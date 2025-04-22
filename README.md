# Magento 2 Payment Fee

The Magento 2 Payment Fee extension by MagePrince allows store owners to add extra charges for specific payment methods. These fees are shown throughout the customer journey — on the cart, checkout, order summary, invoice, credit memo, and sales emails. Admins can configure different types of fees, control visibility based on customer groups or store views, and even apply rules like disabling fees over a certain order amount. The extension also supports tax and refund settings for complete flexibility.

[![Latest Stable Version](https://poser.pugx.org/mageprince/magento2-paymentfee/v)](//packagist.org/packages/mageprince/magento2-paymentfee)
[![Total Downloads](https://poser.pugx.org/mageprince/magento2-paymentfee/downloads)](//packagist.org/packages/mageprince/magento2-paymentfee)
[![Monthly Downloads](https://poser.pugx.org/mageprince/magento2-paymentfee/d/monthly)](//packagist.org/packages/mageprince/magento2-paymentfee)
[![License](https://poser.pugx.org/mageprince/magento2-paymentfee/license)](//packagist.org/packages/mageprince/magento2-paymentfee)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/mageprince/magento2-FAQ/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/mageprince/magento2-FAQ/?branch=master)
[![Code Intelligence Status](https://scrutinizer-ci.com/g/mageprince/magento2-FAQ/badges/code-intelligence.svg?b=master)](https://scrutinizer-ci.com/code-intelligence)

## ✨ Features
 - Enable or disable the extension from admin configuration
 - Add payment fees for specific payment methods (e.g., COD, PayPal)
 - Fee types supported:
   - Fixed Price
   - Percentage of Order
   - Per Item
   - Per Row
 - Display fees on:
   - Checkout Page
   - Order Summary
   - Sales Emails (Order, Invoice, Credit Memo)
 - Admin Order View
 - Set custom titles and descriptions for fees
 - Refundable fee option in credit memo
 - Disable fees for orders exceeding a maximum amount
 - Customer group restrictions
 - Store view restrictions
 - Supports default extra fee (applies even without specific method match)
 - Set sort order for fee display
 - Supports tax calculation on the fee
   - Assign tax class
   - Choose display type: Incl., Excl., or Both
 - Multi-store and multi-currency support
 - Option to include discount/shipping in subtotal for calculation

## 🔧 Admin Configuration

**Navigate to:** Admin Panel → Stores → Configuration → MagePrince → Payment Fee

You can:
 - Enable/disable the module
 - Set global and per-method fees
 - Configure refund behavior
 - Restrict by store view or customer group
 - Manage fee visibility and label

## 🧪 Demo

<b><a href="http://demo2.mageprince.com/">Frontend</a>   |   <a href="http://demo2.mageprince.com/admin">Backend</a></b>

## 🚀 Installation Instructions

### Option 1: Install via Composer (recommended)

```
composer require mageprince/magento2-paymentfee
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy
```

### Option 2: Manual Installation
1. Copy the content of the repo to the Magento 2 `app/code/Mageprince/Paymentfee`
2. Run the following Magento CLI commands:
```
php bin/magento setup:upgrade
php bin/magento setup:di:compile
php bin/magento setup:static-content:deploy
```

## 🤝 Contribution

Want to contribute to this extension? The quickest way is to <a href="https://help.github.com/articles/about-pull-requests/">open a pull request</a> on GitHub.

## 🛠 Support

If you encounter any problems or bugs, please <a href="https://github.com/mageprince/magento2-paymentfee/issues">open an issue</a> on GitHub.

## 📸 Screenshots

<h3>Checkout Page</h3>
<img src="https://raw.githubusercontent.com/mageprince/all-module-screenshots/master/PaymentFee/checkout_page_2.png" />

<h3>Configuration</h3>
<img src="https://raw.githubusercontent.com/mageprince/all-module-screenshots/master/PaymentFee/paymentfee-config1.jpg" />
<img src="https://raw.githubusercontent.com/mageprince/all-module-screenshots/master/PaymentFee/paymentfee-config2.jpg" />


