define([
    'jquery',
    'Magento_Sales/order/create/scripts'
], function (jQuery) {
    'use strict';

    AdminOrder.prototype.switchPaymentMethod = function(method){
        jQuery('#edit_form')
            .off('submitOrder')
            .on('submitOrder', function(){
                jQuery(this).trigger('realOrder');
            });
        jQuery('#edit_form').trigger('changePaymentMethod', [method]);
        this.setPaymentMethod(method);
        var data = {};
        data['order[payment_method]'] = method;
        this.loadArea(['card_validation', 'totals'], true, data);
    }
});