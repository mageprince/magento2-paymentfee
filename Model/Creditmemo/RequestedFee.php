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
 * @package     Mageprince_Paymentfee
 * @copyright   Copyright (c) Mageprince (https://mageprince.com/)
 * @license     https://mageprince.com/end-user-license-agreement
 */

namespace Mageprince\Paymentfee\Model\Creditmemo;

/**
 * Holds the payment fee amount the admin entered for the credit memo being created,
 * so the credit memo total collector can read it during collectTotals()
 */
class RequestedFee
{
    /**
     * @var float|null
     */
    private $baseAmount;

    /**
     * Set requested refund amount in base currency, null when not entered
     *
     * @param float|null $baseAmount
     * @return $this
     */
    public function setBaseAmount($baseAmount)
    {
        $this->baseAmount = $baseAmount;
        return $this;
    }

    /**
     * Get requested refund amount in base currency, null when not entered
     *
     * @return float|null
     */
    public function getBaseAmount()
    {
        return $this->baseAmount;
    }
}
