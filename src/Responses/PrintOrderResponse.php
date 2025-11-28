<?php

declare(strict_types=1);

namespace CarlLee\NewebPayLogistics\Responses;

class PrintOrderResponse extends Response
{
    /**
     * Get HTML Content for printing.
     *
     * @return string|null
     */
    public function getHtmlContent(): ?string
    {
        // Sometimes print API returns HTML directly or a URL
        return $this->getRawBody();
    }
}
