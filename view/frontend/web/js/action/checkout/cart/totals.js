define([
    'ko',
    'jquery',
    'Magento_Checkout/js/model/quote',
    'mage/storage',
    'mage/url',
    'Magento_Checkout/js/action/get-totals'
], function (ko, $, quote, storage, urlBuilder, getTotalsAction) {
        'use strict';

        return function (isLoading, payment) {
            let isEnabled = window.checkoutConfig.mageprince_paymentfee.isEnabled;
            if (isEnabled) {
                return storage.post(
                    urlBuilder.build('paymentfee/calculate/paymentfee'),
                    JSON.stringify({payment: payment})
                ).done(function (response) {
                    if (response) {
                        let deferred = $.Deferred();

                        isLoading(false);
                        getTotalsAction([], deferred);
                    }
                }).fail(function () {
                    isLoading(false);
                });
            }
        };
    }
);
