<?php

return [
    'driver' => env('SMS_DRIVER'),

    'drivers' => [
        'clickatell' => [
            'token' => env('SMS_CLICKATELL_TOKEN')
        ],
    ],
];