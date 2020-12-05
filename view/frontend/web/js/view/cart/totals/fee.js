define([
    'Magento_Checkout/js/view/summary/abstract-total',
    'Magento_Checkout/js/model/quote',
    'Magento_Checkout/js/model/totals'
], function (Component, quote, totals) {
    "use strict";
    return Component.extend({
        defaults: {
            template: 'Mageprince_Paymentfee/cart/totals/fee'
        },
        totals: quote.getTotals(),
        title: window.checkoutConfig.mageprince_paymentfee.title,
        description: window.checkoutConfig.mageprince_paymentfee.description,
        isTaxEnabled: window.checkoutConfig.mageprince_paymentfee.isTaxEnabled,
        displayBoth: window.checkoutConfig.mageprince_paymentfee.displayBoth,
        displayInclTax: window.checkoutConfig.mageprince_paymentfee.displayInclTax,
        displayExclTax: window.checkoutConfig.mageprince_paymentfee.displayExclTax,

        isDisplayed: function() {
            return this.getPaymentFee() != 0;
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
        },

        getPaymentFeeExclTax: function () {
            return this.getValue();
        },

        getPaymentFeeInclTax: function () {
            var price = 0;
            if (this.totals() && totals.getSegment('payment_fee')) {
                price = totals.getSegment('payment_fee_incl_tax').value;
            }
            return this.getFormattedPrice(price);
        }
    });
});
