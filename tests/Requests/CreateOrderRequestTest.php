<?php

namespace CarlLee\NewebPayLogistics\Tests\Requests;

use CarlLee\NewebPayLogistics\Exceptions\NewebPayLogisticsException;
use CarlLee\NewebPayLogistics\Requests\CreateOrderRequest;
use CarlLee\NewebPayLogistics\Services\EncryptionService;
use CarlLee\NewebPayLogistics\Tests\TestCase;
use Mockery;
use CarlLee\NewebPayLogistics\Parameter\LgsType;
use CarlLee\NewebPayLogistics\Parameter\ShipType;
use CarlLee\NewebPayLogistics\Parameter\TradeType;

class CreateOrderRequestTest extends TestCase
{
    public function testValidationSuccess()
    {
        $encryptionService = Mockery::mock(EncryptionService::class);
        $encryptionService->shouldReceive('encrypt')->andReturn('ENCRYPTED');
        $encryptionService->shouldReceive('hash')->andReturn('HASH');

        $create = new CreateOrderRequest('MERCHANT_ID', 'HASH_KEY', 'HASH_IV', $encryptionService);
        $create->setMerchantTradeNo('TRADE123');
        $create->setLgsType(LgsType::B2C);
        $create->setShipType(ShipType::SEVEN_ELEVEN);
        $create->setReceiverName('Test Receiver');
        $create->setReceiverCellPhone('0912345678');
        $create->setReceiverEmail('test@example.com');

        // Add other required fields
        $create->setTradeType(TradeType::PAYMENT);
        $create->setUserName('Test User');
        $create->setUserTel('0212345678');
        $create->setStoreID('123456');
        $create->setAmt(100);
        $create->setTimeStamp(time());

        // Mocking validation logic if it's internal or testing the actual validation
        // Since validation is protected/internal called by getContent/send, we test if getContent throws exception

        try {
            $create->getContent();
            $this->assertTrue(true);
        } catch (NewebPayLogisticsException $e) {
            $this->fail('Validation failed: ' . $e->getMessage());
        }
    }

    public function testValidationFailure()
    {
        $this->expectException(NewebPayLogisticsException::class);

        $encryptionService = Mockery::mock(EncryptionService::class);
        $create = new CreateOrderRequest('MERCHANT_ID', 'HASH_KEY', 'HASH_IV', $encryptionService);
        // Missing required fields

        $create->getContent();
    }
}
