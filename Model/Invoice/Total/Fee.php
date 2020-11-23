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

namespace Mageprince\Paymentfee\Model\Invoice\Total;

use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Order\Invoice\Total\AbstractTotal;

class Fee extends AbstractTotal
{
    /**
     * @param Invoice $invoice
     * @return $this
     */
    public function collect(Invoice $invoice)
    {
        $order = $invoice->getOrder();
        $feeAmount = $order->getPaymentFee();
        $baseFeeAmount = $order->getBasePaymentFee();
        $feeTaxAmount = $order->getPaymentFeeTax();
        $baseFeeTaxAmount = $order->getBasePaymentFeeTax();
        $invoice->setGrandTotal($invoice->getGrandTotal() + $feeAmount + $feeTaxAmount);
        $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $baseFeeAmount + $baseFeeTaxAmount);
        $invoice->setTaxAmount($invoice->getTaxAmount() + $feeTaxAmount);
        $invoice->setBaseTaxAmount($invoice->getBaseTaxAmount() + $baseFeeTaxAmount);

        return $this;
    }
}
