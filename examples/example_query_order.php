<?php

require __DIR__ . '/../vendor/autoload.php';

use CarlLee\NewebPayLogistics\NewebPayLogistics;

// Initialize the library
$merchantId = getenv('NEWEBPAY_MERCHANT_ID') ?: 'MERCHANT_ID';
$hashKey = getenv('NEWEBPAY_HASH_KEY') ?: 'HASH_KEY';
$hashIV = getenv('NEWEBPAY_HASH_IV') ?: 'HASH_IV';

$logistics = NewebPayLogistics::create($merchantId, $hashKey, $hashIV);

// Create Query Operation
$query = $logistics->query();

// Set parameters
$query->setLogisticsID('LOGISTICS_ID_12345'); // Replace with actual Logistics ID
$query->setMerchantTradeNo('TRADE_12345'); // Replace with actual Merchant Trade No
$query->setTimeStamp(time());

// Send request
try {
    // In a real scenario, you would send the request
    // $response = $logistics->send($query);
    // echo "Response: " . $response->getMessage();
    
    // For demonstration, we just show the payload
    echo "Query Order Payload:\n";
    print_r($query->getPayload());
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
