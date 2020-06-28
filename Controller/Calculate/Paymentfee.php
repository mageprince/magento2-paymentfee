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

namespace Mageprince\Paymentfee\Controller\Calculate;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\Json\Helper\Data as FeeHelper;
use Magento\Checkout\Model\Session as CheckoutSession;
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
     * @var FeeHelper
     */
    protected $_helper;

    /**
     * @var CartRepositoryInterface
     */
    protected $quoteRepository;

    /**
     * Paymentfee constructor.
     * @param Context $context
     * @param CheckoutSession $checkoutSession
     * @param FeeHelper $helper
     * @param JsonFactory $resultJson
     * @param CartRepositoryInterface $quoteRepository
     */
    public function __construct(
        Context $context,
        CheckoutSession $checkoutSession,
        FeeHelper $helper,
        JsonFactory $resultJson,
        CartRepositoryInterface $quoteRepository
    ) {
        parent::__construct($context);
        $this->_checkoutSession = $checkoutSession;
        $this->_helper = $helper;
        $this->_resultJson = $resultJson;
        $this->quoteRepository = $quoteRepository;
    }

    /**
     * Calculate payment fee
     * @return \Magento\Framework\Controller\Result\Json
     */
    public function execute()
    {
        $response = ['errors' => false, 'message' => 'Payment Fee Calculation Done'];
        try {
            $this->quoteRepository->get($this->_checkoutSession->getQuoteId());
            $quote = $this->_checkoutSession->getQuote();
            $payment = $this->_helper->jsonDecode($this->getRequest()->getContent());
            $this->_checkoutSession->getQuote()->getPayment()->setMethod($payment['payment']);
            $this->quoteRepository->save($quote->collectTotals());
        } catch (\Exception $e) {
            $response = ['errors' => true, 'message' => $e->getMessage()];
        }
        $resultJson = $this->_resultJson->create();
        return $resultJson->setData($response);
    }
}
