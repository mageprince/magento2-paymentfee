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

use Magento\Quote\Model\Quote;

class PerRowCalculator extends AbstractCalculator
{
    /**
     * {@inheritdoc}
     */
    public function calculate(Quote $quote)
    {
        $fee = $this->helper->getFee($quote);
        return $fee * \count($quote->getAllVisibleItems());
    }
}
