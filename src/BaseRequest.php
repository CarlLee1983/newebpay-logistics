<?php

declare(strict_types=1);

namespace CarlLee\NewebPayLogistics;

use CarlLee\NewebPayLogistics\Contracts\LogisticsInterface;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use CarlLee\NewebPayLogistics\Services\EncryptionService;
use CarlLee\NewebPayLogistics\Services\Validator;
use CarlLee\NewebPayLogistics\Parameter\Version;
use CarlLee\NewebPayLogistics\Parameter\RespondType;
use ReflectionClass;

/**
 * Base Request Class
 */
abstract class BaseRequest implements LogisticsInterface
{
    protected string $requestPath = '';
    protected string $merchantID = '';
    protected string $hashKey = '';
    protected string $hashIV = '';
    protected array $content = [];
    protected string $serverUrl = 'https://ccore.newebpay.com/API/Logistic'; // Default to test env
    protected LoggerInterface $logger;
    /**
     * Create a new instance.
     *
     * @param string $merchantId
     * @param string $hashKey
     * @param string $hashIV
     * @param EncryptionService $encryptionService
     */
    public function __construct(
        string $merchantId = '',
        string $hashKey = '',
        string $hashIV = '',
        protected EncryptionService $encryptionService = new EncryptionService()
    ) {
        $this->logger = new NullLogger();
        $this->setMerchantID($merchantId);
        $this->setHashKey($hashKey);
        $this->setHashIV($hashIV);
        $this->initContent();
    }

    /**
     * Initialize content.
     *
     * @return void
     */
    protected function initContent(): void
    {
        $this->content = [
            'MerchantID' => $this->merchantID,
        ];
    }

    /**
     * Set Merchant ID.
     *
     * @param string $id
     * @return static
     */
    public function setMerchantID(string $id)
    {
        $this->merchantID = $id;
        $this->content['MerchantID'] = $id;
        return $this;
    }

    /**
     * Set Merchant Trade No.
     *
     * @param string $tradeNo
     * @return static
     */
    public function setMerchantTradeNo(string $tradeNo)
    {
        $this->content['MerchantOrderNo'] = $tradeNo;
        return $this;
    }

    /**
     * Set Hash Key.
     *
     * @param string $key
     * @return static
     */
    public function setHashKey(string $key)
    {
        $this->hashKey = $key;
        return $this;
    }

    /**
     * Set Hash IV.
     *
     * @param string $iv
     * @return static
     */
    public function setHashIV(string $iv)
    {
        $this->hashIV = $iv;
        return $this;
    }

    /**
     * Set TimeStamp.
     *
     * @param int|string $timeStamp
     * @return static
     */
    public function setTimeStamp($timeStamp)
    {
        $this->content['TimeStamp'] = $timeStamp;
        return $this;
    }

    /**
     * Set Server URL.
     *
     * @param string $url
     * @return static
     */
    public function setServerUrl(string $url)
    {
        $this->serverUrl = rtrim($url, '/');
        return $this;
    }

    /**
     * Get Request Path.
     *
     * @return string
     */
    public function getRequestPath(): string
    {
        return $this->requestPath;
    }

    /**
     * Get full URL.
     *
     * @return string
     */
    public function getUrl(): string
    {
        return $this->serverUrl . $this->requestPath;
    }

    /**
     * Set Request Path.
     *
     * @param string $path
     * @return static
     */
    public function setRequestPath(string $path)
    {
        $this->requestPath = $path;
        return $this;
    }

    /**
     * Get Payload.
     *
     * @return array
     */
    public function getPayload(): array
    {
        $this->validation();
        $this->content['MerchantID'] = $this->merchantID;
        return $this->content;
    }

    /**
     * Get validation rules.
     *
     * @return array
     */
    abstract protected function getRules(): array;

    /**
     * Validate parameters.
     *
     * @return void
     */
    protected function validation(): void
    {
        $validator = new Validator();
        $validator->validate($this->content, $this->getRules());
    }


    /**
     * This method should be implemented to encrypt/sign the payload.
     * NewebPay usually uses AES encryption for TradeInfo and SHA256 for TradeSha.
     *
     * @return array
     */
    public function getContent(): array
    {
        $payload = $this->getPayload();

        $encryptedData = $this->encryptionService->encrypt($payload, $this->hashKey, $this->hashIV);
        $hashData = $this->encryptionService->hash($encryptedData, $this->hashKey, $this->hashIV);

        return [
            'MerchantID_' => $this->merchantID,
            'PostData_' => $encryptedData,
            'UID_' => $this->merchantID,
            'EncryptData_' => $encryptedData,
            'HashData_' => $hashData,
            'Version_' => Version::V_1_0->value,
            'RespondType_' => RespondType::JSON->value,
        ];
    }

    /**
     * Get the response class name.
     *
     * @return string
     */
    abstract public function getResponseClass(): string;
}
