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

namespace Mageprince\Paymentfee\Model\Total;

use Magento\Quote\Model\Quote\Address\Total;
use Mageprince\Paymentfee\Model\Calculation\Calculator\CalculatorInterface;
use Magento\Quote\Model\Quote;
use Magento\Quote\Model\Quote\Address;
use Magento\Quote\Api\Data\ShippingAssignmentInterface;
use Mageprince\Paymentfee\Helper\Data as FeeHelper;
use Magento\Tax\Model\Calculation;

class Fee extends Address\Total\AbstractTotal
{
    /**
     * @var CalculatorInterface
     */
    protected $calculator;

    /**
     * @var FeeHelper
     */
    protected $helper;

    /**
     * @var Calculation
     */
    private $taxCalculator;

    /**
     * Fee constructor.
     *
     * @param CalculatorInterface $calculator
     * @param FeeHelper $helper
     * @param Calculation $taxCalculator
     */
    public function __construct(
        CalculatorInterface $calculator,
        FeeHelper $helper,
        Calculation $taxCalculator
    ) {
        $this->calculator = $calculator;
        $this->helper = $helper;
        $this->taxCalculator = $taxCalculator;
    }

    /**
     * Collect fee
     *
     * @param Quote $quote
     * @param \Magento\Quote\Api\Data\ShippingAssignmentInterface $shippingAssignment
     * @param Total $total
     * @return $this
     */
    public function collect(
        Quote $quote,
        ShippingAssignmentInterface $shippingAssignment,
        Total $total
    ) {
        parent::collect($quote, $shippingAssignment, $total);

        $total->setTotalAmount($this->getCode(), 0);
        $total->setBaseTotalAmount($this->getCode(), 0);
        $total->setTotalAmount('payment_fee_tax', 0);
        $total->setBaseTotalAmount('payment_fee_tax', 0);

        if (!count($shippingAssignment->getItems())) {
            return $this;
        }

        $baseFee = 0;
        $fee = 0;
        $baseTax = 0;
        $tax = 0;

        if ($this->helper->isEnable()) {
            $baseFee = $this->calculator->calculate($quote);
            $fee = $this->helper->getStoreFee($baseFee, $quote);

            if ($this->helper->isTaxEnabled()) {
                $taxClassId = $this->helper->getTaxClassId();
                if ($taxClassId) {
                    $taxRateRequest = $this->_getRateTaxRequest($quote);
                    $taxRateRequest->setProductClassId($taxClassId);
                    $rate = $this->taxCalculator->getRate($taxRateRequest);
                    $baseTax = $this->taxCalculator->calcTaxAmount(
                        $baseFee,
                        $rate,
                        false,
                        true
                    );
                    $tax = $this->taxCalculator->calcTaxAmount(
                        $fee,
                        $rate,
                        false,
                        true
                    );
                }
            }
        }

        $total->setTotalAmount($this->getCode(), $fee);
        $total->setBaseTotalAmount($this->getCode(), $baseFee);

        $total->setPaymentFee($fee);
        $total->setBasePaymentFee($baseFee);
        $total->setBasePaymentFeeTax($baseTax);
        $total->setPaymentFeeTax($tax);
        $total->addBaseTotalAmount('tax', $baseTax);
        $total->addTotalAmount('tax', $tax);

        $quote->setPaymentFee($fee);
        $quote->setBasePaymentFee($baseFee);
        $quote->setPaymentFeeTax($tax);
        $quote->setBasePaymentFeeTax($baseTax);

        return $this;
    }

    /**
     * Fetch fee
     *
     * @param Quote $quote
     * @param Total $total
     * @return array
     */
    public function fetch(Quote $quote, Total $total)
    {
        $fee = $total->getPaymentFee();

        $result = [
            [
                'code' => $this->getCode(),
                'title' => __($this->helper->getTitle()),
                'value' => $fee
            ]
        ];

        if ($this->helper->isTaxEnabled() &&
            $this->helper->displayInclTax() &&
            !$this->helper->isBackendArea()
        ) {
            $address = $this->getAddressFromQuote($quote);
            $result [] = [
                'code' => 'payment_fee_incl_tax',
                'value' => $fee + $address->getPaymentFeeTax()
            ];
        }

        return $result;
    }

    /**
     * Get address
     *
     * @param Quote $quote
     * @return Address
     */
    private function getAddressFromQuote(Quote $quote)
    {
        return $quote->isVirtual() ? $quote->getBillingAddress() : $quote->getShippingAddress();
    }

    /**
     * Get tax request for quote address
     *
     * @param Quote $quote
     * @return \Magento\Framework\DataObject
     */
    private function _getRateTaxRequest(Quote $quote)
    {
        $rateTaxRequest = $this->taxCalculator->getRateRequest(
            $quote->getShippingAddress(),
            $quote->getBillingAddress(),
            $quote->getCustomerTaxClassId(),
            $quote->getStore(),
            $quote->getCustomerId()
        );
        return $rateTaxRequest;
    }

    /**
     * Get label
     *
     * @return \Magento\Framework\Phrase
     */
    public function getLabel()
    {
        return __($this->helper->getTitle());
    }
}
