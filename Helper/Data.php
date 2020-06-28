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

namespace Mageprince\Paymentfee\Helper;

use Magento\Customer\Model\Session as CustomerSession;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Quote\Model\Quote;
use Magento\Store\Model\ScopeInterface;
use Mageprince\Paymentfee\Model\Config\Source\ConfigData;

class Data extends AbstractHelper
{
    /**
     * @var \Magento\Framework\Serialize\Serializer\Json
     */
    private $serialize;

    /**
     * @var array
     */
    public $methodFee = null;

    /**
     * Session quote
     *
     * @var \Magento\Backend\Model\Session\Quote
     */
    protected $_sessionQuote;

    /**
     * @var \Magento\Customer\Api\CustomerRepositoryInterface
     */
    protected $_customerRepositoryInterface;

    /**
     * @var \Magento\Framework\Pricing\Helper\Data
     */
    private $_priceHelper;

    /**
     * Data constructor.
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeInterface
     * @param \Magento\Framework\Serialize\Serializer\Json $serialize
     * @param CustomerSession $customerSession
     * @param \Magento\Backend\Model\Session\Quote $sessionQuote
     * @param \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface
     * @param \Magento\Framework\Pricing\Helper\Data $priceHelper
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeInterface,
        \Magento\Framework\Serialize\Serializer\Json $serialize,
        CustomerSession $customerSession,
        \Magento\Backend\Model\Session\Quote $sessionQuote,
        \Magento\Customer\Api\CustomerRepositoryInterface $customerRepositoryInterface,
        \Magento\Framework\Pricing\Helper\Data $priceHelper
    ) {
        parent::__construct($context);
        $this->serialize = $serialize;
        $this->customerSession = $customerSession;
        $this->_sessionQuote = $sessionQuote;
        $this->_customerRepositoryInterface = $customerRepositoryInterface;
        $this->_priceHelper = $priceHelper;
    }

    /**
     * Get config value
     * @param $config
     * @return mixed
     */
    public function getConfig($path, $storeId = null)
    {
        return $this->scopeConfig->getValue(
            $path,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get module status
     * @return bool
     */
    public function isEnable()
    {
        return $this->getConfig(ConfigData::MODULE_STATUS_XML_PATH);
    }

    /**
     * Get description status
     * @return bool
     */
    public function isDescription()
    {
        return $this->getConfig(ConfigData::PAYMENTFEE_SHOW_DESCRIPTION_XML_PATH);
    }

    /**
     * Get description
     * @return bool
     */
    public function getDescription()
    {
        return $this->getConfig(ConfigData::PAYMENTFEE_DESCRIPTION_XML_PATH);
    }
    /**
     * Get minimum order amount to add payment fee
     * @return bool
     */
    public function getMinOrderTotal()
    {
        return $this->getConfig(ConfigData::PAYMENTFEE_MINORDER_XML_PATH);
    }

    /**
     * Get maximum order amount to add payment fee
     * @return bool
     */
    public function getMaxOrderTotal()
    {
        return $this->getConfig(ConfigData::PAYMENTFEE_MAXORDER_XML_PATH);
    }

    /**
     * Get payment fee title
     * @param int $storeId
     * @return string
     */
    public function getTitle($storeId = null)
    {
        return $this->getConfig(ConfigData::PAYMENTFEE_TITLE_XML_PATH, $storeId);
    }

    /**
     * Get payment fee price type
     * @return bool
     */
    public function getPriceType()
    {
        return $this->getConfig(ConfigData::PAYMENTFEE_PRICETYPE_XML_PATH);
    }

    /**
     * Get allowed customer group
     * @return bool
     */
    public function getAllowedCustomerGroup()
    {
        return $this->getConfig(ConfigData::PAYMENTFEE_CUSTOMERS_XML_PATH);
    }

    /**
     * Get is refund payment fee
     * @return bool
     */
    public function isRefund()
    {
        return (bool) $this->getConfig(ConfigData::PAYMENTFEE_IS_REFUND_XML_PATH);
    }

    /**
     * Get payment fees
     * @return array
     */
    public function _getPaymentFee()
    {
        $paymentFees = $this->getConfig(ConfigData::PAYMENTFEE_AMOUNT_XML_PATH);
        if ($paymentFees) {
            $fees = $this->serialize->unserialize($paymentFees);
            if (is_array($fees)) {
                foreach ($fees as $fee) {
                    $this->methodFee[$fee['payment_method']] = [
                        'fee' => $fee['fee']
                    ];
                }
            }
        }
        return $this->methodFee;
    }

    /**
     * @param Quote $quote
     * @return bool
     */
    public function canApply(Quote $quote)
    {
        $this->_getPaymentFee();
        if ($this->isEnable()) {
            if ($method = $quote->getPayment()->getMethod()) {
                if (isset($this->methodFee[$method])) {
                    return true;
                }
            }
        }
        return false;
    }

    /**
     * @param Quote $quote
     * @return float|int
     */
    public function getFee(Quote $quote)
    {
        $method  = $quote->getPayment()->getMethod();
        $fee = $this->methodFee[$method]['fee'];
        return $fee;
    }

    /**
     * Get selected customer groups
     * @return array
     */
    public function getCustomerGroup()
    {
        $customerGroups = $this->getConfig(ConfigData::PAYMENTFEE_CUSTOMERS_XML_PATH);
        return explode(',', $customerGroups);
    }

    /**
     * @param $baseFee
     * @param $quote
     * @return float|string
     */
    public function getStoreFee($baseFee, $quote)
    {
        return $this->_priceHelper->currencyByStore(
            $baseFee,
            $quote->getStoreId(),
            false,
            false
        );
    }

    /**
     * Check is tax enabled
     * @return bool
     */
    public function isTaxEnabled()
    {
        return $this->getConfig(ConfigData::PAYMENTFEE_TAX_ENABLE_XML_PATH);
    }

    /**
     * Get tax class id
     * @return int
     */
    public function getTaxClassId()
    {
        return $this->getConfig(ConfigData::PAYMENTFEE_TAX_CLASS_XML_PATH);
    }

    /**
     * Check if include shipping in subtotal
     * @return bool
     */
    public function getIsIncludeShipping()
    {
        return $this->getConfig(ConfigData::PAYMENTFEE_SHIPPING_INCLUDE_XML_PATH);
    }

    /**
     * Check if include discount in subtotal
     * @return bool
     */
    public function getIsIncludeDiscount()
    {
        return $this->getConfig(ConfigData::PAYMENTFEE_DISCOUNT_INCLUDE_XML_PATH);
    }
}
