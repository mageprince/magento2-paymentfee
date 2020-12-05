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

namespace Mageprince\Paymentfee\Observer;

use Magento\Framework\Event\ObserverInterface;

class AfterOrder implements ObserverInterface
{
    /**
     * Set payment fee to order
     *
     * @param \Magento\Framework\Event\Observer $observer
     * @return $this
     */
    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        $quote = $observer->getQuote();
        $paymentFee = $quote->getPaymentFee();
        $basePaymentFee = $quote->getBasePaymentFee();
        if (!$paymentFee || !$basePaymentFee) {
            return $this;
        }

        $paymentFeeTax = $quote->getPaymentFeeTax();
        $basePaymentFeeTax = $quote->getBasePaymentFeeTax();
        $order = $observer->getOrder();
        $order->setData('payment_fee', $paymentFee);
        $order->setData('base_payment_fee', $basePaymentFee);
        $order->setData('payment_fee_tax', $paymentFeeTax);
        $order->setData('base_payment_fee_tax', $basePaymentFeeTax);

        return $this;
    }
}
