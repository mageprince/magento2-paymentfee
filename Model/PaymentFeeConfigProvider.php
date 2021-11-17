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
use Mageprince\Paymentfee\Helper\Data;

class PaymentFeeConfigProvider implements ConfigProviderInterface
{
    /**
     * @var Data
     */
    protected $helper;

    /**
     * PaymentFeeConfigProvider constructor.
     *
     * @param Data $helper
     */
    public function __construct(Data $helper)
    {
        $this->helper = $helper;
    }

    /**
     * Get payment fee config
     *
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
