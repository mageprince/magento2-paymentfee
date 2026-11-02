<?php

/**
 * Mageprince
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the mageprince.com license that is
 * available through the world-wide-web at this URL:
 * https://mageprince.com/end-user-license-agreement
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this extension to newer
 * version in the future.
 *
 * @category    Mageprince
 * @package     Mageprince_MageAI
 * @copyright   Copyright (c) Mageprince (https://mageprince.com/)
 * @license     https://mageprince.com/end-user-license-agreement
 */

namespace Mageprince\Paymentfee\Model\Creditmemo\Total;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Pricing\PriceCurrencyInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\Creditmemo;
use Magento\Sales\Model\Order\Creditmemo\Total\AbstractTotal;
use Mageprince\Paymentfee\Helper\Data;
use Mageprince\Paymentfee\Model\Creditmemo\RequestedFee;

class Fee extends AbstractTotal
{
    /**
     * Credit memo data keys for fee tax kept out of the core tax collector's refundable tax
     */
    public const RESERVED_TAX = 'payment_fee_tax_reserved';
    public const BASE_RESERVED_TAX = 'base_payment_fee_tax_reserved';

    /**
     * @var Data
     */
    protected $paymentHelper;

    /**
     * @var RequestedFee
     */
    protected $requestedFee;

    /**
     * @var PriceCurrencyInterface
     */
    protected $priceCurrency;

    /**
     * Fee constructor.
     * @param Data $paymentHelper
     * @param RequestedFee $requestedFee
     * @param PriceCurrencyInterface $priceCurrency
     * @param array $data
     */
    public function __construct(
        Data $paymentHelper,
        RequestedFee $requestedFee,
        PriceCurrencyInterface $priceCurrency,
        array $data = []
    ) {
        parent::__construct($data);
        $this->paymentHelper = $paymentHelper;
        $this->requestedFee = $requestedFee;
        $this->priceCurrency = $priceCurrency;
    }

    /**
     * Collect fee
     *
     * Refunds the remaining (not yet refunded) payment fee by default, or the amount the admin
     * entered on the credit memo form. Runs before the core tax collector so the fee tax is
     * excluded from the tax that collector still considers refundable.
     *
     * @param Creditmemo $creditmemo
     * @return $this
     * @throws LocalizedException
     */
    public function collect(Creditmemo $creditmemo)
    {
        $creditmemo->setPaymentFee(0);
        $creditmemo->setBasePaymentFee(0);
        $creditmemo->setPaymentFeeTax(0);
        $creditmemo->setBasePaymentFeeTax(0);
        $creditmemo->setData(self::RESERVED_TAX, 0);
        $creditmemo->setData(self::BASE_RESERVED_TAX, 0);

        $order = $creditmemo->getOrder();
        if (!$this->paymentHelper->isRefund($order->getStoreId()) || $order->getBasePaymentFee() == 0) {
            return $this;
        }

        // The whole fee is charged on the first invoice, so other invoices have none to refund
        $invoice = $creditmemo->getInvoice();
        if ($invoice && $invoice->getBasePaymentFee() == 0) {
            return $this;
        }

        $refunded = $this->getRefundedFee($order);
        $paymentFee = $order->getPaymentFee() - $refunded['payment_fee'];
        $basePaymentFee = $order->getBasePaymentFee() - $refunded['base_payment_fee'];
        $paymentFeeTax = $order->getPaymentFeeTax() - $refunded['payment_fee_tax'];
        $basePaymentFeeTax = $order->getBasePaymentFeeTax() - $refunded['base_payment_fee_tax'];

        $desiredAmount = $this->requestedFee->getBaseAmount();
        if ($desiredAmount !== null) {
            $desiredAmount = $this->priceCurrency->round($desiredAmount);

            // Note: ($x > $y + 0.0001) means ($x > $y) for floats
            if ($desiredAmount > $this->priceCurrency->round($basePaymentFee) + 0.0001) {
                throw new LocalizedException(
                    __(
                        'Maximum payment fee amount allowed to refund is: %1',
                        $order->getBaseCurrency()->format(max($basePaymentFee, 0), null, false)
                    )
                );
            }

            // Note: ($x < $y - 0.0001) means ($x < $y) for floats
            if ($desiredAmount < $basePaymentFee - 0.0001) {
                $ratio = $desiredAmount / $order->getBasePaymentFee();
                $paymentFee = $this->priceCurrency->round($order->getPaymentFee() * $ratio);
                $basePaymentFee = $desiredAmount;
                $paymentFeeTax = $this->priceCurrency->round($order->getPaymentFeeTax() * $ratio);
                $basePaymentFeeTax = $this->priceCurrency->round($order->getBasePaymentFeeTax() * $ratio);
            }
        }

        if ($basePaymentFee <= 0) {
            $paymentFee = $basePaymentFee = $paymentFeeTax = $basePaymentFeeTax = 0;
        }

        $creditmemo->setPaymentFee($paymentFee);
        $creditmemo->setBasePaymentFee($basePaymentFee);
        $creditmemo->setPaymentFeeTax($paymentFeeTax);
        $creditmemo->setBasePaymentFeeTax($basePaymentFeeTax);
        $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $paymentFee + $paymentFeeTax);
        $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $basePaymentFee + $basePaymentFeeTax);

        // Fee tax that stays unrefunded is added to the tax amount too, so the core tax collector does not
        // refund it on the last credit memo; Plugin\CreditmemoTaxTotal removes it again afterwards
        $reservedTax = max($order->getPaymentFeeTax() - $refunded['payment_fee_tax'] - $paymentFeeTax, 0);
        $baseReservedTax = max(
            $order->getBasePaymentFeeTax() - $refunded['base_payment_fee_tax'] - $basePaymentFeeTax,
            0
        );
        $creditmemo->setData(self::RESERVED_TAX, $reservedTax);
        $creditmemo->setData(self::BASE_RESERVED_TAX, $baseReservedTax);
        $creditmemo->setTaxAmount($creditmemo->getTaxAmount() + $paymentFeeTax + $reservedTax);
        $creditmemo->setBaseTaxAmount($creditmemo->getBaseTaxAmount() + $basePaymentFeeTax + $baseReservedTax);

        return $this;
    }

    /**
     * Get payment fee already refunded by the order's existing credit memos
     *
     * @param Order $order
     * @return array
     */
    private function getRefundedFee(Order $order)
    {
        $refunded = [
            'payment_fee' => 0,
            'base_payment_fee' => 0,
            'payment_fee_tax' => 0,
            'base_payment_fee_tax' => 0
        ];

        $creditmemos = $order->getCreditmemosCollection();
        if (!$creditmemos) {
            return $refunded;
        }

        foreach ($creditmemos as $creditmemo) {
            if ($creditmemo->getState() == Creditmemo::STATE_CANCELED) {
                continue;
            }
            foreach (array_keys($refunded) as $field) {
                $refunded[$field] += (float) $creditmemo->getData($field);
            }
        }

        return $refunded;
    }
}
