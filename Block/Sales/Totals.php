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

use Magento\Directory\Model\Currency;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Mageprince\Paymentfee\Helper\Data;

class Totals extends Template
{
    /**
     * @var Data
     */
    protected $helper;
   
    /**
     * @var Currency
     */
    protected $_currency;

    /**
     * @param Context $context
     * @param Data $helper
     * @param Currency $currency
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $helper,
        Currency $currency,
        array $data = []
    ) {
        $this->helper = $helper;
        $this->_currency = $currency;
        parent::__construct($context, $data);
    }

    public function getOrder()
    {
        return $this->getParentBlock()->getOrder();
    }

    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }

    public function getCurrencySymbol()
    {
        return $this->_currency->getCurrencySymbol();
    }

    public function initTotals()
    {
        $this->getParentBlock();
        $this->getOrder();
        $this->getSource();

        $paymentFee = $this->getSource()->getPaymentFee();

        if ($paymentFee > 0) {
            $feeTitle = $this->helper->getTitle($this->getSource()->getStoreId());
            $total = new \Magento\Framework\DataObject(
                [
                    'code' => 'payment_fee',
                    'value' => $paymentFee,
                    'base_value' => $this->getSource()->getBasePaymentFee(),
                    'label' => $feeTitle,
                ]
            );
            $this->getParentBlock()->addTotalBefore($total, 'grand_total');
        }

        return $this;
    }
}
