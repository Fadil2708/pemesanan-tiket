<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Midtrans Server Key
    |--------------------------------------------------------------------------
    |
    | This is your Midtrans Server Key. You can get it from your Midtrans dashboard.
    | For sandbox environment, use the sandbox server key.
    |
    */

    'server_key' => env('MIDTRANS_SERVER_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Midtrans Client Key
    |--------------------------------------------------------------------------
    |
    | This is your Midtrans Client Key. You can get it from your Midtrans dashboard.
    | For sandbox environment, use the sandbox client key.
    |
    */

    'client_key' => env('MIDTRANS_CLIENT_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Midtrans API Endpoint
    |--------------------------------------------------------------------------
    |
    | Set the API endpoint. Use 'https://api.sandbox.midtrans.com' for sandbox.
    |
    */

    'api_endpoint' => env('MIDTRANS_API_ENDPOINT', 'https://api.sandbox.midtrans.com'),
];
