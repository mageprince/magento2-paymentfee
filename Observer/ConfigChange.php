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
use Magento\Framework\Event\Observer as EventObserver;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Config\Storage\WriterInterface;

class ConfigChange implements ObserverInterface
{
    /**
     * @var RequestInterface
     */
    protected $request;

    /**
     * @var WriterInterface
     */
    protected $configWriter;

    /**
     * ConfigChange constructor.
     * @param RequestInterface $request
     * @param WriterInterface $configWriter
     */
    public function __construct(
        RequestInterface $request,
        WriterInterface $configWriter
    ) {
        $this->request = $request;
        $this->configWriter = $configWriter;
    }

    /**
     * Add payment fee sort order
     *
     * @param EventObserver $observer
     * @return $this
     */
    public function execute(EventObserver $observer)
    {
        $paymentFeeParams = $this->request->getParam('groups');
        $paymentFeeSettings = $paymentFeeParams['general']['fields'];
        if (isset($paymentFeeSettings['total_sortorder']['value'])) {
            $totalsSortOrderVal = $paymentFeeSettings['total_sortorder']['value'];
            $this->configWriter->save('sales/totals_sort/mageprince_paymentfee', $totalsSortOrderVal);
        }
        return $this;
    }
}
