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

namespace Mageprince\Paymentfee\Block\Adminhtml\System\Form\Field;

use Magento\Backend\Block\Template\Context;
use Magento\Config\Block\System\Config\Form\Field\FieldArray\AbstractFieldArray;
use Magento\Framework\DataObject;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Element\BlockInterface;
use Mageprince\Paymentfee\Model\Config\Source\ActiveMethods;

class PaymentFee extends AbstractFieldArray
{
    protected $_columns = [];

    /**
     * @var Methods
     */
    protected $_typeRenderer;

    protected $_searchFieldRenderer;

    /**
     * @var ActiveMethods
     */
    protected $activeMethods;

    /**
     * PaymentFee constructor.
     * @param Context $context
     * @param ActiveMethods $activeMethods
     * @param array $data
     */
    public function __construct(
        Context $context,
        ActiveMethods $activeMethods,
        array $data = []
    ) {
        $this->activeMethods = $activeMethods;
        parent::__construct($context, $data);
    }

    /**
     * Prepare to render
     * @throws LocalizedException
     */
    protected function _prepareToRender()
    {
        $this->_typeRenderer        = null;
        $this->_searchFieldRenderer = null;

        $this->addColumn(
            'payment_method',
            ['label' => __('Payment Method'), 'renderer' => $this->_getPaymentRenderer()]
        );

        $this->addColumn('fee', ['label' => __('Fee')]);
        $this->_addAfter       = false;
        $this->_addButtonLabel = __('Add Fee');
    }

    /**
     * @return BlockInterface|Methods
     * @throws LocalizedException
     */
    protected function _getPaymentRenderer()
    {
        if (!$this->_typeRenderer) {
            $this->_typeRenderer = $this->getLayout()->createBlock(
                Methods::class,
                '',
                ['data' => ['is_render_to_js_template' => true]]
            );
            $this->_typeRenderer->setClass('payemtfee_select');
        }
        return $this->_typeRenderer;
    }

    /**
     * Prepare existing row data object
     * @param DataObject $row
     * @throws LocalizedException
     */
    protected function _prepareArrayRow(DataObject $row)
    {
        $optionExtraAttr = [];
        $optionExtraAttr['option_' . $this->_getPaymentRenderer()->calcOptionHash($row->getData('payment_method'))] =
            'selected="selected"';
        $row->setData(
            'option_extra_attrs',
            $optionExtraAttr
        );
    }
}
