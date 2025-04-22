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

namespace Mageprince\Paymentfee\Model\Invoice\Total;

use Magento\Sales\Model\Order\Invoice;
use Magento\Sales\Model\Order\Invoice\Total\AbstractTotal;

class Fee extends AbstractTotal
{
    /**
     * Collect invoice totals
     *
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

        if ($order->getInvoiceCollection()->getTotalCount()) {
            return $this;
        }

        $paymentFee = $order->getPaymentFee();
        $basePaymentFee = $order->getBasePaymentFee();
        $paymentFeeTax = $order->getPaymentFeeTax();
        $basePaymentFeeTax = $order->getBasePaymentFeeTax();

        if ($paymentFee != 0) {
            $invoice->setPaymentFee($paymentFee);
            $invoice->setBasePaymentFee($basePaymentFee);
            $invoice->setPaymentFeeTax($paymentFeeTax);
            $invoice->setBasePaymentFeeTax($basePaymentFeeTax);
            $invoice->setGrandTotal($invoice->getGrandTotal() + $paymentFee + $paymentFeeTax);
            $invoice->setBaseGrandTotal($invoice->getBaseGrandTotal() + $basePaymentFee + $basePaymentFeeTax);
            $invoice->setTaxAmount($invoice->getTaxAmount() + $paymentFeeTax);
            $invoice->setBaseTaxAmount($invoice->getBaseTaxAmount() + $basePaymentFeeTax);
        }

        return $this;
    }
}
