<?php

use Illuminate\Support\Facades\Route;
use PaulhenriL\LaravelLambdaEngine\Http\Controllers;

if (config('laravel_lambda_engine.signed_upload.enable')) {
    Route::middleware(config('laravel_lambda_engine.signed_upload.middlewares'))
        ->post('/signed_upload_url', [Controllers\SignedUploadUrlController::class, 'store'])
        ->name('signed_upload_url.store');
}
