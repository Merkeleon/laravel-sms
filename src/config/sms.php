<?php

return [
    /*
     * Available drivers:
     *
     * clickatell, nexmo, smsc, log, cryptosasa
     */
    'driver' => env('SMS_DRIVER'),

    'default' => 'log',

    'drivers' => [
        'clickatellV1' => [
            'token' => env('SMS_CLICKATELL_TOKEN'),
        ],
        'clickatellV2' => [
            'token' => env('SMS_CLICKATELL_TOKEN'),
        ],
        'nexmo'        => [
            'api_key'            => env('SMS_NEXMO_KEY'),
            'api_secret'         => env('SMS_NEXMO_SECRET'),
            'sender'             => env('SMS_NEXMO_SENDER'),
            'max_attempt_number' => env('SMS_NEXMO_MAX_ATTEMPT_NUMBER'),
        ],
        'smsc'         => [
            'login'    => env('SMS_SMSC_LOGIN'),
            'password' => env('SMS_SMSC_PASSWORD'),
            'sender'   => env('SMS_SMSC_SENDER'),
        ],
        'log'          => [],
        'cryptosasa'   => [
            'url'      => env('SMS_CRYPTOSASA_URL'),
            'username' => env('SMS_CRYPTOSASA_USERNAME'),
            'secret'   => env('SMS_CRYPTOSASA_SECRET'),
        ],
    ],
];