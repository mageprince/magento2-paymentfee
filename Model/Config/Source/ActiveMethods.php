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

namespace Mageprince\Paymentfee\Model\Config\Source;

class ActiveMethods
{
    /**
     * @var \Magento\Payment\Model\Config
     */
    protected $paymentConfig;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * ActiveMethods constructor.
     *
     * @param \Magento\Payment\Model\Config $config
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Payment\Model\Config $config,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->paymentConfig = $config;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * Get active payment methods
     *
     * @return array
     */
    protected function _getPaymentMethods()
    {
        return $this->paymentConfig->getActiveMethods();
    }

    /**
     * Get active payment methods
     *
     * @return array
     */
    public function toOptionArray()
    {
        $methods = [['value'=>'', 'label'=>'']];
        $payments = $this->_getPaymentMethods();

        foreach ($payments as $paymentCode => $paymentModel) {
            $paymentTitle = $this->scopeConfig->getValue('payment/'.$paymentCode.'/title');
            $methods[$paymentCode] = [
                'label'   => $paymentTitle,
                'value' => $paymentCode
            ];
        }
        return $methods;
    }
}
