define(
    [
        'jquery',
        'ko',
        'Magento_Checkout/js/model/quote',
        'Mageprince_Paymentfee/js/action/checkout/cart/totals'
    ],
    function($, ko ,quote, totals) {
        'use strict';
        var isLoading = ko.observable(false);

        return function (paymentMethod) {
            var $isEnabled = window.checkoutConfig.mageprince_paymentfee.isEnabled;
            if($isEnabled != 0) {
                quote.paymentMethod(paymentMethod);
                totals(isLoading, paymentMethod['method']);
            }
        }
    }
);
