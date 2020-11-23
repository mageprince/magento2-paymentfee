<?php

/**
 * MagePrince
 * Copyright (C) 2020 Mageprince <info@mageprince.com>
 *
 * @package Mageprince_Paymentfee
 * @copyright Copyright (c) 2020 Mageprince (http://www.mageprince.com/)
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author MagePrince <info@mageprince.com>
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
        $quote->setPaymentFee($fee);
        $quote->setBasePaymentFee($baseFee);
        $quote->setPaymentFeeTax($tax);
        $quote->setBasePaymentFeeTax($baseTax);
        $total->setTotalAmount('tax', $total->getTotalAmount('tax') + $tax);
        $total->setBaseTotalAmount('tax', $total->getBaseTotalAmount('tax') + $baseTax);

        return $this;
    }

    /**
     * @param Quote $quote
     * @param Total $total
     * @return array
     */
    public function fetch(Quote $quote, Total $total)
    {
        $result = [];
        $baseFee = $this->calculator->calculate($quote);
        $fee = $this->helper->getStoreFee($baseFee, $quote);

        if ($fee > 0) {
            $result = [
                'code' => $this->getCode(),
                'title' => $this->getLabel(),
                'value' => $fee
            ];
        }

        return $result;
    }

    /**
     * Get tax request for quote address
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
     * @return \Magento\Framework\Phrase
     */
    public function getLabel()
    {
        return __($this->helper->getTitle());
    }
}
