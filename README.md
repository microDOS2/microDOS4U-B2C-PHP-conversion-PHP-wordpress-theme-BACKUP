# microDOS4U WordPress Theme

Custom WordPress theme for microDOS4U e-commerce, built with WooCommerce.

## Brand Architecture

| What | Name | Visual |
|------|------|--------|
| Website / URL / Company | `microDOS4U` | Plain text |
| Product | `microDOS(2)` | Green/Purple/Pink |

## Color System (CSS Variables)

| Token | Hex | Usage |
|-------|-----|-------|
| `--bg-dark` | `#0a0514` | Page background |
| `--bg-card` | `#150f24` | Cards, panels |
| `--brand-micro` | `#44f80c` | "micro" in product name |
| `--brand-dos` | `#9a02d0` | "DOS" in product name |
| `--brand-two` | `#ff66c4` | "(2)" in product name |

All colors adjustable via WordPress Customizer.

## Installation

1. Download this repository as ZIP
2. In WordPress Admin, go to Appearance > Themes > Add New > Upload Theme
3. Upload the ZIP file and activate

## Required Plugins

| Plugin | Purpose | Cost |
|--------|---------|------|
| WooCommerce | E-commerce | Free |
| WooCommerce Subscriptions | Recurring billing | $279/year |
| WooCommerce Shipping | USPS labels | Free |
| AffiliateWP | Referral tracking | $149/year |
| RewardsWP | Loyalty program | Included |
| Authorize.Net Gateway | Payment processing | Free |

## Products to Configure

| Product | Price | Type |
|---------|-------|------|
| Trial Pack | $12.95 | One-time |
| Explorer Box | $47.56/mo | Subscription (10 doses) |
| Optimizer Box | $128.31/mo | Subscription (30 doses) |
| Master Box | $217.56/mo | Subscription (60 doses) |

## Page Templates

- Home (`page-home.php`)
- User Stories (`page-stories.php`)
- Articles & Studies (`page-articles.php`)
- Metocin Info (`page-metocin.php`)
- Dosage Guide (`page-dosage.php`)
- Legal Disclaimer (`page-legal.php`)
- Contact (`page-contact.php`)

## File Structure

```
microdos-theme/
├── style.css          # Main stylesheet with CSS variables
├── functions.php      # Theme functions, Customizer, WooCommerce support
├── index.php          # Default template
├── header.php         # Site header + navigation
├── footer.php         # Site footer
├── page-*.php         # Page templates (8 files)
├── woocommerce/       # WooCommerce template overrides
│   ├── archive-product.php
│   ├── single-product.php
│   ├── cart/
│   │   └── cart.php
│   └── checkout/
│       └── form-checkout.php
├── js/
│   ├── main.js        # Theme JS (menu, FAQ, scroll)
│   └── woocommerce.js # Cart, checkout behavior
└── README.md          # This file
```

## Customizer Colors

Navigate to Appearance > Customize > microDOS4U Colors:

- Background Colors (page bg, card bg)
- Brand Colors (micro green, DOS purple, (2) pink)
- Text Colors (body, heading, muted)

## Hosting Requirements

- PHP 8.0+
- MySQL 5.7+ or MariaDB 10.3+
- WordPress 6.0+
- SSL certificate (for payments)

## Payment Setup

1. Apply for High Wire Payments merchant account
2. Receive Authorize.Net API credentials
3. Install WooCommerce Authorize.Net plugin
4. Enter credentials in WooCommerce > Settings > Payments

## Support

For questions about this theme, contact the development team.
