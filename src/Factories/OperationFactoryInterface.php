<?php

declare(strict_types=1);

namespace CarlLee\NewebPayLogistics\Factories;

use CarlLee\NewebPayLogistics\BaseRequest;

interface OperationFactoryInterface
{
    public function make(string $target, array $parameters = []): BaseRequest;
    public function setCredentials(string $merchantId, string $hashKey, string $hashIV): void;
}
