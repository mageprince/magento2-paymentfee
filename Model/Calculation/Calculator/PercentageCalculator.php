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

namespace Mageprince\Paymentfee\Model\Calculation\Calculator;

use Magento\Quote\Model\Quote;

class PercentageCalculator extends AbstractCalculator
{
    /**
     * @inheritdoc
     */
    public function calculate(Quote $quote)
    {
        $fee = $this->helper->getFee($quote);

        $subTotal = $quote->getBaseSubtotal();

        if ($this->helper->getIsIncludeShipping()) {
            $subTotal += $quote->getShippingAddress()->getBaseShippingAmount();
        }

        if ($this->helper->getIsIncludeDiscount()) {
            $discount = 0;
            foreach ($quote->getAllItems() as $item) {
                $discount -= $item->getBaseDiscountAmount();
            }
            $subTotal += $discount;
        }

        return ($subTotal * $fee) / 100;
    }
}
