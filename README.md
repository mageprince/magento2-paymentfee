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
 - Set custom titles for fees
 - Refundable fee option in credit memo
 - Disable fees for orders exceeding a maximum amount
 - Customer group restrictions
 - Store view restrictions
 - Set sort order for fee display
 - Supports tax calculation on the fee
   - Assign tax class
   - Choose display type: Incl., Excl., or Both
 - Multi-store and multi-currency support
 - Option to include discount/shipping in subtotal for calculation

## 💡 Payment Fee Types Explained
**1. Fixed Price**

A flat fee is added to the order, no matter what’s in the cart.

Example: Fee = 10 → You pay 10 extra.

**2. Percentage Price**

The fee is a percentage of the cart subtotal.
- If “Include shipping in subtotal” is Yes:
  - Subtotal = Products + Shipping
- If “Include discount in subtotal” is Yes:
  - Subtotal = Subtotal - Discount

Example: Fee = 10%, Subtotal = 36 → Extra = 3.60

**3. Per Row**

Fee is added based on the number of products (rows) in the cart.

Example: Fee = 10, Cart = 2 products → Extra = 20

**4. Per Item**

Fee is based on the total quantity of all products in the cart.

Example: Fee = 10, Cart = 3 items total → Extra = 30

## 🧪 Demo

<b><a href="http://demo.mageprince.com/">Frontend</a>   |   <a href="http://demo.mageprince.com/admin">Backend</a></b>

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

### Checkout Page
<img width="1248" alt="image" src="https://github.com/user-attachments/assets/b22c94e7-5c89-4c48-8929-cde1b6dd68f4" />


### Configuration
<img width="986" alt="image" src="https://github.com/user-attachments/assets/2e8df15a-2ae3-41cf-91f0-9bb975b35eb0" />
<img width="1007" alt="image" src="https://github.com/user-attachments/assets/8239f224-985f-498e-9af7-b84bc2372534" />




