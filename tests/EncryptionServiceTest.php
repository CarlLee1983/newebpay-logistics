<?php

namespace CarlLee\NewebPayLogistics\Tests;

use CarlLee\NewebPayLogistics\Services\EncryptionService;
use PHPUnit\Framework\TestCase;

class EncryptionServiceTest extends TestCase
{
    private $service;
    private $key = '12345678901234567890123456789012';
    private $iv = '12345678';

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new EncryptionService();
    }

    public function testEncrypt()
    {
        $data = ['Foo' => 'Bar', 'Baz' => 'Qux'];

        $encrypted = $this->service->encrypt($data, $this->key, $this->iv);

        // Expected value from previous manual test
        $this->assertEquals('0a35d9597c615676047e3aad0b50b84f', $encrypted);
    }

    public function testHash()
    {
        $encrypted = '0a35d9597c615676047e3aad0b50b84f';

        $hash = $this->service->hash($encrypted, $this->key, $this->iv);

        // Expected value using original IV (not padded)
        $this->assertEquals('359133D270CA4556B87079B9EF1D204A653494DBDC48A1F246FCB80AC2F651AC', $hash);
    }
}
