<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Firebase Cloud Messaging
    |--------------------------------------------------------------------------
    |
    | You can use the legacy server key for simple HTTP notifications (less
    | secure) or configure the Admin SDK with a service-account JSON file.
    |
    */

    'fcm' => [
        // Legacy FCM server key (from Firebase Console -> Project settings -> Cloud Messaging)
        'server_key' => env('FIREBASE_SERVER_KEY', null),

        // Use this URL for legacy HTTP API
        'send_url'   => env('FIREBASE_SEND_URL', 'https://fcm.googleapis.com/fcm/send'),

        // Optional default notification settings
        'default' => [
            'title' => env('FIREBASE_DEFAULT_TITLE', 'New Schedule'),
            'body'  => env('FIREBASE_DEFAULT_BODY', 'You have a new scheduled visit.'),
        ],
        // Use HTTP v1 API with service account when true
        'use_v1' => env('FIREBASE_USE_V1', false),

        // Path to service account JSON file (required for v1)
        'service_account_path' => env('FIREBASE_SERVICE_ACCOUNT_PATH', storage_path('app/firebase-service-account.json')),

        // Google Cloud project id (required for v1)
        'project_id' => env('FIREBASE_PROJECT_ID', null),
    ],
];
