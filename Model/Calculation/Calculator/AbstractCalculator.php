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

namespace Mageprince\Paymentfee\Model\Calculation\Calculator;

use Mageprince\Paymentfee\Helper\Data as FeeHelper;

abstract class AbstractCalculator implements CalculatorInterface
{
    /**
     * @var FeeHelper
     */
    protected $helper;

    /**
     * AbstractCalculation constructor.
     *
     * @param FeeHelper $helper
     */
    public function __construct(FeeHelper $helper)
    {
        $this->helper = $helper;
    }
}
