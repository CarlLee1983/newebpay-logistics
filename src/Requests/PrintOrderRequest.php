<?php

declare(strict_types=1);

namespace CarlLee\NewebPayLogistics\Requests;

use CarlLee\NewebPayLogistics\BaseRequest;
use CarlLee\NewebPayLogistics\Responses\PrintOrderResponse;

/**
 * Print Order Request
 */
class PrintOrderRequest extends BaseRequest
{
    /**
     * Request path.
     *
     * @var string
     */
    protected string $requestPath = '/print'; // TODO: Confirm endpoint

    /**
     * Get validation rules.
     *
     * @return array
     */
    #[\Override]
    protected function getRules(): array
    {
        return [
            'MerchantOrderNo' => 'required',
            'TimeStamp' => 'required',
        ];
    }

    /**
     * Get the response class name.
     *
     * @return string
     */
    #[\Override]
    public function getResponseClass(): string
    {
        return PrintOrderResponse::class;
    }

    /**
     * Set Logistics ID.
     *
     * @param string $id
     * @return static
     */
    public function setLogisticsID(string $id)
    {
        $this->content['LogisticsID'] = $id;
        return $this;
    }
}
