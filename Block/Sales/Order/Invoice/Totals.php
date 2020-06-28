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

namespace Mageprince\Paymentfee\Block\Sales\Order\Invoice;

use Magento\Framework\DataObject;
use Magento\Framework\View\Element\Template;
use Magento\Framework\View\Element\Template\Context;
use Magento\Sales\Model\Order\Invoice;
use Mageprince\Paymentfee\Helper\Data;

class Totals extends Template
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var Invoice
     */
    protected $_invoice = null;

    /**
     * @var DataObject
     */
    protected $_source;

    /**
     * @param Context $context
     * @param Data $helper
     * @param array $data
     */
    public function __construct(
        Context $context,
        Data $helper,
        array $data = []
    ) {
        $this->helper = $helper;
        parent::__construct($context, $data);
    }

    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }

    public function getInvoice()
    {
        return $this->getParentBlock()->getInvoice();
    }

    public function initTotals()
    {
        $this->getParentBlock();
        $this->getInvoice();
        $this->getSource();

        if (!$this->getSource()->getPaymentFee()) {
            return $this;
        }

        $paymentFee = $this->getSource()->getPaymentFee();

        if ($paymentFee > 0) {
            $feeTitle = $this->helper->getTitle($this->getSource()->getStoreId());
            $total = new DataObject(
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
