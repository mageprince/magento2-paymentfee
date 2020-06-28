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
     * @param Creditmemo $creditmemo
     * @return $this
     */
    public function collect(Creditmemo $creditmemo)
    {
        $creditmemo->setPaymentFee(0);
        $creditmemo->setBasePaymentFee(0);

        if (!$this->paymentHelper->isRefund()) {
            return $this;
        }

        $fee = $creditmemo->getOrder()->getPaymentFee();
        $creditmemo->setPaymentFee($fee);
        $baseFee = $creditmemo->getOrder()->getBasePaymentFee();
        $creditmemo->setBasePaymentFee($baseFee);

        $creditmemo->setGrandTotal($creditmemo->getGrandTotal() + $fee);
        $creditmemo->setBaseGrandTotal($creditmemo->getBaseGrandTotal() + $baseFee);

        return $this;
    }
}
