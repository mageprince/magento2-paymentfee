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

use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Checkout\Model\Session as CheckoutSession;

class AddPaymentfeeItem implements ObserverInterface
{
    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * AddPaymentfeeItem constructor.
     * @param CheckoutSession $checkoutSession
     */
    public function __construct(CheckoutSession $checkoutSession)
    {
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * Add custom amount as custom item to payment cart totals
     *
     * @param Observer $observer
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function execute(Observer $observer)
    {
        $cart = $observer->getCart();
        $quote = $this->checkoutSession->getQuote();
        $paymentFee = $quote->getBasePaymentFee();
        if ($paymentFee > 0) {
            $cart->addCustomItem(__('Payment Fee'), 1, $paymentFee, 'paymentfee');
        }
    }
}
