<?php

return [
    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
    ],

    'gemini' => [
        'api_key' => env('GEMINI_API_KEY'),
    ],

    'serp' => [
        'api_key' => env('SERP_API_KEY'),
    ],

    'ai' => [
        'default_provider' => env('AI_DEFAULT_PROVIDER', 'openai'),
    ],

    'stripe' => [
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'webhook_secret' => env('STRIPE_WEBHOOK_SECRET'),
    ],

    'paypal' => [
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'secret' => env('PAYPAL_SECRET'),
        'mode' => env('PAYPAL_MODE', 'sandbox'),
        'webhook_id' => env('PAYPAL_WEBHOOK_ID'),
    ],

    'sentry' => [
        'dsn' => env('SENTRY_LARAVEL_DSN', env('SENTRY_DSN')),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],

    'facebook' => [
        'client_id' => env('FACEBOOK_CLIENT_ID'),
        'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
        'webhook_verify_token' => env('FACEBOOK_WEBHOOK_VERIFY_TOKEN'),
        'webhook_app_secret' => env('FACEBOOK_WEBHOOK_APP_SECRET', env('FACEBOOK_CLIENT_SECRET')),
    ],

    'instagram' => [
        'webhook_app_secret' => env('INSTAGRAM_WEBHOOK_APP_SECRET', env('FACEBOOK_WEBHOOK_APP_SECRET', env('FACEBOOK_CLIENT_SECRET'))),
    ],

    'twitter' => [
        'consumer_secret' => env('TWITTER_CONSUMER_SECRET'),
    ],

    'linkedin' => [
        'webhook_secret' => env('LINKEDIN_WEBHOOK_SECRET'),
    ],

    'tiktok' => [
        'client_secret' => env('TIKTOK_CLIENT_SECRET'),
    ],

    'pinterest' => [
        'webhook_secret' => env('PINTEREST_WEBHOOK_SECRET'),
    ],
];
