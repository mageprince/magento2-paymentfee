<?php

/**
 * MagePrince
 * Copyright (C) 2020 Mageprince <info@mageprince.com>
 *
 * @package Mageprince_Extrafee
 * @copyright Copyright (c) 2020 Mageprince (http://www.mageprince.com/)
 * @license http://opensource.org/licenses/gpl-3.0.html GNU General Public License,version 3 (GPL-3.0)
 * @author MagePrince <info@mageprince.com>
 */

namespace Mageprince\Paymentfee\Model\Pdf;

use Magento\Sales\Model\Order\Pdf\Total\DefaultTotal;
use Magento\Tax\Helper\Data;
use Magento\Tax\Model\Calculation;
use Magento\Tax\Model\ResourceModel\Sales\Order\Tax\CollectionFactory;
use Mageprince\Paymentfee\Helper\Data as FeeHelper;

class Fee extends DefaultTotal
{
    /**
     * @var FeeHelper
     */
    protected $helper;

    /**
     * Fee constructor.
     * @param Data $taxHelper
     * @param Calculation $taxCalculation
     * @param CollectionFactory $ordersFactory
     * @param FeeHelper $helper
     * @param array $data
     */
    public function __construct(
        Data $taxHelper,
        Calculation $taxCalculation,
        CollectionFactory $ordersFactory,
        FeeHelper $helper,
        array $data = []
    ) {
        $this->helper = $helper;
        parent::__construct(
            $taxHelper,
            $taxCalculation,
            $ordersFactory,
            $data
        );
    }

    /**
     * Get fee amount
     * @return float
     */
    public function getAmount()
    {
        return $this->getOrder()->getPaymentFee();
    }

    /**
     * Get fee title
     * @return string
     */
    public function getTitle()
    {
        $storeId = $this->getOrder()->getStoreId();
        return $this->helper->getTitle($storeId);
    }
}
