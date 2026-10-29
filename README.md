# Magento 2 Payment Fee

<p>
<a href="https://packagist.org/packages/mageprince/magento2-paymentfee"><img src="https://img.shields.io/packagist/v/mageprince/magento2-paymentfee?style=for-the-badge&logo=packagist&logoColor=F28D1A&label=packagist&color=2F7FC1" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/mageprince/magento2-paymentfee/stats"><img src="https://img.shields.io/packagist/dt/mageprince/magento2-paymentfee?style=for-the-badge&logo=packagist&logoColor=white&label=downloads&color=D4B53A" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/mageprince/magento2-paymentfee/stats"><img src="https://img.shields.io/packagist/dm/mageprince/magento2-paymentfee?style=for-the-badge&label=monthly&color=D4B53A" alt="Monthly Downloads"></a>
<a href="https://www.gnu.org/licenses/gpl-3.0"><img src="https://img.shields.io/badge/license-GPL--3.0-232F3E?style=for-the-badge" alt="License: GPL-3.0"></a>
<a href="https://business.adobe.com/products/magento/magento-commerce.html"><img src="https://img.shields.io/badge/magento-2.4-6CB83A?style=for-the-badge" alt="Magento 2.4"></a>
<a href="https://www.hyva.io/"><img src="https://img.shields.io/badge/hyv%C3%A4-compatible-0A1A3F?style=for-the-badge" alt="Hyvä compatible"></a>
<a href="https://mage-os.org/"><img src="https://img.shields.io/badge/mage--os-compatible-F26322?style=for-the-badge" alt="Mage-OS compatible"></a>
<a href="https://scrutinizer-ci.com/g/mageprince/magento2-paymentfee/?branch=master"><img src="https://img.shields.io/scrutinizer/quality/g/mageprince/magento2-paymentfee/master?style=for-the-badge&logo=scrutinizerci&label=code%20quality" alt="Scrutinizer Code Quality"></a>
</p>

The Magento 2 Payment Fee extension by MagePrince allows store owners to add extra charges for specific payment methods. These fees are shown throughout the customer journey — on the cart, checkout, order summary, invoice, credit memo, and sales emails. Admins can configure different types of fees, control visibility based on customer groups or store views, and even apply rules like disabling fees over a certain order amount. The extension also supports tax and refund settings for complete flexibility.

<img src="https://github.com/hyva-themes.png?size=40" height="20" align="top" alt="Hyvä"/> **Hyvä Theme compatible** – works out of the box on Hyvä, no compat module needed.<br/>
<img src="https://github.com/mage-os.png?size=40" height="20" align="top" alt="Mage-OS"/> **Mage-OS compatible** – supports Mage-OS as well as Magento Open Source and Adobe Commerce.
  
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
 - <a href="https://www.hyva.io/" target="_blank">Hyva</a> Compatibile

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

## ✅ Compatibility

- Magento Open Source (Community Edition): 2.4.4 – 2.4.8
- Adobe Commerce (Enterprise Edition): 2.4.4 – 2.4.8
  
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




