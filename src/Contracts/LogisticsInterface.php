<?php

declare(strict_types=1);

namespace CarlLee\NewebPayLogistics\Contracts;

/**
 * Logistics Interface
 */
interface LogisticsInterface
{
    /**
     * Set Merchant ID
     *
     * @param string $merchantId
     * @return static
     */
    public function setMerchantID(string $merchantId);

    /**
     * Set Merchant Trade No
     *
     * @param string $tradeNo
     * @return static
     */
    public function setMerchantTradeNo(string $tradeNo);

    /**
     * Set HashKey
     *
     * @param string $hashKey
     * @return static
     */
    public function setHashKey(string $hashKey);

    /**
     * Set HashIV
     *
     * @param string $hashIV
     * @return static
     */
    public function setHashIV(string $hashIV);

    /**
     * Get API Request Path
     *
     * @return string
     */
    public function getRequestPath(): string;

    /**
     * Get Payload (Raw parameters)
     *
     * @return array<string, mixed>
     */
    public function getPayload(): array;

    /**
     * Get Content (Encrypted/Signed payload ready for sending)
     *
     * @return array<string, mixed>
     */
    public function getContent(): array;
}
