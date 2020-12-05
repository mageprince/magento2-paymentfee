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
        $invoice->setPaymentFee(0);
        $invoice->setBasePaymentFee(0);
        $invoice->setPaymentFeeTax(0);
        $invoice->setBasePaymentFeeTax(0);

        $order = $invoice->getOrder();
        $paymentFee = $order->getPaymentFee();
        $basePaymentFee = $order->getBasePaymentFee();
        $paymentFeeTax = $order->getPaymentFeeTax();
        $basePaymentFeeTax = $order->getBasePaymentFeeTax();

        if ($paymentFee != 0) {
            $invoice->setPaymentFee($paymentFee);
            $invoice->setBasePaymentFee($basePaymentFee);
            $invoice->setPaymentFeeTax($paymentFeeTax);
            $invoice->setBasePaymentFeeTax($basePaymentFeeTax);
            $invoice->setGrandTotal($invoice->getGrandTotal() + $paymentFee);
            $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $basePaymentFee);

        }

        return $this;
    }
}
