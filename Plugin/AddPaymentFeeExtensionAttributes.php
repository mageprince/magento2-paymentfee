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

namespace Mageprince\Paymentfee\Plugin;

use Magento\Framework\Api\ExtensibleDataInterface;
use Magento\Framework\Api\ExtensionAttributesFactory;
use Magento\Framework\Api\SearchResultsInterface;

/**
 * Expose payment fee columns as extension attributes on order, invoice and credit memo repositories
 */
class AddPaymentFeeExtensionAttributes
{
    /**
     * Payment fee columns, also the extension attribute codes
     */
    private const FIELDS = [
        'payment_fee' => 'setPaymentFee',
        'base_payment_fee' => 'setBasePaymentFee',
        'payment_fee_tax' => 'setPaymentFeeTax',
        'base_payment_fee_tax' => 'setBasePaymentFeeTax'
    ];

    /**
     * @var ExtensionAttributesFactory
     */
    private $extensionAttributesFactory;

    /**
     * AddPaymentFeeExtensionAttributes constructor.
     * @param ExtensionAttributesFactory $extensionAttributesFactory
     */
    public function __construct(ExtensionAttributesFactory $extensionAttributesFactory)
    {
        $this->extensionAttributesFactory = $extensionAttributesFactory;
    }

    /**
     * Add payment fee to a loaded order, invoice or credit memo
     *
     * @param object $subject
     * @param ExtensibleDataInterface $entity
     * @return ExtensibleDataInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGet($subject, $entity)
    {
        $this->addPaymentFee($entity);
        return $entity;
    }

    /**
     * Add payment fee to loaded orders, invoices or credit memos
     *
     * @param object $subject
     * @param SearchResultsInterface $searchResult
     * @return SearchResultsInterface
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function afterGetList($subject, $searchResult)
    {
        foreach ($searchResult->getItems() as $entity) {
            $this->addPaymentFee($entity);
        }
        return $searchResult;
    }

    /**
     * Copy payment fee columns into the entity's extension attributes
     *
     * @param ExtensibleDataInterface $entity
     * @return void
     */
    private function addPaymentFee($entity)
    {
        $extensionAttributes = $entity->getExtensionAttributes()
            ?: $this->extensionAttributesFactory->create(get_class($entity));

        foreach (self::FIELDS as $field => $setter) {
            $extensionAttributes->{$setter}((float) $entity->getData($field));
        }
        $entity->setExtensionAttributes($extensionAttributes);
    }
}
