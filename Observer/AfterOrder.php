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
