<?php

namespace CarlLee\NewebPayLogistics\Tests;

use CarlLee\NewebPayLogistics\BaseRequest;
use CarlLee\NewebPayLogistics\Services\EncryptionService;
use CarlLee\NewebPayLogistics\Responses\Response;
use Mockery;

class BaseRequestTest extends TestCase
{
    public function testGetContent()
    {
        $encryptionService = Mockery::mock(EncryptionService::class);
        $encryptionService->shouldReceive('encrypt')->andReturn('ENCRYPTED_DATA');
        $encryptionService->shouldReceive('hash')->andReturn('HASH_DATA');

        $content = new class ('MERCHANT_ID', 'HASH_KEY', 'HASH_IV', $encryptionService) extends BaseRequest {
            protected function getRules(): array
            {
                return [];
            }

            public function getResponseClass(): string
            {
                return Response::class;
            }
        };
        $content->setMerchantTradeNo('TRADE123');

        $result = $content->getContent();

        $this->assertArrayHasKey('MerchantID_', $result);
        $this->assertArrayHasKey('PostData_', $result);
        $this->assertEquals('MERCHANT_ID', $result['MerchantID_']);
        $this->assertEquals('ENCRYPTED_DATA', $result['PostData_']);
    }
}
