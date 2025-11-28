<?php

declare(strict_types=1);

namespace CarlLee\NewebPayLogistics\Requests;

use CarlLee\NewebPayLogistics\BaseRequest;
use CarlLee\NewebPayLogistics\Parameter\LgsType;
use CarlLee\NewebPayLogistics\Parameter\ShipType;
use CarlLee\NewebPayLogistics\Responses\Response;

/**
 * Map Request
 */
class MapRequest extends BaseRequest
{
    /**
     * Request path.
     *
     * @var string
     */
    protected string $requestPath = '/map';

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
            'LgsType' => 'required|enum:' . LgsType::class,
            'ShipType' => 'required|enum:' . ShipType::class,
            'ReturnURL' => 'required',
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
        return Response::class;
    }

    /**
     * Set Logistics Sub Type.
     *
     * @param string $subType
     * @return static
     */
    public function setLogisticsSubType(string $subType)
    {
        $this->content['LogisticsSubType'] = $subType;
        return $this;
    }

    /**
     * Set Is Collection.
     *
     * @param string $isCollection
     * @return static
     */
    public function setIsCollection(string $isCollection)
    {
        $this->content['IsCollection'] = $isCollection;
        return $this;
    }

    /**
     * Set Server Reply URL.
     *
     * @param string $url
     * @return static
     */
    public function setServerReplyURL(string $url)
    {
        $this->content['ServerReplyURL'] = $url;
        return $this;
    }

    /**
     * Set Extra Data.
     *
     * @param string $data
     * @return static
     */
    public function setExtraData(string $data)
    {
        $this->content['ExtraData'] = $data;
        return $this;
    }

    public function setLgsType(LgsType $type)
    {
        $this->content['LgsType'] = $type->value;
        return $this;
    }

    public function setShipType(ShipType $type)
    {
        $this->content['ShipType'] = $type->value;
        return $this;
    }

    public function setReturnURL(string $url)
    {
        $this->content['ReturnURL'] = $url;
        return $this;
    }
}
