<?php

require __DIR__ . '/../vendor/autoload.php';

use CarlLee\NewebPayLogistics\NewebPayLogistics;
use CarlLee\NewebPayLogistics\FormBuilder;
use CarlLee\NewebPayLogistics\Parameter\LgsType;
use CarlLee\NewebPayLogistics\Parameter\ShipType;

// Initialize the library
// Replace with your actual Merchant ID, Hash Key, and Hash IV
$merchantId = getenv('NEWEBPAY_MERCHANT_ID') ?: 'MERCHANT_ID';
$hashKey = getenv('NEWEBPAY_HASH_KEY') ?: 'HASH_KEY';
$hashIV = getenv('NEWEBPAY_HASH_IV') ?: 'HASH_IV';

$logistics = NewebPayLogistics::create($merchantId, $hashKey, $hashIV);

// Create Map Operation
$map = $logistics->map();

// Set parameters
$map->setMerchantTradeNo('TRADE' . time()); // Unique trade number
$map->setLgsType(LgsType::B2C);
$map->setShipType(ShipType::SEVEN_ELEVEN);
$map->setIsCollection('N'); // N: No collection, Y: Collection
$map->setServerReplyURL('https://example.com/reply'); // URL for callback
$map->setReturnURL('https://example.com/return'); // URL for return
$map->setTimeStamp(time());
$map->setExtraData('custom_data'); // Optional extra data

// Generate HTML Form
$formBuilder = new FormBuilder();
$html = $formBuilder->build($map);

echo "<!-- Map Operation Form -->\n";
echo $html . "\n";
