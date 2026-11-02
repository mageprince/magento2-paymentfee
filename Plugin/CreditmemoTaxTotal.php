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

namespace Mageprince\Paymentfee\Plugin;

use Magento\Sales\Model\Order\Creditmemo;
use Magento\Sales\Model\Order\Creditmemo\Total\Tax;
use Mageprince\Paymentfee\Model\Creditmemo\Total\Fee;

class CreditmemoTaxTotal
{
    /**
     * Remove the unrefunded fee tax reserved by the payment fee collector once core tax is collected
     *
     * @param Tax $subject
     * @param Tax $result
     * @param Creditmemo $creditmemo
     * @return Tax
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterCollect(Tax $subject, $result, Creditmemo $creditmemo)
    {
        $reservedTax = (float) $creditmemo->getData(Fee::RESERVED_TAX);
        $baseReservedTax = (float) $creditmemo->getData(Fee::BASE_RESERVED_TAX);
        if ($reservedTax || $baseReservedTax) {
            $creditmemo->setTaxAmount($creditmemo->getTaxAmount() - $reservedTax);
            $creditmemo->setBaseTaxAmount($creditmemo->getBaseTaxAmount() - $baseReservedTax);
            $creditmemo->setData(Fee::RESERVED_TAX, 0);
            $creditmemo->setData(Fee::BASE_RESERVED_TAX, 0);
        }

        return $result;
    }
}
