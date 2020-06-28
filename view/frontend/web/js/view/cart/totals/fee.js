define([
    'Mageprince_Paymentfee/js/view/cart/summary/fee'
], function (Component) {
    'use strict';

    return Component.extend({
        getDescription: function () {
            var description = window.checkoutConfig.fee_description;
            if(description) {
                return description;
            }
            return false;
        }
    });
});