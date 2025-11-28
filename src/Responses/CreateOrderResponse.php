<?php

declare(strict_types=1);

namespace CarlLee\NewebPayLogistics\Responses;

class CreateOrderResponse extends Response
{
    /**
     * Get Shipment Number (Logistics ID).
     *
     * @return string|null
     */
    public function getShipmentNo(): ?string
    {
        // Depending on API, it might be 'ShipmentNo' or 'LogisticsID' or inside 'Result'
        // Assuming standard NewebPay response structure where data is at top level or inside Result
        return $this->get('ShipmentNo') ?? $this->get('LogisticsID');
    }
}
