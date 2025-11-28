# NewebPay Logistics API Reference

Based on `NDNSv1.0.0.pdf` (Version 3.0, Document Version NDNSv1.0.0).

## 1. Introduction
NewebPay Logistics provides convenient logistics services for stores, including:
- **C2C (Store to Store)**: 7-ELEVEN, FamilyMart, Hi-Life, OK mart.
- **B2C (Bulk to Store)**: 7-ELEVEN.

## 2. Transaction Flow

### 4.1 B2C Bulk to Store (Payment on Delivery)
1. **Create Order**: Consumer selects "Payment on Delivery" and store. Store calls `createShipment` API.
2. **Get Code & Print**: Store calls `getShipmentNo` and `printLabel` APIs.
3. **Ship**: Store sends goods to logistics center.
4. **Distribute**: Logistics center distributes to pickup store.
5. **Pickup & Pay**: Consumer picks up and pays. NewebPay notifies store.

### 4.2 C2C Store to Store (Payment on Delivery)
1. **Create Order**: Consumer selects "Payment on Delivery" and store. Store calls `createShipment` API.
2. **Get Code & Print**: Store calls `getShipmentNo` and `printLabel` APIs. Store prints label (self or Kiosk).
3. **Ship**: Store sends goods to sending store.
4. **Distribute**: Sending store -> Logistics center -> Pickup store.
5. **Pickup & Pay**: Consumer picks up and pays. NewebPay notifies store.

*(Similar flows for Non-Payment on Delivery)*

## 3. API Specifications

### Common Mechanisms
- **Data Exchange**: HTTP POST (Form Post).
- **Encoding**: UTF-8.
- **Hash Data**:
    1. `EncryptData` = AES Encrypted Trade Data.
    2. String = `HashKey={HashKey}&{EncryptData}&HashIV={HashIV}`.
    3. `HashData` = `strtoupper(hash("sha256", String))`.

### 5.1 Store Map Query (NPA-B51)
Allows users to select pickup/sending stores.

**URL**:
- Test: `https://ccore.newebpay.com/API/Logistic/storeMap`
- Prod: `https://core.newebpay.com/API/Logistic/storeMap`

**Request Parameters**:
| Parameter | Name | Type | Required | Description |
| :--- | :--- | :--- | :--- | :--- |
| UID_ | Store ID | Varchar(15) | V | NWP Store ID |
| EncryptData_ | Encrypted Data | Text | V | AES Encrypted |
| HashData_ | Hash Data | Text | V | SHA256 Hash |
| Version_ | Version | Varchar(5) | V | Fixed: `1.0` |
| RespondType_ | Response Type | Varchar(6) | V | Fixed: `JSON` |

**EncryptData Content**:
| Parameter | Name | Type | Required | Description |
| :--- | :--- | :--- | :--- | :--- |
| MerchantOrderNo | Store Order No | Varchar(30) | V | Unique per store |
| LgsType | Logistics Type | Varchar(15) | V | `B2C` (Bulk), `C2C` (Store-to-Store) |
| ShipType | Logistics Provider | Varchar(15) | V | `1`: 7-11, `2`: Family, `3`: Hi-Life, `4`: OK |
| ReturnURL | Return URL | Varchar(50) | V | Callback URL after selection |
| TimeStamp | Timestamp | Text | V | Unix timestamp |
| ExtraData | Extra Data | Varchar(20) | | Returned as is |

### 5.2 Create Shipment (NPA-B52)
Creates a logistics order.

**URL**:
- Test: `https://ccore.newebpay.com/API/Logistic/createShipment`
- Prod: `https://core.newebpay.com/API/Logistic/createShipment`

**Request Parameters**:
(Same common parameters: `UID_`, `EncryptData_`, `HashData_`, `Version_`, `RespondType_`)

**EncryptData Content**:
| Parameter | Name | Type | Required | Description |
| :--- | :--- | :--- | :--- | :--- |
| MerchantOrderNo | Store Order No | Varchar(30) | V | Unique per store |
| TradeType | Trade Type | Int(1) | V | `1`: Payment, `3`: Non-payment |
| UserName | Receiver Name | Varchar(20) | V | |
| UserTel | Receiver Phone | Varchar(10) | V | |
| UserEmail | Receiver Email | Varchar(50) | V | |
| StoreID | Pickup Store ID | Varchar(10) | V | From Store Map |
| Amt | Amount | Int(10) | V | Transaction Amount |
| NotifyURL | Notify URL | Varchar(100) | | Notification on pickup |
| ItemDesc | Item Description | Varchar(100) | | |
| LgsType | Logistics Type | Varchar(3) | V | `B2C`, `C2C` |
| ShipType | Logistics Provider | Varchar(15) | V | `1`..`4` |
| TimeStamp | Timestamp | Varchar(50) | V | |

### 5.3 Get Shipment No (NPA-B53)
Get shipment number for printing.

**URL**:
- Test: `https://ccore.newebpay.com/API/Logistic/getShipmentNo`
- Prod: `https://core.newebpay.com/API/Logistic/getShipmentNo`

**EncryptData Content**:
| Parameter | Name | Type | Required | Description |
| :--- | :--- | :--- | :--- | :--- |
| MerchantOrderNo | Store Order No | Json_array | V | List of order numbers (Max 10) |
| TimeStamp | Timestamp | Varchar(50) | V | |

### 5.4 Print Label (NPA-B54)
Print the shipment label.

**URL**:
- Test: `https://ccore.newebpay.com/API/Logistic/printLabel`
- Prod: `https://core.newebpay.com/API/Logistic/printLabel`

**Request Parameters**:
(Same common parameters. **Must use Form Post**)

**EncryptData Content**:
| Parameter | Name | Type | Required | Description |
| :--- | :--- | :--- | :--- | :--- |
| MerchantOrderNo | Store Order No | Json_array | V | List of order numbers |
| TimeStamp | Timestamp | Varchar(50) | V | |

### 5.5 Query Shipment (NPA-B55)
Query shipment status.

**URL**:
- Test: `https://ccore.newebpay.com/API/Logistic/queryShipment`
- Prod: `https://core.newebpay.com/API/Logistic/queryShipment`

**EncryptData Content**:
| Parameter | Name | Type | Required | Description |
| :--- | :--- | :--- | :--- | :--- |
| MerchantOrderNo | Store Order No | Varchar(30) | V | |
| TimeStamp | Timestamp | Varchar(50) | V | |

### 5.6 Modify Shipment (NPA-B56)
Modify shipment details (before getting code/expired/re-select store).

**URL**:
- Test: `https://ccore.newebpay.com/API/Logistic/modifyShipment`
- Prod: `https://core.newebpay.com/API/Logistic/modifyShipment`

**EncryptData Content**:
| Parameter | Name | Type | Required | Description |
| :--- | :--- | :--- | :--- | :--- |
| MerchantOrderNo | Store Order No | Varchar(30) | V | |
| UserName | Receiver Name | Varchar(20) | | |
| UserTel | Receiver Phone | Varchar(10) | | |
| UserEmail | Receiver Email | Varchar(50) | | |
| StoreID | Pickup Store ID | Varchar(10) | | |
| TimeStamp | Timestamp | Varchar(50) | V | |

### 5.7 Shipment History (NPA-B57)
Query full shipment history.

**URL**:
- Test: `https://ccore.newebpay.com/API/Logistic/traceShipment`
- Prod: `https://core.newebpay.com/API/Logistic/traceShipment`

**EncryptData Content**:
| Parameter | Name | Type | Required | Description |
| :--- | :--- | :--- | :--- | :--- |
| MerchantOrderNo | Store Order No | Varchar(30) | V | |
| TimeStamp | Timestamp | Varchar(50) | V | |

### 5.8 Shipment Status Notification (NPA-B58)
Server-to-Server notification of status changes.

**Parameters**:
| Parameter | Name | Type | Description |
| :--- | :--- | :--- | :--- |
| Status | Status | Varchar(10) | `SUCCESS` or Error Code |
| MerchantID | Store ID | Varchar(15) | |
| MerchantOrderNo | Store Order No | Varchar(30) | |
| TradeNo | Trade No | Varchar(20) | |
| LgsNo | Shipment No | Varchar(20) | |
| Amt | Amount | Int(10) | |
| TradeType | Trade Type | Int(1) | |
| StoreID | Store ID | Varchar(10) | |
| StoreName | Store Name | Varchar(10) | |
| StoreAddr | Store Address | Varchar(100) | |
| StoreType | Store Type | Int(1) | |
| LgsType | Logistics Type | Varchar(15) | |
| ShipType | Logistics Provider | Varchar(15) | |
| LogisticsStatus | Logistics Status | Varchar(10) | Status Code |
| LogisticsStatusName | Status Name | Varchar(20) | Status Description |
| LogisticsTime | Status Time | Varchar(20) | |
| HashData | Hash Data | Text | Verification Hash |

## 4. Appendix

### Error Codes
(Refer to PDF for full list)
