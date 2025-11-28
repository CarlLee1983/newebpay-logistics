<?php

declare(strict_types=1);

namespace CarlLee\NewebPayLogistics\Requests;

use CarlLee\NewebPayLogistics\BaseRequest;
use CarlLee\NewebPayLogistics\Parameter\LgsType;
use CarlLee\NewebPayLogistics\Parameter\ShipType;
use CarlLee\NewebPayLogistics\Parameter\TradeType;
use CarlLee\NewebPayLogistics\Responses\CreateOrderResponse;

/**
 * Create Order Request
 */
class CreateOrderRequest extends BaseRequest
{
    /**
     * Request path.
     *
     * @var string
     */
    protected string $requestPath = '/create'; // TODO: Confirm endpoint

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
            'TradeType' => 'required|enum:' . TradeType::class,
            'UserName' => 'required',
            'UserTel' => 'required',
            'UserEmail' => 'required',
            'StoreID' => 'required',
            'Amt' => 'required',
            'LgsType' => 'required|enum:' . LgsType::class,
            'ShipType' => 'required|enum:' . ShipType::class,
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
        return CreateOrderResponse::class;
    }

    // Add setters for common fields
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
     * Set Receiver Name.
     *
     * @param string $name
     * @return static
     */
    public function setReceiverName(string $name)
    {
        $this->content['ReceiverName'] = $name;
        return $this;
    }

    /**
     * Set Receiver Phone.
     *
     * @param string $phone
     * @return static
     */
    public function setReceiverPhone(string $phone)
    {
        $this->content['ReceiverPhone'] = $phone;
        return $this;
    }

    /**
     * Set Receiver Cell Phone.
     *
     * @param string $cellPhone
     * @return static
     */
    public function setReceiverCellPhone(string $cellPhone)
    {
        $this->content['ReceiverCellPhone'] = $cellPhone;
        return $this;
    }

    /**
     * Set Receiver Email.
     *
     * @param string $email
     * @return static
     */
    public function setReceiverEmail(string $email)
    {
        $this->content['UserEmail'] = $email;
        return $this;
    }

    public function setTradeType(TradeType $type)
    {
        $this->content['TradeType'] = $type->value;
        return $this;
    }

    public function setUserName(string $name)
    {
        $this->content['UserName'] = $name;
        return $this;
    }

    public function setUserTel(string $tel)
    {
        $this->content['UserTel'] = $tel;
        return $this;
    }

    public function setStoreID(string $id)
    {
        $this->content['StoreID'] = $id;
        return $this;
    }

    public function setAmt(int $amt)
    {
        $this->content['Amt'] = $amt;
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

    // ... add other setters as needed
}
