<?php

namespace CarlLee\NewebPayLogistics\Tests\Requests;

use CarlLee\NewebPayLogistics\Exceptions\NewebPayLogisticsException;
use CarlLee\NewebPayLogistics\Requests\PrintOrderRequest;
use CarlLee\NewebPayLogistics\Services\EncryptionService;
use CarlLee\NewebPayLogistics\Tests\TestCase;
use Mockery;

class PrintOrderRequestTest extends TestCase
{
    public function testValidationSuccess()
    {
        $encryptionService = Mockery::mock(EncryptionService::class);
        $encryptionService->shouldReceive('encrypt')->andReturn('ENCRYPTED');
        $encryptionService->shouldReceive('hash')->andReturn('HASH');

        $print = new PrintOrderRequest('MERCHANT_ID', 'HASH_KEY', 'HASH_IV', $encryptionService);
        $print->setMerchantTradeNo('TRADE123');
        $print->setLogisticsID('LOGISTICS123');
        $print->setTimeStamp(time());

        try {
            $print->getContent();
            $this->assertTrue(true);
        } catch (NewebPayLogisticsException $e) {
            $this->fail('Validation failed: ' . $e->getMessage());
        }
    }

    public function testValidationFailure()
    {
        $this->expectException(NewebPayLogisticsException::class);

        $encryptionService = Mockery::mock(EncryptionService::class);
        $print = new PrintOrderRequest('MERCHANT_ID', 'HASH_KEY', 'HASH_IV', $encryptionService);
        // Missing required fields

        $print->getContent();
    }
}
