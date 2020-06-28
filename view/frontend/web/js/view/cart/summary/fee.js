define([
    'Magento_Checkout/js/view/summary/abstract-total',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/totals'
], function (Component, quote, totals) {
    "use strict";
    return Component.extend({
        defaults: {
            template: 'Mageprince_Paymentfee/cart/summary/fee'
        },
        totals: quote.getTotals(),
        paymentFeeTitle: window.checkoutConfig.paymentfee_title,
        paymentFeeDescription: window.checkoutConfig.paymentfee_description,
        isDisplayed: function() {
            return this.getPaymentFee() > 0;
        },
        getPaymentFee: function() {
            var price = 0;
            if (this.totals() && totals.getSegment('payment_fee')) {
                price = parseFloat(totals.getSegment('payment_fee').value);
            }
            return price;
        },
        getValue: function() {
            return this.getFormattedPrice(this.getPaymentFee());
        }
    });
});