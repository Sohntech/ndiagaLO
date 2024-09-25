<?php

return [
    'default' => env('SMS', 'twilio'),

    'sms' => [
        'twilio' => [
            'sid' => env('TWILIO_ACCOUNT_SID'),
            'token' => env('TWILIO_AUTH_TOKEN'),
            'from' => env('TWILIO_PHONE_NUMBER'),
        ],

        'infobite' => [
            'username' => env('INFOBIP_API_URL'),
            'password' => env('INFOBIP_API_KEY'),
        ],

        'vonage' => [
            'key' => env('VONAGE_API_KEY'),
            'secret' => env('VONAGE_API_SECRET'),
            'from' => env('BRAND_NAME'),
        ],
    ],
];