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
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin dashboard (fixed login stored in .env)
    |--------------------------------------------------------------------------
    */
    'admin' => [
        'email'    => env('ADMIN_EMAIL'),
        'password' => env('ADMIN_PASSWORD'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Authorize.Net
    |--------------------------------------------------------------------------
    */
    'authorizenet' => [
        'env'             => env('AUTHORIZENET_ENV', 'sandbox'), // sandbox | production
        'login_id'        => env('AUTHORIZENET_LOGIN_ID'),
        'transaction_key' => env('AUTHORIZENET_TRANSACTION_KEY'),
        'client_key'      => env('AUTHORIZENET_CLIENT_KEY'),
        'signature_key'   => env('AUTHORIZENET_SIGNATURE_KEY'),
        'verify_ssl'      => env('AUTHORIZENET_VERIFY_SSL', true),
        'ca_bundle'       => storage_path('certs/cacert.pem'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Credit Repair Cloud
    |--------------------------------------------------------------------------
    */
    'crc' => [
        'api_key'    => env('CRC_API_KEY'),
        'secret_key' => env('CRC_SECRET_KEY'),
        'base_url'   => env('CRC_BASE_URL', 'https://app.creditrepaircloud.com'),
        // Comma-separated team member names to assign the new client to (optional)
        'assigned_to' => env('CRC_ASSIGNED_TO'),
        // Agreement name to apply to the new client (optional, only if portal access on)
        'agreement'   => env('CRC_AGREEMENT'),
    ],

    'myfreescorenow' => [
        'enroll_url' => env('MYFREESCORENOW_ENROLL_URL', 'https://app.myfreescorenow.com/enroll/B01C4636'),
    ],

    'community' => [
        'telegram_url' => env('COMMUNITY_TELEGRAM_URL'),
    ],

    /*
    |--------------------------------------------------------------------------
    | GoHighLevel (CRM + automations) — configurable, off until keys provided
    |--------------------------------------------------------------------------
    */
    'ghl' => [
        'enabled'      => env('GHL_ENABLED', false),
        'api_key'      => env('GHL_API_KEY'),
        'location_id'  => env('GHL_LOCATION_ID'),
        'pipeline_id'  => env('GHL_PIPELINE_ID'),
        'endpoint'     => env('GHL_ENDPOINT', 'https://services.leadconnectorhq.com'),
        'calendar_url' => env('GHL_CALENDAR_URL'),
    ],

    'jotform' => [
        'contract_url' => env('JOTFORM_CONTRACT_URL'),
    ],

];
