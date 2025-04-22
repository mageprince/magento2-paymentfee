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
