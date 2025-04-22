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

namespace Mageprince\Paymentfee\Model\Config\Source;

class ConfigData
{
    /**
     * Module status config path
     */
    const MODULE_STATUS_XML_PATH = 'paymentfee/general/active';

    /**
     * Is refund payment fee
     */
    const PAYMENTFEE_IS_REFUND_XML_PATH = 'paymentfee/paymentfee_settings/refund';

    /**
     * Payment fee discriotion status config path
     */
    const PAYMENTFEE_SHOW_DESCRIPTION_XML_PATH = 'paymentfee/general/is_description';

    /**
     * Payment fee description content config path
     */
    const PAYMENTFEE_DESCRIPTION_XML_PATH = 'paymentfee/general/description';

    /**
     * Payment fee minimum order amount config path
     */
    const PAYMENTFEE_MINORDER_XML_PATH = 'paymentfee/paymentfee_settings/minorderamount';

    /**
     * Payment fee maximum order amount config path
     */
    const PAYMENTFEE_MAXORDER_XML_PATH = 'paymentfee/paymentfee_settings/maxorderamount';

    /**
     * Payment fee title config path
     */
    const PAYMENTFEE_TITLE_XML_PATH = 'paymentfee/general/title';

    /**
     * Payment fee pricetype config path
     */
    const PAYMENTFEE_PRICETYPE_XML_PATH = 'paymentfee/paymentfee_settings/pricetype';

    /**
     * Payment fee is shipping include to subtotal config path
     */
    const PAYMENTFEE_SHIPPING_INCLUDE_XML_PATH = 'paymentfee/paymentfee_settings/include_shipping';

    /**
     * Payment fee is discount include to subtotal config path
     */
    const PAYMENTFEE_DISCOUNT_INCLUDE_XML_PATH = 'paymentfee/paymentfee_settings/include_discount';

    /**
     * Payment fee customer group config path
     */
    const PAYMENTFEE_CUSTOMERS_XML_PATH = 'paymentfee/paymentfee_settings/customers';

    /**
     * Payment fee amount config path
     */
    const PAYMENTFEE_AMOUNT_XML_PATH = 'paymentfee/paymentfee_settings/paymentfee';

    /**
     * Payment fee tax enable config path
     */
    const PAYMENTFEE_TAX_ENABLE_XML_PATH = 'paymentfee/tax/enable';

    /**
     * Payment fee tax class config path
     */
    const PAYMENTFEE_TAX_CLASS_XML_PATH = 'paymentfee/tax/tax_class';

    /**
     * Payment fee tax class display config path
     */
    const PAYMENTFEE_TAX_DISPLAY_XML_PATH = 'paymentfee/tax/display';
}
