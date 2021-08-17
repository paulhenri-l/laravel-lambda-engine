<?php

namespace PaulhenriL\LaravelLambdaEngine\Concerns;

use Illuminate\Support\Facades\Config;

/*
The MIT License (MIT)

Copyright (c) 2019 Taylor Otwell <taylor@laravel.com>

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
/*

/**
 * @author Taylor Otwell <taylor@laravel.com>
 * @author Paul-Henri Leobon
 */
trait ConfiguresSqs
{
    protected function ensureSqsIsConfigured()
    {
        // Ensure we are running on Lambda...
        if (!isset($_ENV['LAMBDA_TASK_ROOT'])) {
            return;
        }

        Config::set('queue.connections.sqs', array_merge([
            'driver' => 'sqs',
            'key' => $_ENV['AWS_ACCESS_KEY_ID'] ?? null,
            'secret' => $_ENV['AWS_SECRET_ACCESS_KEY'] ?? null,
            'token' => $_ENV['AWS_SESSION_TOKEN'] ?? null,
            'prefix' => $_ENV['SQS_PREFIX'] ?? null,
            'queue' => $_ENV['SQS_QUEUE'] ?? 'default',
            'region' => $_ENV['AWS_DEFAULT_REGION'] ?? 'us-east-1',
        ], Config::get('queue.connections.sqs') ?? []));
    }
}
