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

namespace Mageprince\Paymentfee\Controller\Calculate;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Quote\Api\CartRepositoryInterface;

class Paymentfee extends \Magento\Framework\App\Action\Action
{
    /**
     * @var CheckoutSession
     */
    protected $_checkoutSession;

    /**
     * @var JsonFactory
     */
    protected $_resultJson;

    /**
     * @var CartRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * @var Json
     */
    protected $json;

    /**
     * Paymentfee constructor.
     * @param Context $context
     * @param CheckoutSession $checkoutSession
     * @param JsonFactory $resultJson
     * @param CartRepositoryInterface $quoteRepository
     * @param Json $json
     */
    public function __construct(
        Context $context,
        CheckoutSession $checkoutSession,
        JsonFactory $resultJson,
        CartRepositoryInterface $quoteRepository,
        Json $json
    ) {
        parent::__construct($context);
        $this->_checkoutSession = $checkoutSession;
        $this->_resultJson = $resultJson;
        $this->quoteRepository = $quoteRepository;
        $this->json = $json;
    }

    /**
     * Calculate payment fee
     *
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $response = ['errors' => false, 'message' => 'Payment Fee Calculation Done'];
        try {
            $this->quoteRepository->get($this->_checkoutSession->getQuoteId());
            $quote = $this->_checkoutSession->getQuote();
            $payment = $this->json->unserialize($this->getRequest()->getContent());
            $this->_checkoutSession->getQuote()->getPayment()->setMethod($payment['payment']);
            $this->quoteRepository->save($quote->collectTotals());
        } catch (\Exception $e) {
            $response = ['errors' => true, 'message' => $e->getMessage()];
        }
        $resultJson = $this->_resultJson->create();
        return $resultJson->setData($response);
    }
}
