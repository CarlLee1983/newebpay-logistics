<?php

require __DIR__ . '/../vendor/autoload.php';

use CarlLee\NewebPayLogistics\NewebPayLogistics;
use CarlLee\NewebPayLogistics\Parameter\LgsType;
use CarlLee\NewebPayLogistics\Parameter\ShipType;
use CarlLee\NewebPayLogistics\Parameter\TradeType;

// Initialize the library
$merchantId = getenv('NEWEBPAY_MERCHANT_ID') ?: 'MERCHANT_ID';
$hashKey = getenv('NEWEBPAY_HASH_KEY') ?: 'HASH_KEY';
$hashIV = getenv('NEWEBPAY_HASH_IV') ?: 'HASH_IV';

$logistics = NewebPayLogistics::create($merchantId, $hashKey, $hashIV);

// Create Order Operation
$create = $logistics->createOrder();

// Set parameters
$create->setMerchantTradeNo('TRADE' . time());
$create->setLgsType(LgsType::B2C);
$create->setShipType(ShipType::SEVEN_ELEVEN);
$create->setTradeType(TradeType::PAYMENT);
$create->setUserName('Test User');
$create->setReceiverName('Test Receiver');
$create->setUserTel('0212345678');
$create->setReceiverPhone('0912345678');
$create->setReceiverCellPhone('0912345678');
$create->setReceiverEmail('test@example.com');
$create->setStoreID('123456');
$create->setAmt(100);
$create->setTimeStamp(time());

// Send request
try {
    // In a real scenario, you would send the request
    // $response = $logistics->send($create);
    // echo "Response: " . $response->getMessage();
    
    // For demonstration, we just show the payload
    echo "Create Order Payload:\n";
    print_r($create->getPayload());
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
