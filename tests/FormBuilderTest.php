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
}
