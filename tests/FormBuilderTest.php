<?php

namespace CarlLee\NewebPayLogistics\Tests;

use CarlLee\NewebPayLogistics\BaseRequest;
use CarlLee\NewebPayLogistics\Responses\Response;
use CarlLee\NewebPayLogistics\FormBuilder;
use CarlLee\NewebPayLogistics\Services\EncryptionService;
use Mockery;

class FormBuilderTest extends TestCase
{
    public function testBuild()
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
        $content->setRequestPath('/api');

        $builder = new FormBuilder('https://example.com');
        $html = $builder->build($content);

        $this->assertStringContainsString('<form', $html);
        $this->assertStringContainsString('action="https://example.com/api"', $html);
        $this->assertStringContainsString('value="MERCHANT_ID"', $html);
        $this->assertStringContainsString('value="ENCRYPTED_DATA"', $html);
    }

    public function testGetFormData()
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
        $content->setRequestPath('/api');

        $builder = new FormBuilder('https://example.com');
        $data = $builder->getFormData($content);

        $this->assertEquals('https://example.com/api', $data['url']);
        $this->assertEquals('post', $data['method']);
        $this->assertIsArray($data['params']);
        $this->assertEquals('MERCHANT_ID', $data['params']['MerchantID_']);
        $this->assertEquals('ENCRYPTED_DATA', $data['params']['PostData_']);
    }

    public function testAutoSubmit()
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
        $content->setRequestPath('/api');

        $builder = new FormBuilder('https://example.com');
        $html = $builder->autoSubmit($content);

        $this->assertStringContainsString('<form', $html);
        $this->assertStringContainsString('action="https://example.com/api"', $html);
        $this->assertStringContainsString('style="display:none;"', $html);
        $this->assertStringContainsString('document.getElementById("newebpay-logistics-form").submit()', $html);
    }
}
