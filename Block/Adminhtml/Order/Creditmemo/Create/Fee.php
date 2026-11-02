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

namespace Mageprince\Paymentfee\Block\Adminhtml\Order\Creditmemo\Create;

use Magento\Backend\Block\Template;
use Magento\Framework\Currency\Data\Currency as CurrencyData;
use Magento\Framework\DataObjectFactory;
use Magento\Sales\Model\Order\Creditmemo;
use Mageprince\Paymentfee\Helper\Data;

/**
 * Editable payment fee refund row on the new credit memo form, like core "Refund Shipping"
 */
class Fee extends Template
{
    /**
     * @var string
     */
    protected $_template = 'Mageprince_Paymentfee::order/creditmemo/create/fee.phtml';

    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var DataObjectFactory
     */
    protected $dataObjectFactory;

    /**
     * Fee constructor.
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
     * Get credit memo
     *
     * @return Creditmemo
     */
    public function getSource()
    {
        return $this->getParentBlock()->getSource();
    }

    /**
     * Add the editable payment fee row to the credit memo totals
     *
     * @return $this
     */
    public function initTotals()
    {
        if (!$this->canRefund()) {
            return $this;
        }

        $this->getParentBlock()->addTotal(
            $this->dataObjectFactory->create()->setData([
                'code' => 'payment_fee',
                'block_name' => $this->getNameInLayout()
            ]),
            'adjustments'
        );

        return $this;
    }

    /**
     * Check if the order has a payment fee that may be refunded with this credit memo
     *
     * Visibility depends on the order, not on the credit memo amount, so the field stays
     * available after the admin sets it to 0 and updates totals.
     *
     * @return bool
     */
    public function canRefund()
    {
        $source = $this->getSource();
        $order = $source->getOrder();
        if (!$this->helper->isRefund($order->getStoreId()) || $order->getBasePaymentFee() == 0) {
            return false;
        }

        $invoice = $source->getInvoice();
        return !$invoice || $invoice->getBasePaymentFee() != 0;
    }

    /**
     * Get field label
     *
     * @return string
     */
    public function getLabel()
    {
        $title = $this->helper->getTitle($this->getSource()->getStoreId());
        if ($this->helper->isTaxEnabled($this->getSource()->getStoreId())) {
            return (string) __('Refund %1 (Excl. Tax)', $title);
        }
        return (string) __('Refund %1', $title);
    }

    /**
     * Get payment fee to refund in base currency, formatted for the input
     *
     * @return string
     */
    public function getValue()
    {
        return $this->getSource()->getOrder()->getBaseCurrency()->formatPrecision(
            (float) $this->getSource()->getBasePaymentFee(),
            2,
            ['display' => CurrencyData::NO_SYMBOL],
            false,
            false
        );
    }
}
