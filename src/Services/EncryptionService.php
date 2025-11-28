<?php

declare(strict_types=1);

namespace CarlLee\NewebPayLogistics\Services;

use RuntimeException;

class EncryptionService
{
    /**
     * Encrypt data using AES-256-CBC.
     *
     * @param array $data
     * @param string $key
     * @param string $iv
     * @return string
     */
    public function encrypt(array $data, string $key, string $iv): string
    {
        $payload = http_build_query($data);
        // Pad IV to 16 bytes if shorter, or truncate if longer (though usually it should be 16)
        // NewebPay documentation says HashIV is variable length but AES-256-CBC requires 16 bytes IV.
        // Usually, if the provided IV is shorter, it's padded with null bytes or spaces.
        // Let's pad with null bytes to be safe and suppress the warning.
        $iv = str_pad($iv, 16, "\0");

        $encrypted = openssl_encrypt(
            $payload,
            'AES-256-CBC',
            $key,
            OPENSSL_RAW_DATA,
            $iv
        );

        if ($encrypted === false) {
            throw new RuntimeException('Encryption failed');
        }

        return bin2hex($encrypted);
    }

    /**
     * Hash data using SHA256.
     *
     * @param string $data
     * @param string $key
     * @param string $iv
     * @return string
     */
    public function hash(string $data, string $key, string $iv): string
    {
        $hashString = "HashKey={$key}&{$data}&HashIV={$iv}";
        return strtoupper(hash('sha256', $hashString));
    }
}
