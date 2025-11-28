# NewebPay Logistics Integration PHP SDK

[繁體中文](README_TW.md)

A PHP SDK for integrating with NewebPay Logistics API (藍新金流物流 API).

## Installation

Install via Composer:

```bash
composer require carllee1983/newebpay-logistics
```

## Configuration

Initialize the library with your Merchant ID, Hash Key, and Hash IV:

```php
use CarlLee\NewebPayLogistics\NewebPayLogistics;

$merchantId = 'YOUR_MERCHANT_ID';
$hashKey = 'YOUR_HASH_KEY';
$hashIV = 'YOUR_HASH_IV';

$logistics = NewebPayLogistics::create($merchantId, $hashKey, $hashIV);
```

## Laravel Integration

### Installation

1. Publish the configuration file:

```bash
php artisan vendor:publish --tag=newebpay-logistics-config
```

2. Add the following variables to your `.env` file:

```env
NEWEBPAY_LOGISTICS_MERCHANT_ID=your_merchant_id
NEWEBPAY_LOGISTICS_HASH_KEY=your_hash_key
NEWEBPAY_LOGISTICS_HASH_IV=your_hash_iv
# Optional: Override server URL (default is testing environment)
# NEWEBPAY_LOGISTICS_SERVER_URL=https://core.newebpay.com/API/Logistic
```

### Usage

You can use the `NewebPayLogistics` facade:

```php
use NewebPayLogistics;

public function map()
{
    $map = NewebPayLogistics::map();
    // ...
}
```

## Usage

### 1. Map Interface (電子地圖)

Generate the HTML form to redirect the user to the logistics provider's map interface for store selection.

```php
use CarlLee\NewebPayLogistics\FormBuilder;

use CarlLee\NewebPayLogistics\Parameter\LgsType;
use CarlLee\NewebPayLogistics\Parameter\ShipType;

$map = $logistics->map();
$map->setMerchantTradeNo('TRADE' . time());
$map->setLgsType(LgsType::B2C);
$map->setShipType(ShipType::SEVEN_ELEVEN);
$map->setIsCollection('N'); // N: No collection, Y: Collection
$map->setServerReplyURL('https://example.com/reply');

$formBuilder = new FormBuilder();
$html = $formBuilder->build($map);

echo $html;
```

### 2. Create Order (建立物流訂單)

Create a logistics order (B2C/C2C).

```php
use CarlLee\NewebPayLogistics\Parameter\LgsType;
use CarlLee\NewebPayLogistics\Parameter\ShipType;
use CarlLee\NewebPayLogistics\Parameter\TradeType;

$create = $logistics->createOrder();
$create->setMerchantTradeNo('TRADE' . time());
$create->setLgsType(LgsType::B2C);
$create->setShipType(ShipType::SEVEN_ELEVEN);
$create->setTradeType(TradeType::PAYMENT);
$create->setReceiverName('Test Receiver');
$create->setReceiverPhone('0912345678');
$create->setReceiverCellPhone('0912345678');
// ... set other parameters

// $response = $logistics->send($create);
```

### 3. Query Order (查詢物流訂單)

Query the status of a logistics order.

```php
$query = $logistics->query();
$query->setLogisticsID('LOGISTICS_ID');
$query->setMerchantTradeNo('TRADE_NO');

// $response = $logistics->send($query);
```

### 4. Print Order (列印託運單)

Generate the interface for printing the shipping label.

```php
$print = $logistics->printOrder();
$print->setLogisticsID('LOGISTICS_ID');
$print->setMerchantTradeNo('TRADE_NO');

// $response = $logistics->send($print);
```

## Examples

Check the `examples/` directory for complete example scripts:

- [Map Operation](examples/example_map.php)
- [Create Order](examples/example_create_order.php)
- [Query Order](examples/example_query_order.php)
- [Print Order](examples/example_print_order.php)

## Testing

To run the unit tests:

```bash
vendor/bin/phpunit
```

## Development with Docker

This project supports development using Docker, which provides a consistent PHP 8.3 environment.

### Prerequisites

- Docker
- Docker Compose

### Usage

1. **Build the Docker image:**

   ```bash
   docker-compose build
   ```

2. **Run Composer commands:**

   ```bash
   docker-compose run --rm php composer install
   ```

3. **Run tests:**

   ```bash
   docker-compose run --rm php vendor/bin/phpunit
   ```

4. **Enter the container shell:**

   ```bash
   docker-compose run --rm php bash
   ```

## License

MIT
