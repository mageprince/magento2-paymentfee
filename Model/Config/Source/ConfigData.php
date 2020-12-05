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
