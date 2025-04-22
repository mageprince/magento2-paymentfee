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

namespace Mageprince\Paymentfee\Helper;

use Magento\Backend\App\Area\FrontNameResolver;
use Magento\Backend\Model\Session\Quote as SessionQuote;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\State;
use Magento\Framework\Pricing\Helper\Data as PriceHelper;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Quote\Model\Quote;
use Magento\Store\Model\ScopeInterface;
use Mageprince\Paymentfee\Model\Config\Source\ConfigData;

class Data extends AbstractHelper
{
    /**
     * @var Json
     */
    private $serialize;

    /**
     * @var array
     */
    protected $methodFee = [];

    /**
     * @var SessionQuote
     */
    protected $_sessionQuote;

    /**
     * @var CustomerRepositoryInterface
     */
    protected $_customerRepositoryInterface;

    /**
     * @var PriceHelper
     */
    protected $_priceHelper;

    /**
     * @var State
     */
    protected $state;

    /**
     * Data constructor.
     *
     * @param Context $context
     * @param Json $serialize
     * @param SessionQuote $sessionQuote
     * @param CustomerRepositoryInterface $customerRepositoryInterface
     * @param PriceHelper $priceHelper
     * @param State $state
     */
    public function __construct(
        Context $context,
        Json $serialize,
        SessionQuote $sessionQuote,
        CustomerRepositoryInterface $customerRepositoryInterface,
        PriceHelper $priceHelper,
        State $state
    ) {
        $this->serialize = $serialize;
        $this->_sessionQuote = $sessionQuote;
        $this->_customerRepositoryInterface = $customerRepositoryInterface;
        $this->_priceHelper = $priceHelper;
        $this->state = $state;
        parent::__construct($context);
    }

    /**
     * Get config value
     *
     * @param string $path
     * @param int $storeId
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
     *
     * @return bool
     */
    public function isEnable()
    {
        return $this->scopeConfig->isSetFlag(
            ConfigData::MODULE_STATUS_XML_PATH,
            ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Get description status
     *
     * @return bool
     */
    public function isDescription()
    {
        return $this->getConfig(ConfigData::PAYMENTFEE_SHOW_DESCRIPTION_XML_PATH);
    }

    /**
     * Get description
     *
     * @return bool
     */
    public function getDescription()
    {
        return $this->getConfig(ConfigData::PAYMENTFEE_DESCRIPTION_XML_PATH);
    }
    /**
     * Get minimum order amount to add payment fee
     *
     * @return bool
     */
    public function getMinOrderTotal()
    {
        return $this->getConfig(ConfigData::PAYMENTFEE_MINORDER_XML_PATH);
    }

    /**
     * Get maximum order amount to add payment fee
     *
     * @return bool
     */
    public function getMaxOrderTotal()
    {
        return $this->getConfig(ConfigData::PAYMENTFEE_MAXORDER_XML_PATH);
    }

    /**
     * Get payment fee title
     *
     * @param int $storeId
     * @return string
     */
    public function getTitle($storeId = null)
    {
        return $this->getConfig(ConfigData::PAYMENTFEE_TITLE_XML_PATH, $storeId);
    }

    /**
     * Get payment fee price type
     *
     * @return bool
     */
    public function getPriceType()
    {
        return $this->getConfig(ConfigData::PAYMENTFEE_PRICETYPE_XML_PATH);
    }

    /**
     * Get allowed customer group
     *
     * @return bool
     */
    public function getAllowedCustomerGroup()
    {
        return $this->getConfig(ConfigData::PAYMENTFEE_CUSTOMERS_XML_PATH);
    }

    /**
     * Get is refund payment fee
     *
     * @param int $storeId
     * @return bool
     */
    public function isRefund($storeId)
    {
        return (bool) $this->getConfig(ConfigData::PAYMENTFEE_IS_REFUND_XML_PATH, $storeId);
    }

    /**
     * Get payment fees
     *
     * @return array
     */
    public function getPaymentFee()
    {
        if (!$this->methodFee) {
            $paymentFees = $this->getConfig(ConfigData::PAYMENTFEE_AMOUNT_XML_PATH);
            if (is_string($paymentFees) && !empty($paymentFees)) {
                $paymentFees = $this->serialize->unserialize($paymentFees);
            }

            if (is_array($paymentFees)) {
                foreach ($paymentFees as $paymentFee) {
                    $this->methodFee[$paymentFee['payment_method']] = [
                        'fee' => $paymentFee['fee']
                    ];
                }
            }
        }

        return $this->methodFee;
    }

    /**
     * Check can apply
     *
     * @param Quote $quote
     * @return bool
     */
    public function canApply(Quote $quote)
    {
        $this->getPaymentFee();
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
     * Get fee
     *
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
     *
     * @return array
     */
    public function getCustomerGroup()
    {
        $customerGroups = $this->getConfig(ConfigData::PAYMENTFEE_CUSTOMERS_XML_PATH);
        return explode(',', $customerGroups);
    }

    /**
     * Get store fee
     *
     * @param float $baseFee
     * @param Quote $quote
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
     *
     * @param int $storeId
     * @return bool
     */
    public function isTaxEnabled($storeId = null)
    {
        return $this->scopeConfig->isSetFlag(
            ConfigData::PAYMENTFEE_TAX_ENABLE_XML_PATH,
            ScopeInterface::SCOPE_STORE,
            $storeId
        );
    }

    /**
     * Get tax class id
     *
     * @return int
     */
    public function getTaxClassId()
    {
        return $this->getConfig(ConfigData::PAYMENTFEE_TAX_CLASS_XML_PATH);
    }

    /**
     * Get tax display type
     *
     * @param int $storeId
     * @return int
     */
    public function getTaxDisplay($storeId = null)
    {
        return $this->getConfig(ConfigData::PAYMENTFEE_TAX_DISPLAY_XML_PATH, $storeId);
    }

    /**
     * Check is incl. tax displayed
     *
     * @param int $storeId
     * @return bool
     */
    public function displayInclTax($storeId = null)
    {
        return in_array($this->getTaxDisplay($storeId), [2,3]);
    }

    /**
     * Check is excl. tax displayed
     *
     * @param int $storeId
     * @return bool
     */
    public function displayExclTax($storeId = null)
    {
        return in_array($this->getTaxDisplay($storeId), [1,3]);
    }

    /**
     * Check is tax suffix added
     *
     * @return bool
     */
    public function displaySuffix()
    {
        return ($this->getTaxDisplay() == 3);
    }

    /**
     * Check if include shipping in subtotal
     *
     * @return bool
     */
    public function getIsIncludeShipping()
    {
        return $this->getConfig(ConfigData::PAYMENTFEE_SHIPPING_INCLUDE_XML_PATH);
    }

    /**
     * Check if include discount in subtotal
     *
     * @return bool
     */
    public function getIsIncludeDiscount()
    {
        return $this->getConfig(ConfigData::PAYMENTFEE_DISCOUNT_INCLUDE_XML_PATH);
    }

    /**
     * Check is backend area
     *
     * @return bool
     */
    public function isBackendArea()
    {
        $isBackend = false;
        try {
            $areaCode = $this->state->getAreaCode();
            if ($areaCode == FrontNameResolver::AREA_CODE) {
                $isBackend = true;
            }
        } catch (\Exception $e) {
        }
        return $isBackend;
    }
}
