<?php

namespace CarlLee\NewebPayLogistics\Tests;

use CarlLee\NewebPayLogistics\Responses\Response;

class ResponseTest extends TestCase
{
    public function testParseUrlEncoded()
    {
        $body = 'Status=SUCCESS&Message=Operation+Successful&RtnCode=1';
        $response = new Response($body);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals('Operation Successful', $response->getMessage());
        $this->assertEquals('1', $response->get('RtnCode'));
    }

    public function testParseJson()
    {
        $body = json_encode(['Status' => 'SUCCESS', 'Message' => 'Operation Successful']);
        $response = new Response($body);

        $this->assertTrue($response->isSuccess());
        $this->assertEquals('Operation Successful', $response->getMessage());
    }

    public function testErrorResponse()
    {
        $body = 'Status=FAIL&Message=Invalid+Parameter';
        $response = new Response($body);

        $this->assertFalse($response->isSuccess());
        $this->assertEquals('Invalid Parameter', $response->getErrorMessage());
    }

    public function testEmptyResponse()
    {
        $response = new Response('');
        $this->assertFalse($response->isSuccess());
    }
}
