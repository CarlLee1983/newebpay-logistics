<?php

/**
 * Laravel Integration Example
 *
 * This file demonstrates how to use the NewebPay Logistics SDK within a Laravel application.
 * Note: This code is for demonstration and should be used inside a Laravel Controller or Service.
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use CarlLee\NewebPayLogistics\Laravel\Facades\NewebPayLogistics;
use CarlLee\NewebPayLogistics\Parameter\LgsType;
use CarlLee\NewebPayLogistics\Parameter\ShipType;
use CarlLee\NewebPayLogistics\Parameter\TradeType;

class LogisticsController extends Controller
{
    /**
     * Display the logistics map for store selection.
     */
    public function map()
    {
        // Use the Facade to create a Map request
        $map = NewebPayLogistics::map();

        // Configure parameters
        $map->setMerchantTradeNo('TRADE' . time());
        $map->setLgsType(LgsType::B2C);
        $map->setShipType(ShipType::SEVEN_ELEVEN);
        $map->setIsCollection('N'); // N: No collection, Y: Collection payment
        $map->setServerReplyURL(route('logistics.reply')); // Use Laravel helper for URL

        // Generate the auto-submit form HTML
        return NewebPayLogistics::generateForm($map);
    }

    /**
     * Create a logistics order.
     */
    public function createOrder()
    {
        $create = NewebPayLogistics::createOrder();

        $create->setMerchantTradeNo('TRADE' . time());
        $create->setLgsType(LgsType::B2C);
        $create->setShipType(ShipType::FAMILY);
        $create->setTradeType(TradeType::PAYMENT); // Payment required
        $create->setReceiverName('John Doe');
        $create->setReceiverCellPhone('0912345678');
        // ... set other required fields

        // Generate form to submit to NewebPay
        return NewebPayLogistics::generateForm($create);
    }

    /**
     * Handle the callback from NewebPay (ServerReplyURL).
     */
    public function callback(Request $request)
    {
        // In a real application, you would decrypt the data here.
        // The SDK provides helper classes for this, or you can key off the returned data.
        
        $data = $request->all();
        
        // Process logic...
        
        return response('OK');
    }
}
