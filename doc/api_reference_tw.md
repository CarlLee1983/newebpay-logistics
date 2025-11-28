# NewebPay Logistics API Reference (繁體中文)

本文件基於 `NDNSv1.0.0.pdf` (程式版本號：3.0, 文件版本號：NDNSv1.0.0)。

## 1. 簡介
藍新金流因應商店出貨需求，提供超商便捷的物流服務。
目前提供物流服務如下：
- **店到店 (C2C) 服務**：7-ELEVEN、全家、萊爾富、OK mart
- **大宗寄倉 (B2C) 服務**：7-ELEVEN

## 2. 交易流程

### 4.1 B2C 大宗寄倉取貨付款
1. **建立訂單**：消費者選擇取貨付款並指定門市。商店呼叫﹝建立物流寄貨單 API﹞建立物流訂單。
2. **取得寄件代碼並列印寄貨單**：商店呼叫﹝取得寄件代碼 API﹞及﹝列印寄貨單 API﹞。
3. **商店出貨配送**：商店自行將貨品送至物流中心統倉進行驗收。
4. **物流出貨配送**：物流中心驗收貨品並進行後續配發至取件門市。
5. **消費者取貨付款**：消費者至超商門市取貨並付款完成，藍新即時通知商店。

### 4.2 C2C 店到店取貨付款
1. **建立訂單**：消費者選擇取貨付款並指定門市。商店呼叫﹝建立物流寄貨單 API﹞建立物流訂單。
2. **取得寄件代碼並列印寄貨單**：商店呼叫﹝取得寄件代碼 API﹞及﹝列印寄貨單 API﹞。商店可自行列印或至超商 Kiosk 機台列印。
3. **商店出貨配送**：商店自行將貨品送至寄件門市進行交寄。
4. **寄件出貨配送**：寄件門市收件後開始配送至物流中心驗收貨品並配發至各取貨門市。
5. **消費者取貨付款**：消費者至超商門市取貨並付款完成，藍新即時通知商店。

*(取貨不付款流程類似，僅最後步驟為消費者取貨完成)*

## 3. 各項作業 API 說明流程

### 共通機制
- **資料交換方式**：HTTP POST (Form Post)。
- **編碼格式**：UTF-8。
- **Hash Data 處理方式**：
    1. `EncryptData` = 交易資料 AES 加密後字串。
    2. 串聯字串 = `HashKey={HashKey}&{EncryptData}&HashIV={HashIV}`。
    3. `HashData` = `strtoupper(hash("sha256", 串聯字串))`.

### 5.1 門市地圖查詢 API﹝NPA-B51﹞
呼叫此門市地圖查詢 API 可供使用者查詢取貨門市、寄貨門市。

**串接網址**：
- 測試環境：`https://ccore.newebpay.com/API/Logistic/storeMap`
- 正式環境：`https://core.newebpay.com/API/Logistic/storeMap`

**Post 參數說明**：
| 參數名稱 | 參數中文名稱 | 型態 | 必填 | 備註 |
| :--- | :--- | :--- | :--- | :--- |
| UID_ | 商店代號 | Varchar(15) | V | 此為 NWP 商店代號 |
| EncryptData_ | 加密資料 | Text | V | |
| HashData_ | 雜湊資料 | Text | V | |
| Version_ | 串接程式版本 | Varchar(5) | V | 固定帶 `1.0` |
| RespondType_ | 回傳格式 | Varchar(6) | V | 請帶 `JSON` |

**EncryptData 內含參數說明**：
| 參數名稱 | 參數中文名稱 | 型態 | 必填 | 備註 |
| :--- | :--- | :--- | :--- | :--- |
| MerchantOrderNo | 商店訂單編號 | Varchar(30) | V | 商店自訂，不可重覆 |
| LgsType | 物流型態 | Varchar(15) | V | `B2C` (大宗寄倉), `C2C` (店到店) |
| ShipType | 物流廠商類別 | Varchar(15) | V | `1`: 7-11, `2`: 全家, `3`: 萊爾富, `4`: OK |
| ReturnURL | 返回商店網址 | Varchar(50) | V | 選取門市後返回的網址 |
| TimeStamp | 時間戳記 | Text | V | Unix 時間戳記 |
| ExtraData | 額外資料 | Varchar(20) | | 原值回傳 |

### 5.2 建立物流寄貨單 API﹝NPA-B52﹞
當廠商金流訂單成立，呼叫此 API 建立物流寄貨單。

**串接網址**：
- 測試環境：`https://ccore.newebpay.com/API/Logistic/createShipment`
- 正式環境：`https://core.newebpay.com/API/Logistic/createShipment`

**Post 參數說明**：
(同共通參數：`UID_`, `EncryptData_`, `HashData_`, `Version_`, `RespondType_`)

**EncryptData 內含參數說明**：
| 參數名稱 | 參數中文名稱 | 型態 | 必填 | 備註 |
| :--- | :--- | :--- | :--- | :--- |
| MerchantOrderNo | 商店訂單編號 | Varchar(30) | V | |
| TradeType | 取貨類別 | Int(1) | V | `1`: 取貨付款, `3`: 取貨不付款 |
| UserName | 取件人姓名 | Varchar(20) | V | |
| UserTel | 取件人手機號碼 | Varchar(10) | V | |
| UserEmail | 取件人電子信箱 | Varchar(50) | V | |
| StoreID | 取件門市編號 | Varchar(10) | V | 門市地圖回傳的門市編號 |
| Amt | 交易金額 | Int(10) | V | |
| NotifyURL | 取貨完成通知網址 | Varchar(100) | | |
| ItemDesc | 產品名稱說明 | Varchar(100) | | |
| LgsType | 物流型態 | Varchar(3) | V | `B2C`, `C2C` |
| ShipType | 物流廠商類別 | Varchar(15) | V | `1`..`4` |
| TimeStamp | 時間戳記 | Varchar(50) | V | |

### 5.3 取得寄件代碼 API﹝NPA-B53﹞
未出貨前呼叫此 API 進行寄貨單號的索取。

**串接網址**：
- 測試環境：`https://ccore.newebpay.com/API/Logistic/getShipmentNo`
- 正式環境：`https://core.newebpay.com/API/Logistic/getShipmentNo`

**EncryptData 內含參數說明**：
| 參數名稱 | 參數中文名稱 | 型態 | 必填 | 備註 |
| :--- | :--- | :--- | :--- | :--- |
| MerchantOrderNo | 商店訂單編號 | Json_array | V | 一次最多十筆 |
| TimeStamp | 時間戳記 | Varchar(50) | V | |

### 5.4 列印寄貨單 API﹝NPA-B54﹞
商店透過此 API 列印寄貨單並黏貼在商品上進行出貨。

**串接網址**：
- 測試環境：`https://ccore.newebpay.com/API/Logistic/printLabel`
- 正式環境：`https://core.newebpay.com/API/Logistic/printLabel`

**Post 參數說明**：
(同共通參數。**限使用 Form Post**)

**EncryptData 內含參數說明**：
| 參數名稱 | 參數中文名稱 | 型態 | 必填 | 備註 |
| :--- | :--- | :--- | :--- | :--- |
| MerchantOrderNo | 商店訂單編號 | Json_array | V | |
| TimeStamp | 時間戳記 | Varchar(50) | V | |

### 5.5 查詢物流寄貨單 API﹝NPA-B55﹞
查詢物流寄貨單的訂單相關資訊。

**串接網址**：
- 測試環境：`https://ccore.newebpay.com/API/Logistic/queryShipment`
- 正式環境：`https://core.newebpay.com/API/Logistic/queryShipment`

**EncryptData 內含參數說明**：
| 參數名稱 | 參數中文名稱 | 型態 | 必填 | 備註 |
| :--- | :--- | :--- | :--- | :--- |
| MerchantOrderNo | 商店訂單編號 | Varchar(30) | V | |
| TimeStamp | 時間戳記 | Varchar(50) | V | |

### 5.6 修改物流寄貨單 API﹝NPA-B56﹞
未取號前、寄件逾期、重選門市的寄貨單皆可透過此 API 進行異動。

**串接網址**：
- 測試環境：`https://ccore.newebpay.com/API/Logistic/modifyShipment`
- 正式環境：`https://core.newebpay.com/API/Logistic/modifyShipment`

**EncryptData 內含參數說明**：
| 參數名稱 | 參數中文名稱 | 型態 | 必填 | 備註 |
| :--- | :--- | :--- | :--- | :--- |
| MerchantOrderNo | 商店訂單編號 | Varchar(30) | V | |
| UserName | 取件人姓名 | Varchar(20) | | |
| UserTel | 取件人手機號碼 | Varchar(10) | | |
| UserEmail | 取件人電子信箱 | Varchar(50) | | |
| StoreID | 取件門市編號 | Varchar(10) | | |
| TimeStamp | 時間戳記 | Varchar(50) | V | |

### 5.7 貨態歷程追蹤 API﹝NPA-B57﹞
呼叫此 API 可針對訂單物流歷程資訊進行完整查詢。

**串接網址**：
- 測試環境：`https://ccore.newebpay.com/API/Logistic/traceShipment`
- 正式環境：`https://core.newebpay.com/API/Logistic/traceShipment`

**EncryptData 內含參數說明**：
| 參數名稱 | 參數中文名稱 | 型態 | 必填 | 備註 |
| :--- | :--- | :--- | :--- | :--- |
| MerchantOrderNo | 商店訂單編號 | Varchar(30) | V | |
| TimeStamp | 時間戳記 | Varchar(50) | V | |

### 5.8 物流貨態更新即時通知﹝NPA-B58﹞
提供藍新科技通知網址，可直接針對物流系統進行貨態更新的通知。

**參數說明**：
| 參數名稱 | 參數中文名稱 | 型態 | 備註 |
| :--- | :--- | :--- | :--- |
| Status | 回傳狀態 | Varchar(10) | `SUCCESS` 或錯誤代碼 |
| MerchantID | 商店代號 | Varchar(15) | |
| MerchantOrderNo | 商店訂單編號 | Varchar(30) | |
| TradeNo | 藍新金流交易序號 | Varchar(20) | |
| LgsNo | 寄件代碼 | Varchar(20) | |
| Amt | 交易金額 | Int(10) | |
| TradeType | 取貨類別 | Int(1) | |
| StoreID | 取件門市編號 | Varchar(10) | |
| StoreName | 超商門市名稱 | Varchar(10) | |
| StoreAddr | 超商門市地址 | Varchar(100) | |
| StoreType | 門市通路型態 | Int(1) | |
| LgsType | 物流型態 | Varchar(15) | |
| ShipType | 物流廠商類別 | Varchar(15) | |
| LogisticsStatus | 物流狀態代號 | Varchar(10) | |
| LogisticsStatusName | 物流狀態說明 | Varchar(20) | |
| LogisticsTime | 物流狀態時間 | Varchar(20) | |
| HashData | 雜湊資料 | Text | |

## 4. 附錄

### 錯誤代碼
(請參閱 PDF 完整列表)
