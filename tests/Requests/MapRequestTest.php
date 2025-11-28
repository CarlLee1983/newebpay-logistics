<?php

namespace CarlLee\NewebPayLogistics\Tests\Requests;

use CarlLee\NewebPayLogistics\Exceptions\NewebPayLogisticsException;
use CarlLee\NewebPayLogistics\Requests\MapRequest;
use CarlLee\NewebPayLogistics\Services\EncryptionService;
use CarlLee\NewebPayLogistics\Tests\TestCase;
use Mockery;
use CarlLee\NewebPayLogistics\Parameter\LgsType;
use CarlLee\NewebPayLogistics\Parameter\ShipType;

class MapRequestTest extends TestCase
{
    public function testValidationSuccess()
    {
        $encryptionService = Mockery::mock(EncryptionService::class);
        $encryptionService->shouldReceive('encrypt')->andReturn('ENCRYPTED');
        $encryptionService->shouldReceive('hash')->andReturn('HASH');

        $map = new MapRequest('MERCHANT_ID', 'HASH_KEY', 'HASH_IV', $encryptionService);
        $map->setMerchantTradeNo('TRADE123');
        $map->setLgsType(LgsType::B2C);
        $map->setShipType(ShipType::SEVEN_ELEVEN);
        $map->setReturnURL('https://example.com/return');
        $map->setTimeStamp(time());

        try {
            $map->getContent();
            $this->assertTrue(true);
        } catch (NewebPayLogisticsException $e) {
            $this->fail('Validation failed: ' . $e->getMessage());
        }
    }

    public function testValidationFailure()
    {
        $this->expectException(NewebPayLogisticsException::class);

        $encryptionService = Mockery::mock(EncryptionService::class);
        $map = new MapRequest('MERCHANT_ID', 'HASH_KEY', 'HASH_IV', $encryptionService);
        // Missing required fields

        $map->getContent();
    }
}
