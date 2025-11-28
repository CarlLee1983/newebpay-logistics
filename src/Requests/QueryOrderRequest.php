<?php

declare(strict_types=1);

namespace CarlLee\NewebPayLogistics\Requests;

use CarlLee\NewebPayLogistics\BaseRequest;
use CarlLee\NewebPayLogistics\Responses\QueryOrderResponse;

/**
 * Query Order Request
 */
class QueryOrderRequest extends BaseRequest
{
    /**
     * Request path.
     *
     * @var string
     */
    protected string $requestPath = '/query'; // TODO: Confirm endpoint

    /**
     * Get validation rules.
     *
     * @return array
     */
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
    public function getResponseClass(): string
    {
        return QueryOrderResponse::class;
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
