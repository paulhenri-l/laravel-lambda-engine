<?php

namespace PaulhenriL\LaravelLambdaEngine\Concerns;

use Illuminate\Support\Facades\Config;

trait ConfiguresStorage
{
    protected function ensureStorageIsConfigured()
    {
        // Ensure we are running on Lambda...
        if (!isset($_ENV['LAMBDA_TASK_ROOT'])) {
            return;
        }

        Config::set('filesystems.disks.s3_public', array_merge([
            'driver' => 's3',
            'key' => $_ENV['AWS_ACCESS_KEY_ID'] ?? null,
            'secret' => $_ENV['AWS_SECRET_ACCESS_KEY'] ?? null,
            'token' => $_ENV['AWS_SESSION_TOKEN'] ?? null,
            'region' => $_ENV['AWS_DEFAULT_REGION'] ?? 'us-east-1',
            'bucket' => $_ENV['AWS_BUCKET_PUBLIC'] ?? null,
            'url' => $_ENV['AWS_URL'] ?? null,
            'endpoint' => $_ENV['AWS_ENDPOINT'] ?? null,
        ], Config::get('filesystems.disks.s3_public') ?? []));

        Config::set('filesystems.disks.s3_private', array_merge([
            'driver' => 's3',
            'key' => $_ENV['AWS_ACCESS_KEY_ID'] ?? null,
            'secret' => $_ENV['AWS_SECRET_ACCESS_KEY'] ?? null,
            'token' => $_ENV['AWS_SESSION_TOKEN'] ?? null,
            'region' => $_ENV['AWS_DEFAULT_REGION'] ?? 'us-east-1',
            'bucket' => $_ENV['AWS_BUCKET_PRIVATE'] ?? null,
            'url' => $_ENV['AWS_URL'] ?? null,
            'endpoint' => $_ENV['AWS_ENDPOINT'] ?? null,
        ], Config::get('filesystems.disks.s3_private') ?? []));
    }
}
