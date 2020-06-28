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

namespace Mageprince\Paymentfee\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class PriceType implements ArrayInterface
{
    /**
     * Price type variants
     */
    const TYPE_FIXED = 0;
    const TYPE_PERCENTAGE = 1;
    const TYPE_PER_ROW = 2;
    const TYPE_PER_ITEM = 3;

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return [
            self::TYPE_PERCENTAGE => __('Percentage Price'),
            self::TYPE_FIXED => __('Fixed Price'),
            self::TYPE_PER_ROW => __('Per row'),
            self::TYPE_PER_ITEM => __('Per item')
        ];
    }

    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $optionArray = [];
        $arr = $this->toArray();
        foreach ($arr as $value => $label) {
            $optionArray[] = [
                'value' => $value,
                'label' => $label
            ];
        }
        return $optionArray;
    }
}
