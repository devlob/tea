<?php

namespace Devlob\Auth;

use DateInterval;
use DateTime;
use Exception;

/**
 * Class JWT
 *
 * Manage JWT.
 *
 * @package Devlob\Auth
 */
class JWT
{
    /**
     * Generate JWT token.
     *
     * @param array $payload
     *
     * @return string
     * @throws Exception
     */
    public function generate(array $payload): string
    {
        $secret = env('APP_SECRET');

        $header = json_encode([
            'typ' => 'JWT',
            'alg' => 'HS256'
        ]);

        $payload['expiration'] = (new DateTime())->add(new DateInterval('P10Y'))->getTimestamp();

        $payload = json_encode($payload);

        $base64URLHeader  = $this->base64URLEncode($header);
        $base64URLPayload = $this->base64URLEncode($payload);
        $signature        = hash_hmac('sha256', "$base64URLHeader.$base64URLPayload", $secret, true);

        $base64URLSignature = $this->base64URLEncode($signature);

        return "$base64URLHeader.$base64URLPayload.$base64URLSignature";
    }

    /**
     * Validate JWT.
     *
     * @param string $jwt
     *
     * @return bool
     * @throws Exception
     */
    public function validate(string $jwt): bool
    {
        $secret = env('APP_SECRET');

        $tokenParts = explode('.', $jwt);

        if (count($tokenParts) === 3) {
            $header            = base64_decode($tokenParts[0]);
            $payload           = base64_decode($tokenParts[1]);
            $signatureProvided = $tokenParts[2];

            $tokenExpired = new DateTime() > new DateTime('@' . json_decode($payload)->expiration);

            $base64URLHeader  = $this->base64URLEncode($header);
            $base64URLPayload = $this->base64URLEncode($payload);
            $signature        = hash_hmac('sha256', "$base64URLHeader.$base64URLPayload", $secret, true);

            $base64URLSignature = $this->base64URLEncode($signature);

            $isValidSignature = $base64URLSignature === $signatureProvided;

            if ( ! $tokenExpired && $isValidSignature) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get JWT payload.
     *
     * @param string $jwt
     *
     * @return false|string
     */
    public function getPayload(string $jwt)
    {
        $tokenParts = explode('.', $jwt);

        return base64_decode($tokenParts[1]);
    }

    /**
     * Replace characters for encoded text.
     *
     * @param string $text
     *
     * @return string|string[]
     */
    private function base64URLEncode(string $text)
    {
        return str_replace(
            ['+', '/', '='],
            ['-', '_', ''],
            base64_encode($text)
        );
    }
}