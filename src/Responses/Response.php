<?php

declare(strict_types=1);

namespace CarlLee\NewebPayLogistics\Responses;

/**
 * API Response Wrapper
 */
/**
 * API Response Wrapper
 */
class Response
{
    private string $rawBody;
    private array $data = [];
    private ?string $parseError = null;

    /**
     * Get the parse error if any.
     * 
     * @return string|null
     */
    public function getParseError(): ?string
    {
        return $this->parseError;
    }

    /**
     * Create a new instance.
     *
     * @param string $body
     */
    public function __construct(string $body)
    {
        $this->rawBody = $body;
        $this->parseBody();
    }

    /**
     * Parse the response body.
     *
     * @return void
     */
    private function parseBody(): void
    {
        if ($this->rawBody === '') {
            $this->parseError = 'Empty response body';
            return;
        }

        // Try parsing as URL encoded string
        parse_str($this->rawBody, $this->data);

        // If parsing failed or looks invalid, try JSON
        if (empty($this->data) || $this->isInvalidUrlParsed()) {
            $decoded = json_decode($this->rawBody, true);

            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $this->data = $decoded;
                $this->parseError = null;
            } elseif (empty($this->data)) {
                $this->parseError = 'Unable to parse response: Not valid URL encoded or JSON';
            }
        }
    }

    /**
     * Check if URL parsing produced invalid results.
     *
     * @return bool
     */
    private function isInvalidUrlParsed(): bool
    {
        if (count($this->data) === 1) {
            $keys = array_keys($this->data);
            $firstKey = $keys[0];
            $firstValue = $this->data[$firstKey];

            if (strpos($this->rawBody, 'Status=') !== false && strpos($this->rawBody, 'Message=') !== false) {
                return true;
            }

            if ($firstValue === '') {
                return true;
            }
        }

        return false;
    }

    /**
     * Get the data array.
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     * Get the error message.
     *
     * @return string|null
     */
    public function getErrorMessage(): ?string
    {
        if ($this->isSuccess()) {
            return null;
        }
        return $this->data['Message'] ?? 'Unknown error';
    }

    /**
     * Get a specific value.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }

    /**
     * Get raw body.
     *
     * @return string
     */
    public function getRawBody(): string
    {
        return $this->rawBody;
    }

    /**
     * Check if the response indicates success.
     *
     * @return bool
     */
    public function isSuccess(): bool
    {
        // NewebPay usually uses Status or RtnCode
        $status = $this->get('Status') ?? $this->get('RtnCode');
        return $status === 'SUCCESS' || $status === '1' || $status === '300';
    }

    /**
     * Get the response message.
     *
     * @return string
     */
    public function getMessage(): string
    {
        return (string) ($this->get('Message') ?? $this->get('RtnMsg') ?? '');
    }
}
