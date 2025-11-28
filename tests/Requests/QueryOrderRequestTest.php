<?php

namespace CarlLee\NewebPayLogistics\Tests\Requests;

use CarlLee\NewebPayLogistics\Exceptions\NewebPayLogisticsException;
use CarlLee\NewebPayLogistics\Requests\QueryOrderRequest;
use CarlLee\NewebPayLogistics\Services\EncryptionService;
use CarlLee\NewebPayLogistics\Tests\TestCase;
use Mockery;

class QueryOrderRequestTest extends TestCase
{
    public function testValidationSuccess()
    {
        $encryptionService = Mockery::mock(EncryptionService::class);
        $encryptionService->shouldReceive('encrypt')->andReturn('ENCRYPTED');
        $encryptionService->shouldReceive('hash')->andReturn('HASH');

        $query = new QueryOrderRequest('MERCHANT_ID', 'HASH_KEY', 'HASH_IV', $encryptionService);
        $query->setMerchantTradeNo('TRADE123');
        $query->setLogisticsID('LOGISTICS123');
        $query->setTimeStamp(time());

        try {
            $query->getContent();
            $this->assertTrue(true);
        } catch (NewebPayLogisticsException $e) {
            $this->fail('Validation failed: ' . $e->getMessage());
        }
    }

    public function testValidationFailure()
    {
        $this->expectException(NewebPayLogisticsException::class);

        $encryptionService = Mockery::mock(EncryptionService::class);
        $query = new QueryOrderRequest('MERCHANT_ID', 'HASH_KEY', 'HASH_IV', $encryptionService);
        // Missing required fields

        $query->getContent();
    }
}
