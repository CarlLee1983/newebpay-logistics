# NewebPay Logistics Integration PHP SDK

[繁體中文](README_TW.md) | [English](README.md)

[![Latest Version on Packagist](https://img.shields.io/packagist/v/carllee1983/newebpay-logistics.svg)](https://packagist.org/packages/carllee1983/newebpay-logistics)
[![Run Tests](https://github.com/CarlLee1983/newebpay-logistics/actions/workflows/run-tests.yml/badge.svg)](https://github.com/CarlLee1983/newebpay-logistics/actions/workflows/run-tests.yml)
[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE.md)
[![PHP Version](https://img.shields.io/badge/php-%5E8.1-blue)](https://packagist.org/packages/carllee1983/newebpay-logistics)

整合藍新金流物流 API (NewebPay Logistics API) 的 PHP SDK。此套件簡化了建立物流訂單、電子地圖選店、訂單查詢及託運單列印的流程。

## 特色

- **易於整合**：提供簡單直覺的 API 來處理常見的物流操作。
- **框架無關**：適用於任何 PHP 專案（內建 Laravel 支援）。
- **型別安全**：利用 PHP 封裝確保資料正確性。
- **前端友善**：內建產生 HTML 表單功能，支援前端框架（如 Vue, React）。

## 安裝

透過 Composer 安裝：

```bash
composer require carllee1983/newebpay-logistics
```

## 設定

使用您的商店代號 (Merchant ID)、Hash Key 和 Hash IV 初始化函式庫。

```php
use CarlLee\NewebPayLogistics\NewebPayLogistics;

$merchantId = 'YOUR_MERCHANT_ID';
$hashKey = 'YOUR_HASH_KEY';
$hashIV = 'YOUR_HASH_IV';

// 最後一個參數可選，用於覆寫伺服器網址 (預設為測試環境)
$logistics = NewebPayLogistics::create($merchantId, $hashKey, $hashIV);
```

## Laravel 整合

本套件包含 Service Provider 和 Facade，可與 Laravel 無縫整合。

### 1. 安裝

發布設定檔：

```bash
php artisan vendor:publish --tag=newebpay-logistics-config
```

### 2. 設定

在 `.env` 檔案中加入以下變數：

```env
NEWEBPAY_LOGISTICS_MERCHANT_ID=您的商店代號
NEWEBPAY_LOGISTICS_HASH_KEY=您的HashKey
NEWEBPAY_LOGISTICS_HASH_IV=您的HashIV
# 選填：覆寫伺服器網址 (預設為測試環境)
# NEWEBPAY_LOGISTICS_SERVER_URL=https://core.newebpay.com/API/Logistic
```

### 3. 使用 Facade

您可以在 Laravel 應用程式的任何地方使用 `NewebPayLogistics` Facade。

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

## 使用方法

### 1. 電子地圖 (Map Interface)

產生 HTML 表單，將使用者導向至物流商的電子地圖介面以選擇門市（如 7-11, 全家）。

```php
use CarlLee\NewebPayLogistics\Parameter\LgsType;
use CarlLee\NewebPayLogistics\Parameter\ShipType;

$map = $logistics->map();
$map->setMerchantTradeNo('TRADE' . time());
$map->setLgsType(LgsType::B2C);
$map->setShipType(ShipType::SEVEN_ELEVEN);
$map->setIsCollection('N'); // N: 不取款, Y: 取款
$map->setServerReplyURL('https://example.com/reply');

// 產生自動送出 HTML 表單
echo $logistics->generateForm($map);
```

#### 1-1. 前端整合 (Vue/React)

如果您使用前後端分離架構，請將表單資料以 JSON 格式回傳給前端。

```php
$formBuilder = $logistics->getFormBuilder();
$data = $formBuilder->getFormData($map);

echo json_encode($data);
// 輸出範例： {"url": "...", "method": "post", "params": { ... }}
```

### 2. 建立物流訂單 (Create Order)

建立 B2C 或 C2C 的物流訂單。

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
$create->setReceiverCellPhone('0912345678');
// ... 設定其他參數

// 此 API 通常會回應轉導或直接結果，視具體參數與流程而定
// 嚴格來說，部分建立訂單流程也是表單 Post 轉導
// 請參閱官方文件確認具體流程
```

### 3. 查詢物流訂單 (Query Order)

查詢既有訂單的狀態。

```php
$query = $logistics->query();
$query->setLogisticsID('LOGISTICS_ID');
$query->setMerchantTradeNo('TRADE_NO');

// 這是直接的 API 呼叫
$response = $logistics->send($query);

if ($response->isSuccess()) {
    echo "Status: " . $response->getMessage();
}
```

## 範例

查看 `examples/` 目錄以獲取完整的純 PHP 範例腳本：

- [電子地圖 (Map Operation)](examples/example_map.php)
- [建立訂單 (Create Order)](examples/example_create_order.php)
- [查詢訂單 (Query Order)](examples/example_query_order.php)
- [列印託運單 (Print Order)](examples/example_print_order.php)
- [Laravel 整合範例 (Laravel Integration)](examples/laravel_example.php)

## API 參考文件

詳細的 API 說明文件請參考 `doc/` 目錄下的檔案：

- [英文 API 參考文件](doc/api_reference_en.md)
- [繁體中文 API 參考文件](doc/api_reference_tw.md)

## 常見問題 (FAQ)

**Q: 如何切換到正式環境？**
A: `NewebPayLogistics::create()` 預設使用測試環境網址。請傳入第四個參數作為正式環境網址，或是在 Laravel `.env` 中設定 `NEWEBPAY_LOGISTICS_SERVER_URL`。

**Q: 只有 Laravel 可以用嗎？**
A: 不是！這個套件是框架無關的 (Framework Agnostic)。請參考 [設定](#設定) 章節。

**Q: 我遇到 "Validation Validation" 錯誤。**
A: 請確認所有必填欄位都已設定。SDK 會在產生 Payload 前驗證請求參數。

**Q: 我遇到 "Check Value Error" 或 解密失敗。**
A: 這通常代表您的 Merchant ID、Hash Key 或 Hash IV 不正確。請重新檢查它們。同時請確保金鑰中沒有多餘的空白。在某些情況下，請確保輸入資料不包含可能影響長度計算的編碼字元。

## 開發

```bash
# 執行測試
composer test

# 檢查程式碼風格
composer check

# 修復程式碼風格
composer format
```

## 貢獻

請參閱 [CONTRIBUTING.md](CONTRIBUTING.md) 瞭解詳情。

## 安全性

如果您發現任何安全性相關的問題，請發送電子郵件至 carllee1983@gmail.com，請勿使用 Issue Tracker。

## 授權

MIT
