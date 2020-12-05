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

namespace Mageprince\Paymentfee\Block\Sales;

use Magento\Framework\DataObjectFactory;
use Magento\Framework\View\Element\Template;
use Mageprince\Paymentfee\Helper\Data;

class Totals extends Template
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var DataObjectFactory
     */
    protected $dataObjectFactory;

    /**
     * Totals constructor.
     * @param Template\Context $context
     * @param Data $helper
     * @param DataObjectFactory $dataObjectFactory
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        Data $helper,
        DataObjectFactory $dataObjectFactory,
        array $data = []
    ) {
        $this->helper = $helper;
        $this->dataObjectFactory = $dataObjectFactory;
        parent::__construct($context, $data);
    }

    /**
     * @return mixed
     */
    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }


    /**
     * @return $this
     */
    public function initTotals()
    {
        $parent = $this->getParentBlock();
        $source = $this->getSource();

        if ($source->getPaymentFee() == 0) {
            return $this;
        }

        $paymentFeeTitle = $this->helper->getTitle($source->getStoreId());

        $paymentFeeExclTax = $source->getPaymentFee();
        $paymentFeeExclTaxTotal = [
            'code' => 'payment_fee',
            'strong' => false,
            'value' => $paymentFeeExclTax,
            'label' => $paymentFeeTitle,
        ];

        $paymentFeeInclTax = $paymentFeeExclTax + $source->getPaymentFeeTax();
        $paymentFeeInclTaxTotal = [
            'code' => 'payment_fee_incl_tax',
            'strong' => false,
            'value' => $paymentFeeInclTax,
            'label' => $paymentFeeTitle,
        ];

        if ($this->helper->displayExclTax() && $this->helper->displayInclTax()) {
            $inclTxt = __('Incl. Tax');
            $exclTxt = __('Excl. Tax');
            $paymentFeeInclTaxTotal['label'] .= ' ' . $inclTxt;
            $paymentFeeExclTaxTotal['label'] .= ' ' . $exclTxt;
        }

        if ($this->helper->displayExclTax()) {
            $parent->addTotal(
                $this->dataObjectFactory->create()->setData($paymentFeeExclTaxTotal),
                'shipping'
            );
        }

        if ($this->helper->displayInclTax()) {
            $parent->addTotal(
                $this->dataObjectFactory->create()->setData($paymentFeeInclTaxTotal),
                'shipping'
            );
        }

        return $this;
    }
}
