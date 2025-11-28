<?php

namespace CarlLee\NewebPayLogistics\Tests;

use CarlLee\NewebPayLogistics\NewebPayLogistics;
use CarlLee\NewebPayLogistics\Requests\CreateOrderRequest;
use CarlLee\NewebPayLogistics\Requests\MapRequest;
use CarlLee\NewebPayLogistics\Requests\PrintOrderRequest;
use CarlLee\NewebPayLogistics\Requests\QueryOrderRequest;
use CarlLee\NewebPayLogistics\Responses\Response;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response as GuzzleResponse;
use Mockery;

class NewebPayLogisticsTest extends TestCase
{
    public function testCreate()
    {
        $logistics = NewebPayLogistics::create('MERCHANT_ID', 'HASH_KEY', 'HASH_IV');
        $this->assertInstanceOf(NewebPayLogistics::class, $logistics);
    }

    public function testMap()
    {
        $logistics = NewebPayLogistics::create('MERCHANT_ID', 'HASH_KEY', 'HASH_IV');
        $map = $logistics->map();
        $this->assertInstanceOf(MapRequest::class, $map);
    }

    public function testCreateOrder()
    {
        $logistics = NewebPayLogistics::create('MERCHANT_ID', 'HASH_KEY', 'HASH_IV');
        $create = $logistics->createOrder();
        $this->assertInstanceOf(CreateOrderRequest::class, $create);
    }

    public function testQuery()
    {
        $logistics = NewebPayLogistics::create('MERCHANT_ID', 'HASH_KEY', 'HASH_IV');
        $query = $logistics->query();
        $this->assertInstanceOf(QueryOrderRequest::class, $query);
    }

    public function testPrintOrder()
    {
        $logistics = NewebPayLogistics::create('MERCHANT_ID', 'HASH_KEY', 'HASH_IV');
        $print = $logistics->printOrder();
        $this->assertInstanceOf(PrintOrderRequest::class, $print);
    }

    public function testSend()
    {
        $client = Mockery::mock(ClientInterface::class);
        $client->shouldReceive('post')
            ->once()
            ->andReturn(new GuzzleResponse(200, [], 'Status=SUCCESS&Message=Test'));

        $logistics = new NewebPayLogistics(null, $client);
        $logistics->getFactory()->setCredentials('MERCHANT_ID', 'HASH_KEY', 'HASH_IV');

        $request = Mockery::mock(MapRequest::class);
        $request->shouldReceive('getContent')->andReturn(['foo' => 'bar']);
        $request->shouldReceive('getUrl')->andReturn('https://example.com/api');
        $request->shouldReceive('getResponseClass')->andReturn(Response::class);

        $response = $logistics->send($request);

        $this->assertInstanceOf(Response::class, $response);
        $this->assertTrue($response->isSuccess());
        $this->assertEquals('Test', $response->getMessage());
    }
}
