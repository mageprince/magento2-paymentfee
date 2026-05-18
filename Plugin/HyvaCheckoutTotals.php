<?php

namespace Mageprince\Paymentfee\Plugin;

use Mageprince\Paymentfee\Helper\Data as PaymentFeeHelper;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\View\Element\AbstractBlock;
use Magento\Framework\View\Element\Template;

class HyvaCheckoutTotals
{
    /**
     * @var PaymentFeeHelper
     */
    private $paymentFeeHelper;

    /**
     * @var CheckoutSession
     */
    private $checkoutSession;

    /**
     * HyvaCheckoutTotals constructor.
     * @param PaymentFeeHelper $paymentFeeHelper
     * @param CheckoutSession $checkoutSession
     */
    public function __construct(
        PaymentFeeHelper $paymentFeeHelper,
        CheckoutSession $checkoutSession
    ) {
        $this->paymentFeeHelper = $paymentFeeHelper;
        $this->checkoutSession = $checkoutSession;
    }

    /**
     * Add payment fee block on Hyva Checkout.
     *
     * @param object $subject
     * @param false|AbstractBlock $result
     * @param Template $parent
     * @param array $segment
     * @return false|AbstractBlock
     */
    public function afterGetTotalBlock(
        $subject,
        $result,
        Template $parent,
        array $segment
    ) {
        if ($segment['code'] === 'payment_fee_incl_tax') {
            return false;
        }

        if ($segment['code'] === 'payment_fee') {
            $result = $parent->addChild(
                'mageprince_paymentfee',
                Template::class
            );
            $result->setTemplate('Mageprince_Paymentfee::totals/hyva-checkout-fee.phtml');

            if ($result) {
                $result->setData('segment', $this->prepareSegment($segment));
            }
        }

        return $result;
    }

    /**
     * @param array $segment
     * @return array
     */
    private function prepareSegment(array $segment)
    {
        $paymentFee = (float) ($segment['value'] ?? 0);
        $paymentFeeInclTax = $paymentFee;

        if ($this->paymentFeeHelper->isTaxEnabled()) {
            $quote = $this->checkoutSession->getQuote();
            $address = $quote->isVirtual() ? $quote->getBillingAddress() : $quote->getShippingAddress();
            $paymentFeeInclTax += (float) $address->getPaymentFeeTax();
        }

        $isDescription = $this->paymentFeeHelper->isDescription();

        $segment['title'] = $this->paymentFeeHelper->getTitle();
        $segment['description'] = $isDescription ? $this->paymentFeeHelper->getDescription() : '';
        $segment['value_excl_tax'] = $paymentFee;
        $segment['value_incl_tax'] = $paymentFeeInclTax;
        $segment['is_tax_enabled'] = $this->paymentFeeHelper->isTaxEnabled();
        $segment['display_both'] = $this->paymentFeeHelper->displayExclTax() && $this->paymentFeeHelper->displayInclTax();
        $segment['display_incl_tax'] = $this->paymentFeeHelper->displayInclTax();
        $segment['display_excl_tax'] = $this->paymentFeeHelper->displayExclTax();

        return $segment;
    }
}
