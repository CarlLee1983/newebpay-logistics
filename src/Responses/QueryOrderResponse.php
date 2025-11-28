<?php

declare(strict_types=1);

namespace CarlLee\NewebPayLogistics\Responses;

class QueryOrderResponse extends Response
{
    /**
     * Get Logistics Status.
     *
     * @return string|null
     */
    public function getLogisticsStatus(): ?string
    {
        return $this->get('LogisticsStatus');
    }
}
