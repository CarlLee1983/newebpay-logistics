# NewebPay Logistics Integration PHP SDK

這是一個用於整合藍新金流物流 API 的 PHP SDK。

## 安裝

透過 Composer 安裝：

```bash
composer require carllee1983/newebpay-logistics
```

## 設定

使用您的商店代號 (Merchant ID)、Hash Key 和 Hash IV 初始化函式庫：

```php
use CarlLee\NewebPayLogistics\NewebPayLogistics;

$merchantId = 'YOUR_MERCHANT_ID';
$hashKey = 'YOUR_HASH_KEY';
$hashIV = 'YOUR_HASH_IV';

$logistics = NewebPayLogistics::create($merchantId, $hashKey, $hashIV);
```

## Laravel 整合

### 安裝

1. 發布設定檔：

```bash
php artisan vendor:publish --tag=newebpay-logistics-config
```

2. 在 `.env` 檔案中加入以下變數：

```env
NEWEBPAY_LOGISTICS_MERCHANT_ID=您的商店代號
NEWEBPAY_LOGISTICS_HASH_KEY=您的HashKey
NEWEBPAY_LOGISTICS_HASH_IV=您的HashIV
# 選填：覆寫伺服器網址 (預設為測試環境)
# NEWEBPAY_LOGISTICS_SERVER_URL=https://core.newebpay.com/API/Logistic
```

### 使用方法

您可以使用 `NewebPayLogistics` Facade：

```php
use NewebPayLogistics;

public function map()
{
    $map = NewebPayLogistics::map();
    // ...
}
```

## 使用方法

### 1. 電子地圖 (Map Interface)

產生 HTML 表單，將使用者導向至物流商的電子地圖介面以選擇門市。

```php
use CarlLee\NewebPayLogistics\FormBuilder;

use CarlLee\NewebPayLogistics\Parameter\LgsType;
use CarlLee\NewebPayLogistics\Parameter\ShipType;

$map = $logistics->map();
$map->setMerchantTradeNo('TRADE' . time());
$map->setLgsType(LgsType::B2C);
$map->setShipType(ShipType::SEVEN_ELEVEN);
$map->setIsCollection('N'); // N: 不取款, Y: 取款
$map->setServerReplyURL('https://example.com/reply');

$formBuilder = new FormBuilder();
$html = $formBuilder->build($map);

echo $html;
```

### 1-1. 前端整合 (Frontend Integration - Vue/React)

如果您使用前後端分離架構 (如 Vue, React)，您可以透過 API 回傳表單資料，讓前端自行送出表單。

```php
$data = $formBuilder->getFormData($map);

// 回傳 JSON 給前端
echo json_encode($data);

// 輸出範例：
// {
//     "url": "https://ccore.newebpay.com/API/Logistic/map",
//     "method": "post",
//     "params": {
//         "MerchantID_": "...",
//         "PostData_": "...",
//         "TradeSha": "..."
//     }
// }
```

### 1-2. 伺服器端轉跳 (Server-side Redirect)

由於藍新物流 API (如電子地圖) 需要使用 POST 傳送加密資料，因此無法使用一般的伺服器端 HTTP 302 轉跳 (`header("Location: ...")`)，因為這樣無法攜帶 POST 資料。

若您希望由伺服器端控制轉跳，請使用以下兩種方式之一：

1. **自動送出表單 (Auto Submit Form)**：
   使用 `$formBuilder->autoSubmit($map)` 產生包含 JavaScript 的 HTML，瀏覽器載入後會自動送出表單。

   ```php
   echo $formBuilder->autoSubmit($map);
   ```

2. **前端送出 (Frontend Submit)**：
   如上節所示，將資料回傳給前端，由前端透過 JavaScript 建立表單並送出。

### 2. 建立物流訂單 (Create Order)

建立物流訂單 (B2C/C2C)。

```php
use CarlLee\NewebPayLogistics\Parameter\LgsType;
use CarlLee\NewebPayLogistics\Parameter\ShipType;
use CarlLee\NewebPayLogistics\Parameter\TradeType;

$create = $logistics->createOrder();
$create->setMerchantTradeNo('TRADE' . time());
$create->setLgsType(LgsType::B2C);
$create->setShipType(ShipType::SEVEN_ELEVEN);
$create->setTradeType(TradeType::PAYMENT);
$create->setReceiverName('測試收件人');
$create->setReceiverPhone('0912345678');
$create->setReceiverCellPhone('0912345678');
// ... 設定其他參數

// $response = $logistics->send($create);
```

### 3. 查詢物流訂單 (Query Order)

查詢物流訂單狀態。

```php
$query = $logistics->query();
$query->setLogisticsID('LOGISTICS_ID');
$query->setMerchantTradeNo('TRADE_NO');

// $response = $logistics->send($query);
```

### 4. 列印託運單 (Print Order)

產生列印託運單的介面。

```php
$print = $logistics->printOrder();
$print->setLogisticsID('LOGISTICS_ID');
$print->setMerchantTradeNo('TRADE_NO');

// $response = $logistics->send($print);
```

## 範例

查看 `examples/` 目錄以獲取完整的範例腳本：

- [電子地圖](examples/example_map.php)
- [建立訂單](examples/example_create_order.php)
- [查詢訂單](examples/example_query_order.php)
- [列印託運單](examples/example_print_order.php)

## 測試

執行單元測試：

```bash
vendor/bin/phpunit
```

## 使用 Docker 進行開發

本專案支援使用 Docker 進行開發，提供一致的 PHP 8.3 環境。

### 前置需求

- Docker
- Docker Compose

### 使用方法

1. **建置 Docker 映像檔：**

   ```bash
   docker-compose build
   ```

2. **執行 Composer 指令：**

   ```bash
   docker-compose run --rm php composer install
   ```

3. **執行測試：**

   ```bash
   docker-compose run --rm php vendor/bin/phpunit
   ```

4. **進入容器 Shell：**

   ```bash
   docker-compose run --rm php bash
   ```

## 授權

MIT
