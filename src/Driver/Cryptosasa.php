<?php

namespace Merkeleon\SMS\Driver;

use Illuminate\Support\Str;

class Cryptosasa implements DriverInterface
{
    const PRIORITY_NORMAL = 'NORMAL';

    private $url;
    private $secret;
    private $username;

    public function __construct(array $config)
    {
        $this->url      = array_get($config, 'url');
        $this->secret   = array_get($config, 'secret');
        $this->username = array_get($config, 'username');
    }

    public function send($to, $text)
    {
        $endpoint  = rtrim($this->url, '/') . "/v1/sms";
        $timestamp = now()->format('c');
        $reference = Str::uuid()
                        ->toString();
        $token     = base64_encode(hash_hmac('sha512', $timestamp . $reference, $this->secret, true));

        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
            'x-timestamp: ' . $timestamp,
            'x-reference: ' . $reference,
            'x-username: ' . $this->username,
            'sms-auth-token: ' . $token,
        ];

        $payload = [
            'reference'   => $reference,
            'username'    => $this->username,
            'priority'    => self::PRIORITY_NORMAL,
            'phoneNumber' => ltrim($to, '+'),
            'message'     => $text,
        ];

        $ch = curl_init($endpoint);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result   = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        logger()->debug('Cryptosasa response: ', (array)json_decode($result, true));

        return $httpCode >= 200 && $httpCode < 300;
    }
}