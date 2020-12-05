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

class PaymentFeeConfigProvider implements ConfigProviderInterface
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
     * PaymentFeeConfigProvider constructor.
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
        $displayExclTax = $this->helper->displayExclTax();
        $displayInclTax = $this->helper->displayInclTax();

        $isDescription = $this->helper->isDescription();
        $description = $this->helper->getDescription();

        $paymentFeeConfig = [
            'mageprince_paymentfee' => [
                'isEnabled' => $this->helper->isEnable(),
                'title' => $this->helper->getTitle(),
                'description' => $isDescription ? $description : false,
                'isTaxEnabled' => $this->helper->isTaxEnabled(),
                'displayBoth' => ($displayExclTax && $displayInclTax),
                'displayInclTax' => $this->helper->displayInclTax(),
                'displayExclTax' => $this->helper->displayExclTax()
            ]
        ];

        return $paymentFeeConfig;
    }
}
