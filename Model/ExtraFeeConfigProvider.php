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

namespace Mageprince\Paymentfee\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Checkout\Model\Session;
use Mageprince\Paymentfee\Helper\Data;
use Mageprince\Paymentfee\Model\Calculation\Calculator\CalculatorInterface;

class ExtraFeeConfigProvider implements ConfigProviderInterface
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * @var Session
     */
    protected $checkoutSession;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    protected $logger;

    /**
     * @var CalculatorInterface
     */
    protected $calculator;

    /**
     * ExtraFeeConfigProvider constructor.
     * @param Data $helper
     * @param Session $checkoutSession
     * @param CalculatorInterface $calculator
     */
    public function __construct(
        Data $helper,
        Session $checkoutSession,
        CalculatorInterface $calculator
    ) {
        $this->helper = $helper;
        $this->checkoutSession = $checkoutSession;
        $this->calculator = $calculator;
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        $extraFeeConfig = [];
        $enabled = $this->helper->isEnable();
        $isDescription = $this->helper->isDescription();
        $description = $this->helper->getDescription();
        $extraFeeConfig['paymentfee_title'] = $this->helper->getTitle();
        $extraFeeConfig['paymentfee_description'] = ($enabled && $isDescription) ? $description : false;
        return $extraFeeConfig;
    }
}
