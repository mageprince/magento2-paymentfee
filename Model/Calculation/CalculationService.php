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

namespace Mageprince\Paymentfee\Model\Calculation;

use Magento\Framework\Exception\ConfigurationMismatchException;
use Magento\Quote\Model\Quote;
use Mageprince\Paymentfee\Helper\Data as FeeHelper;
use Mageprince\Paymentfee\Model\Calculation\Calculator\CalculatorInterface;
use Psr\Log\LoggerInterface;

class CalculationService implements CalculatorInterface
{
    /**
     * @var CalculatorFactory
     */
    protected $factory;

    /**
     * @var FeeHelper
     */
    protected $helper;

    /**
     * @var LoggerInterface
     */
    protected $logger;

    /**
     * CalculationService constructor.
     * @param CalculatorFactory $factory
     * @param FeeHelper $helper
     * @param LoggerInterface $logger
     */
    public function __construct(CalculatorFactory $factory, FeeHelper $helper, LoggerInterface $logger)
    {
        $this->factory = $factory;
        $this->helper = $helper;
        $this->logger = $logger;
    }

    /**
     * @inheritdoc
     */
    public function calculate(Quote $quote)
    {
        if (!$this->helper->isEnable()) {
            return 0;
        }

        if (!$this->helper->canApply($quote)) {
            return 0;
        }

        if (!$this->hasMinOrderTotal($quote)) {
            return 0;
        }

        if ($this->hasMaxOrderTotal($quote)) {
            return 0;
        }

        if (!$this->isAllowCustomerGroup($quote)) {
            return 0;
        }

        try {
            return $this->factory->get()->calculate($quote);
        } catch (ConfigurationMismatchException $e) {
            $this->logger->error($e);
            return 0.0;
        }
    }

    /**
     * Check is order has minimum order total
     *
     * @param Quote $quote
     * @return bool
     */
    private function hasMinOrderTotal(Quote $quote)
    {
        $amount = $quote->getBaseSubtotal();
        return $this->helper->getMinOrderTotal() <= $amount ? true: false;
    }

    /**
     * Check is order has maximum order total
     *
     * @param Quote $quote
     * @return bool
     */
    private function hasMaxOrderTotal(Quote $quote)
    {
        $amount = $quote->getBaseSubtotal();
        return $this->helper->getMaxOrderTotal() <= $amount ? true: false;
    }

    /**
     * Check is customer group allowed
     *
     * @param Quote $quote
     * @return bool
     */
    public function isAllowCustomerGroup(Quote $quote)
    {
        $customerGroups = $quote->getCustomerGroupId();
        $selectedCustomers = $this->helper->getCustomerGroup();
        if (in_array($customerGroups, $selectedCustomers)) {
            return true;
        }
    }
}
