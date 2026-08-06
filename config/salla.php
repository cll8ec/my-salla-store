<?php

declare(strict_types=1);

return [
    'client_id' => env('SALLA_CLIENT_ID', ''),
    'client_secret' => env('SALLA_CLIENT_SECRET', ''),
    'redirect_uri' => env('SALLA_REDIRECT_URI', ''),
    'merchant_id' => env('SALLA_MERCHANT_ID', ''),

    'base_url' => env('SALLA_BASE_URL', 'https://api.salla.sa'),
    'oauth_url' => env('SALLA_OAUTH_URL', 'https://accounts.salla.sa/oauth2/token'),
    'api_version' => env('SALLA_API_VERSION', 'v2'),

    'http' => [
        'timeout' => (int) env('SALLA_HTTP_TIMEOUT', 30),
        'retry_times' => (int) env('SALLA_HTTP_RETRY_TIMES', 3),
        'retry_delay_ms' => (int) env('SALLA_HTTP_RETRY_DELAY_MS', 1000),
    ],

    'cache' => [
        'token_key' => env('SALLA_CACHE_TOKEN_KEY', 'salla_access_token'),
        'ttl' => (int) env('SALLA_CACHE_TTL', 3540),
    ],

    'token_storage' => env('SALLA_TOKEN_STORAGE', 'database'),
    'webhooks' => [
        'enabled' => (bool) env('SALLA_WEBHOOKS_ENABLED', true),
        'secret' => env('SALLA_WEBHOOK_SECRET', ''),
    ],
];
