<?php

return [
    'signed_upload' => [
        'enable' => false,
        'disk' => 's3_private',
        'middlewares' => ['auth:api']
    ],

    'warmer' => [
        'latency' => 50, // milliseconds
    ],
];
