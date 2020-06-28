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

        $fee = $invoice->getOrder()->getPaymentFee();
        $invoice->setPaymentFee($fee);
        $baseFee = $invoice->getOrder()->getBasePaymentFee();
        $invoice->setBasePaymentFee($baseFee);

        $invoice->setGrandTotal($invoice->getGrandTotal() + $fee);
        $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $baseFee);

        return $this;
    }
}
