<?php

return [
    /*
     * Available drivers:
     *
     * clickatell, log
     */
    'driver' => env('SMS_DRIVER'),

    'default' => 'log',

    'drivers' => [
        'clickatell' => [
            'token' => env('SMS_CLICKATELL_TOKEN')
        ],
        'log'        => [],
    ],
];