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

namespace Mageprince\Paymentfee\Observer;

use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Quote\Api\CartRepositoryInterface;
use Mageprince\Paymentfee\Helper\Data as FeeHelper;

class ResetPaymentMethod implements ObserverInterface
{
    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * @var CartRepositoryInterface
     */
    private $quoteRepository;

    /**
     * @var FeeHelper
     */
    private $helper;

    /**
     * ResetPaymentMethod constructor.
     * @param CheckoutSession $checkoutSession
     * @param CartRepositoryInterface $quoteRepository
     * @param FeeHelper $helper
     */
    public function __construct(
        CheckoutSession $checkoutSession,
        CartRepositoryInterface $quoteRepository,
        FeeHelper $helper
    ) {
        $this->checkoutSession = $checkoutSession;
        $this->quoteRepository = $quoteRepository;
        $this->helper = $helper;
    }

    /**
     * Remove payment fee of a payment method selected in an earlier session
     *
     * The customer quote loaded on login keeps the payment method chosen before logout, while the
     * checkout no longer shows it as selected, so the fee would be charged without a visible method.
     * Runs after Magento_Checkout has loaded the customer quote on customer_login.
     *
     * @param Observer $observer
     * @return $this
     */
    public function execute(Observer $observer)
    {
        if (!$this->helper->isEnable()) {
            return $this;
        }

        try {
            $quote = $this->checkoutSession->getQuote();
        } catch (LocalizedException $e) {
            return $this;
        }

        if (!$quote->getId() || $quote->getBasePaymentFee() == 0) {
            return $this;
        }

        $quote->getPayment()->setMethod(null);
        $quote->setTotalsCollectedFlag(false)->collectTotals();
        $this->quoteRepository->save($quote);

        return $this;
    }
}
