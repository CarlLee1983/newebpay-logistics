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
     * Create a new instance.
     *
     * @param OperationFactoryInterface $factory
     * @param ClientInterface $client
     */
    public function __construct(
        protected readonly OperationFactoryInterface $factory = new OperationFactory(),
        protected readonly ClientInterface $client = new Client()
    ) {
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

    /**
     * Get a FormBuilder instance.
     *
     * @return FormBuilder
     */
    public function getFormBuilder(): FormBuilder
    {
        // Use the same server URL as the factory, but we need to extract it or assume default.
        // OperationFactory doesn't expose serverUrl publicly but it uses it to set on BaseRequest.
        // We can create a temporary request to get the base URL, or just default to the known one.
        // However, a better way is to check if we can get it from the factory.
        // Since OperationFactory stores it, but it's protected.
        // Let's rely on the BaseRequest knowing its URL.

        // Actually, we can just instantiate FormBuilder with default URL
        // or let the user configure it.
        // But to be consistent, we should try to use the configured URL.

        // A simple workaround: create a dummy request to get the base URL.
        $dummy = $this->factory->make('map');
        // The BaseRequest stores full URL: serverUrl . requestPath
        // requestPath for map is /map
        $fullUrl = $dummy->getUrl();
        $serverUrl = str_replace('/map', '', $fullUrl);

        return new FormBuilder($serverUrl);
    }

    /**
     * Generate HTML form for redirection (Auto Submit or Button).
     *
     * @param BaseRequest $request
     * @param bool $autoSubmit
     * @return string
     */
    public function generateForm(BaseRequest $request, bool $autoSubmit = true): string
    {
        $builder = $this->getFormBuilder();
        return $autoSubmit ? $builder->autoSubmit($request) : $builder->build($request);
    }
}
