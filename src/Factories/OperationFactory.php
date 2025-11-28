<?php

declare(strict_types=1);

namespace CarlLee\NewebPayLogistics\Factories;

use CarlLee\NewebPayLogistics\BaseRequest;
use InvalidArgumentException;

class OperationFactory implements OperationFactoryInterface
{
    /**
     * Credentials.
     *
     * @var array
     */
    protected array $credentials = [
        'merchant_id' => '',
        'hash_key' => '',
        'hash_iv' => '',
    ];

    /**
     * HTTP client.
     *
     * @var ClientInterface
     */
    protected ClientInterface $client;

    /**
     * Server URL.
     *
     * @var string
     */
    protected string $serverUrl = 'https://ccore.newebpay.com/API/Logistic';

    /**
     * Default aliases.
     *
     * @var array
     */
    protected array $classMap = [
        // Map
        'map' => 'Requests\\MapRequest',
        // Create
        'create' => 'Requests\\CreateOrderRequest',
        // Query
        'query' => 'Requests\\QueryOrderRequest',
        // Print
        'print' => 'Requests\\PrintOrderRequest',
    ];

    /**
     * Create a new instance.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->credentials = [
            'merchant_id' => $config['merchant_id'] ?? '',
            'hash_key' => $config['hash_key'] ?? '',
            'hash_iv' => $config['hash_iv'] ?? '',
        ];
        $this->serverUrl = rtrim($config['server_url'] ?? 'https://ccore.newebpay.com/API/Logistic', '/');
    }

    /**
     * Make an operation instance.
     *
     * @param string $target
     * @param array $parameters
     * @return BaseRequest
     */
    public function make(string $target, array $parameters = []): BaseRequest
    {
        $class = $this->resolveClassName($target);

        if (!class_exists($class)) {
            throw new InvalidArgumentException("Class {$class} not found for target {$target}");
        }

        if (!is_subclass_of($class, BaseRequest::class)) {
            throw new InvalidArgumentException("{$class} must extend BaseRequest");
        }

        /** @var BaseRequest $instance */
        $instance = new $class(
            $this->credentials['merchant_id'],
            $this->credentials['hash_key'],
            $this->credentials['hash_iv']
        );

        $instance->setServerUrl($this->serverUrl);

        return $instance;
    }

    /**
     * Set credentials.
     *
     * @param string $merchantId
     * @param string $hashKey
     * @param string $hashIV
     * @return void
     */
    public function setCredentials(string $merchantId, string $hashKey, string $hashIV): void
    {
        $this->credentials = [
            'merchant_id' => $merchantId,
            'hash_key' => $hashKey,
            'hash_iv' => $hashIV,
        ];
    }

    /**
     * Resolve class name.
     *
     * @param string $target
     * @return string
     */
    protected function resolveClassName(string $target): string
    {
        if (isset($this->classMap[$target])) {
            return 'CarlLee\\NewebPayLogistics\\' . $this->classMap[$target];
        }

        return $target;
    }
}
