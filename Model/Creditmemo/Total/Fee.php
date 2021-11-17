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

namespace Mageprince\Paymentfee\Model\Creditmemo\Total;

use Magento\Sales\Model\Order\Creditmemo;
use Magento\Sales\Model\Order\Creditmemo\Total\AbstractTotal;
use Mageprince\Paymentfee\Helper\Data;

class Fee extends AbstractTotal
{
    /**
     * @var Data
     */
    protected $paymentHelper;

    /**
     * Fee constructor.
     * @param Data $paymentHelper
     * @param array $data
     */
    public function __construct(
        Data $paymentHelper,
        array $data = []
    ) {
        parent::__construct($data);
        $this->paymentHelper = $paymentHelper;
    }

    /**
     * Collect fee
     *
     * @param Creditmemo $creditmemo
     * @return $this
     */
    public function collect(Creditmemo $creditmemo)
    {
        $creditmemo->setPaymentFee(0);
        $creditmemo->setBasePaymentFee(0);
        $creditmemo->setPaymentFeeTax(0);
        $creditmemo->setBasePaymentFeeTax(0);

        $storeId = $creditmemo->getOrder()->getStoreId();
        if (!$this->paymentHelper->isRefund($storeId)) {
            return $this;
        }

        $order = $creditmemo->getOrder();
        $paymentFee = $order->getPaymentFee();
        $basePaymentFee = $order->getBasePaymentFee();
        $paymentFeeTax = $order->getPaymentFeeTax();
        $basePaymentFeeTax = $order->getBasePaymentFeeTax();

        if ($paymentFee != 0) {
            $creditmemo->setPaymentFee($paymentFee);
            $creditmemo->setBasePaymentFee($basePaymentFee);
            $creditmemo->setPaymentFeeTax($paymentFeeTax);
            $creditmemo->setBasePaymentFeeTax($basePaymentFeeTax);
            $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $paymentFee);
            $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $basePaymentFee);
            $creditmemo->setTaxAmount($creditmemo->getTaxAmount() + $paymentFeeTax);
            $creditmemo->setBaseTaxAmount($creditmemo->getBaseTaxAmount() + $basePaymentFeeTax);
        }

        return $this;
    }
}
