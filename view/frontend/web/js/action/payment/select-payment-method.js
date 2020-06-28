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
            quote.paymentMethod(paymentMethod);
            totals(isLoading, paymentMethod['method']);
        }
    }
);