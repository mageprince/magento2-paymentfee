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

use Magento\Framework\View\Element\Context;
use Magento\Framework\View\Element\Html\Select;
use Magento\Payment\Model\Config as PaymentModelConfig;
use Magento\Payment\Model\Method\Factory as PaymentMethodFactory;
use Magento\Store\Model\ScopeInterface;

class Methods extends Select
{
    /**
     * Payment methods cache
     *
     * @var array
     */
    protected $methods;

    /**
     * @var PaymentModelConfig
     */
    protected $paymentConfig;

    /**
     * @var PaymentMethodFactory
     */
    protected $paymentMethodFactory;

    /**
     * Methods constructor.
     *
     * @param Context $context
     * @param PaymentModelConfig $config
     * @param PaymentMethodFactory $paymentMethodFactory
     * @param array $data
     */
    public function __construct(
        Context $context,
        PaymentModelConfig $config,
        PaymentMethodFactory $paymentMethodFactory,
        array $data = []
    ) {
        parent::__construct($context, $data);
        $this->paymentConfig = $config;
        $this->paymentMethodFactory = $paymentMethodFactory;
    }

    /**
     * Get payment methods
     *
     * @return array
     */
    protected function _getPaymentMethods()
    {
        if ($this->methods === null) {
            $methods = [];
            foreach ($this->_scopeConfig->getValue('payment', ScopeInterface::SCOPE_STORE, null) as $code => $data) {
                if (isset($data['title'])) {
                    $methods[$code] = $data['title'];
                }
            }
            $this->methods = $methods;
        }
        return $this->methods;
    }

    /**
     * Set input name
     *
     * @param string $value
     * @return $this
     */
    public function setInputName($value)
    {
        return $this->setName($value);
    }

    /**
     * Render block HTML
     *
     * @return string
     */
    public function _toHtml()
    {
        if (!$this->getOptions()) {
            foreach ($this->_getPaymentMethods() as $paymentCode => $paymentTitle) {
                $paymentTitle = $paymentTitle . ' - ' . $paymentCode;
                $this->addOption($paymentCode, addslashes($paymentTitle));
            }
        }
        return parent::_toHtml();
    }
}
