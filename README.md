# NewebPay Logistics Integration PHP SDK

[繁體中文](README_TW.md) | [English](README.md)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/carllee1983/newebpay-logistics.svg)](https://packagist.org/packages/carllee1983/newebpay-logistics)
[![Run Tests](https://github.com/CarlLee1983/newebpay-logistics/actions/workflows/run-tests.yml/badge.svg)](https://github.com/CarlLee1983/newebpay-logistics/actions/workflows/run-tests.yml)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE.md)
[![PHP Version](https://img.shields.io/badge/php-%5E8.1-blue)](https://packagist.org/packages/carllee1983/newebpay-logistics)

A PHP SDK for integrating with the NewebPay Logistics API (藍新金流物流 API). This package simplifies the process of creating transactions, selecting stores (CVS), querying orders, and printing shipping labels.

## Features

- **Easy Integration**: Simple and intuitive API for common logistics operations.
- **Framework Agnostic**: Works with any PHP project (bundled with Laravel support).
- **Type Safety**: Utilizes PHP encapsulation to ensure data validity.
- **Frontend Friendly**: Built-in support for generating HTML forms for frontend frameworks (Vue, React, etc.).

## Requirements

- PHP ^8.1
- `ext-json` extension
- `ext-openssl` extension

## Installation

Install via Composer:

```bash
composer require carllee1983/newebpay-logistics
```

## Configuration

Initialize the library with your NewebPay Logistics credentials.

```php
use CarlLee\NewebPayLogistics\NewebPayLogistics;

$merchantId = 'YOUR_MERCHANT_ID';
$hashKey = 'YOUR_HASH_KEY';
$hashIV = 'YOUR_HASH_IV';

// The last argument is optional for server URL override (defaults to testing env)
$logistics = NewebPayLogistics::create($merchantId, $hashKey, $hashIV);
```

### Parameters

| Parameter | Application | Description |
| :--- | :--- | :--- |
| `$merchantId` | **Required** | Your Shop ID (商店代號) provided by NewebPay. |
| `$hashKey` | **Required** | Hash Key (HashKey) for encryption. |
| `$hashIV` | **Required** | Hash IV (HashIV) for encryption. |
| `$serverUrl` | Optional | API Base URL. Default is testing env: `https://ccore.newebpay.com/API/Logistic`. |

## Laravel Integration

This package includes a Service Provider and Facade for seamless Laravel integration.

### 1. Installation

Publish the configuration file:

```bash
php artisan vendor:publish --tag=newebpay-logistics-config
```

### 2. Configuration

Add the following variables to your `.env` file:

```env
NEWEBPAY_LOGISTICS_MERCHANT_ID=your_merchant_id
NEWEBPAY_LOGISTICS_HASH_KEY=your_hash_key
NEWEBPAY_LOGISTICS_HASH_IV=your_hash_iv
# Optional: Override server URL (default is testing environment)
# NEWEBPAY_LOGISTICS_SERVER_URL=https://core.newebpay.com/API/Logistic
```

### 3. Usage via Facade

You can use the `NewebPayLogistics` facade anywhere in your Laravel application.

```php
use NewebPayLogistics;
use CarlLee\NewebPayLogistics\Parameter\ShipType;

public function map()
{
    $map = NewebPayLogistics::map();
    $map->setShipType(ShipType::SEVEN_ELEVEN);
    // ...
    
    return NewebPayLogistics::generateForm($map);
}
```

## Usage

### 1. Map Interface (電子地圖)

Redirect the user to the logistics provider's map interface to select a store (7-11, FamilyMart, etc.).

```php
use CarlLee\NewebPayLogistics\Parameter\LgsType;
use CarlLee\NewebPayLogistics\Parameter\ShipType;

$map = $logistics->map();
$map->setMerchantTradeNo('TRADE' . time());
$map->setLgsType(LgsType::B2C);
$map->setShipType(ShipType::SEVEN_ELEVEN);
$map->setIsCollection('N'); // N: No collection, Y: Collection
$map->setServerReplyURL('https://example.com/reply');

// Generate auto-submit HTML form
echo $logistics->generateForm($map);
```

#### 1-1. Frontend Integration (Vue/React)

If you are using a decoupled architecture, return the form data as JSON.

```php
$formBuilder = $logistics->getFormBuilder();
$data = $formBuilder->getFormData($map);

echo json_encode($data);
// Output: {"url": "...", "method": "post", "params": { ... }}
```

### 2. Create Order (建立物流訂單)

Create a logistics order for B2C or C2C transactions.

```php
use CarlLee\NewebPayLogistics\Parameter\LgsType;
use CarlLee\NewebPayLogistics\Parameter\ShipType;
use CarlLee\NewebPayLogistics\Parameter\TradeType;

$create = $logistics->createOrder();
$create->setMerchantTradeNo('TRADE' . time());
$create->setLgsType(LgsType::B2C);
$create->setShipType(ShipType::SEVEN_ELEVEN);
$create->setTradeType(TradeType::PAYMENT);
$create->setReceiverName('John Doe');
$create->setReceiverCellPhone('0912345678');
// ... more parameters

// This API usually responds with a redirect or direct result depending on the content
// But strictly speaking, Create Order in some NewebPay flows is also a form post redirect.
// Check official docs for specific flow.
```

### 3. Query Order (查詢物流訂單)

Query the status of an existing order.

```php
$query = $logistics->query();
$query->setLogisticsID('LOGISTICS_ID');
$query->setMerchantTradeNo('TRADE_NO');

// This is a direct API call
$response = $logistics->send($query);

if ($response->isSuccess()) {
    echo "Status: " . $response->getMessage();
}
```

## Examples

Check the `examples/` directory for complete vanilla PHP scripts:

- [Map Operation](examples/example_map.php)
- [Create Order](examples/example_create_order.php)
- [Query Order](examples/example_query_order.php)
- [Print Order](examples/example_print_order.php)
- [Laravel Integration](examples/laravel_example.php)

## API Reference

For detailed API documentation, please refer to the files in the `doc/` directory:

- [English API Reference](doc/api_reference_en.md)
- [Traditional Chinese API Reference](doc/api_reference_tw.md)

## FAQ

**Q: How do I change the environment to Production?**
A: By default, `NewebPayLogistics::create()` uses the testing URL. Pass the production URL as the 4th argument, or set `NEWEBPAY_LOGISTICS_SERVER_URL` in Laravel `.env`.

**Q: Can I use this without Laravel?**
A: Yes! The package is framework-agnostic. See the [Configuration](#configuration) section.

**Q: I get a "Validation Validation" error.**
A: Ensure all required fields are set. The SDK validates the request parameters before generating the payload.

**Q: I get a "Check Value Error" or Decryption failure.**
A: This usually means your Merchant ID, Hash Key, or Hash IV are incorrect. Please double check them. Also ensure there are no extra spaces in your keys. In some cases, ensure your input data doesn't contain encoded characters that might mess up the length calculation.

## Development

```bash
# Run tests
composer test

# Check code style
composer check

# Fix code style
composer format
```

## Contributing

Please see [CONTRIBUTING.md](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email carllee1983@gmail.com instead of using the issue tracker.

## License

MIT
