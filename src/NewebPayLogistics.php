<?php

declare(strict_types=1);

namespace CarlLee\NewebPayLogistics;

use CarlLee\NewebPayLogistics\Factories\OperationFactory;
use CarlLee\NewebPayLogistics\Factories\OperationFactoryInterface;
use CarlLee\NewebPayLogistics\Responses\Response;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

/**
 * NewebPay Logistics Client
 */
class NewebPayLogistics
{
    /**
     * Operation factory.
     *
     * @var OperationFactoryInterface
     */
    protected OperationFactoryInterface $factory;
    /**
     * HTTP Client.
     *
     * @var ClientInterface
     */
    protected ClientInterface $client;

    /**
     * Create a new instance.
     *
     * @param OperationFactoryInterface|null $factory
     * @param ClientInterface|null $client
     */
    public function __construct(OperationFactoryInterface $factory = null, ClientInterface $client = null)
    {
        $this->factory = $factory ?? new OperationFactory();
        $this->client = $client ?? new Client();
    }

    /**
     * Create a new instance with configuration.
     *
     * @param string $merchantId
     * @param string $hashKey
     * @param string $hashIV
     * @param string|null $serverUrl
     * @return self
     */
    public static function create(string $merchantId, string $hashKey, string $hashIV, string $serverUrl = null): self
    {
        $config = [
            'merchant_id' => $merchantId,
            'hash_key' => $hashKey,
            'hash_iv' => $hashIV,
        ];

        if ($serverUrl) {
            $config['server_url'] = $serverUrl;
        }

        return new self(new OperationFactory($config));
    }

    /**
     * Send a request.
     *
     * @param BaseRequest $request
     * @return Response
     * @throws \RuntimeException
     */
    public function send(BaseRequest $request): Response
    {
        $content = $request->getContent();

        $url = $request->getUrl();

        try {
            $response = $this->client->post($url, [
                'form_params' => $content,
            ]);

            $responseClass = $request->getResponseClass();
            return new $responseClass((string) $response->getBody());
        } catch (\Exception $e) {
            throw new \RuntimeException("API Request Failed: " . $e->getMessage(), 0, $e);
        }
    }

    /**
     * Create a Map request.
     *
     * @return BaseRequest
     */
    public function map(): BaseRequest
    {
        return $this->factory->make('map');
    }

    /**
     * Create a Create Order request.
     *
     * @return BaseRequest
     */
    public function createOrder(): BaseRequest
    {
        return $this->factory->make('create');
    }

    /**
     * Create a Query Order request.
     *
     * @return BaseRequest
     */
    public function query(): BaseRequest
    {
        return $this->factory->make('query');
    }

    /**
     * Create a Print Order request.
     *
     * @return BaseRequest
     */
    public function printOrder(): BaseRequest
    {
        return $this->factory->make('print');
    }

    /**
     * Get the operation factory.
     *
     * @return OperationFactoryInterface
     */
    public function getFactory(): OperationFactoryInterface
    {
        return $this->factory;
    }
}
