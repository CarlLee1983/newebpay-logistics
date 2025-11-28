<?php

namespace CarlLee\NewebPayLogistics\Tests;

use CarlLee\NewebPayLogistics\Exceptions\NewebPayLogisticsException;
use CarlLee\NewebPayLogistics\Services\Validator;
use CarlLee\NewebPayLogistics\Parameter\LgsType;

class ValidatorTest extends TestCase
{
    protected Validator $validator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->validator = new Validator();
    }

    public function testValidateRequiredSuccess()
    {
        $data = ['field' => 'value'];
        $rules = ['field' => 'required'];

        $this->validator->validate($data, $rules);
        $this->assertTrue(true);
    }

    public function testValidateRequiredFailure()
    {
        $this->expectException(NewebPayLogisticsException::class);
        $this->expectExceptionMessage('Missing required field: field');

        $data = [];
        $rules = ['field' => 'required'];

        $this->validator->validate($data, $rules);
    }

    public function testValidateClassConstSuccess()
    {
        $data = ['type' => LgsType::B2C];
        $rules = ['type' => 'class_const:' . LgsType::class];

        $this->validator->validate($data, $rules);
        $this->assertTrue(true);
    }

    public function testValidateClassConstFailure()
    {
        $this->expectException(NewebPayLogisticsException::class);
        $this->expectExceptionMessage("Invalid value 'INVALID' for field type");

        $data = ['type' => 'INVALID'];
        $rules = ['type' => 'class_const:' . LgsType::class];

        $this->validator->validate($data, $rules);
    }

    public function testValidateMultipleRulesSuccess()
    {
        $data = ['type' => LgsType::B2C];
        $rules = ['type' => 'required|class_const:' . LgsType::class];

        $this->validator->validate($data, $rules);
        $this->assertTrue(true);
    }
}
