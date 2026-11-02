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
 * @package     Mageprince_Paymentfee
 * @copyright   Copyright (c) Mageprince (https://mageprince.com/)
 * @license     https://mageprince.com/end-user-license-agreement
 */

namespace Mageprince\Paymentfee\Plugin;

use Magento\Framework\Locale\FormatInterface;
use Magento\Sales\Model\Order;
use Magento\Sales\Model\Order\CreditmemoFactory;
use Magento\Sales\Model\Order\Invoice;
use Mageprince\Paymentfee\Model\Creditmemo\RequestedFee;

class CreditmemoFactoryFee
{
    /**
     * Credit memo data key holding the payment fee amount to refund
     */
    public const DATA_KEY = 'payment_fee';

    /**
     * @var RequestedFee
     */
    private $requestedFee;

    /**
     * @var FormatInterface
     */
    private $localeFormat;

    /**
     * CreditmemoFactoryFee constructor.
     * @param RequestedFee $requestedFee
     * @param FormatInterface $localeFormat
     */
    public function __construct(
        RequestedFee $requestedFee,
        FormatInterface $localeFormat
    ) {
        $this->requestedFee = $requestedFee;
        $this->localeFormat = $localeFormat;
    }

    /**
     * Capture requested payment fee refund before credit memo totals are collected
     *
     * @param CreditmemoFactory $subject
     * @param Order $order
     * @param array $data
     * @return null
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeCreateByOrder(CreditmemoFactory $subject, Order $order, array $data = [])
    {
        $this->setRequestedFee($data);
        return null;
    }

    /**
     * Capture requested payment fee refund before credit memo totals are collected
     *
     * @param CreditmemoFactory $subject
     * @param Invoice $invoice
     * @param array $data
     * @return null
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function beforeCreateByInvoice(CreditmemoFactory $subject, Invoice $invoice, array $data = [])
    {
        $this->setRequestedFee($data);
        return null;
    }

    /**
     * Store requested fee, or reset it so a previous credit memo's value is never reused
     *
     * The admin form posts creditmemo[payment_fee]; the REST refund endpoints pass
     * arguments.extension_attributes.payment_fee, extracted to an array by CreditmemoDocumentFactory.
     *
     * @param array $data
     * @return void
     */
    private function setRequestedFee(array $data)
    {
        $value = $data[self::DATA_KEY] ?? $data['extension_attributes'][self::DATA_KEY] ?? null;

        $amount = null;
        if ($value !== null && trim((string) $value) !== '') {
            $amount = (float) $this->localeFormat->getNumber($value);
        }
        $this->requestedFee->setBaseAmount($amount);
    }
}
