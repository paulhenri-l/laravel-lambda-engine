<?php

namespace PaulhenriL\LaravelLambdaEngine\Http\Controllers;

use Aws\S3\S3Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SignedUploadUrlController
{
    public function store(Request $request)
    {
        $uploadDisk = config('laravel_lambda_engine.signed_upload.disk');
        $uploadDiskConfig = config("filesystems.disks.{$uploadDisk}");

        $uploadId = Str::orderedUuid();
        $uploadRoot = $uploadDiskConfig['root'];
        $path = '/tmp/' . $uploadId;

        $contentType = $request->input('content_type') ?? 'application/octet-stream';
        $cacheControl = $request->input('cache_control') ?? null;
        $expires = $request->input('expires') ?? null;

        /** @var S3Client $s3Client */
        $s3Client = Storage::disk($uploadDisk)
            ->getDriver()
            ->getAdapter()
            ->getClient();

        $command = $s3Client->getCommand('putObject', array_filter([
            'Bucket' => $uploadDiskConfig['bucket'],
            'Key' => $key = $uploadRoot . $path,
            'ACL' => 'private',
            'ContentType' => $contentType,
            'CacheControl' => $cacheControl,
            'Expires' => $expires,
        ]));

        $signedRequest = $s3Client->createPresignedRequest(
            $command,
            '+5 minutes'
        );

        return new JsonResponse([
            'upload_id' => $uploadId,
            'key' => $key,
            'path' => $path,
            'url' => (string)$signedRequest->getUri(),
            'headers' => array_merge(
                $signedRequest->getHeaders(),
                ['Content-Type' => $contentType]
            )
        ]);
    }
}
