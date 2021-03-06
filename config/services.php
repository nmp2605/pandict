<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'dicionario_aberto' => [
        'base_uri' => env('DICIONARIO_ABERTO_BASE_URI', 'https://api.dicionario-aberto.net'),
        'force_live_client' => env('DICIONARIO_ABERTO_FORCE_LIVE_CLIENT', false),
    ],

    'dicio' => [
        'base_uri' => env('DICIO_BASE_URI', 'https://www.dicio.com.br'),
        'force_live_client' => env('DICIO_FORCE_LIVE_CLIENT', false),
    ],

];
