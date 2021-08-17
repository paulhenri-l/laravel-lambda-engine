<?php

namespace PaulhenriL\LaravelLambdaEngine\Helpers;

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
class StorageDirectories
{
    public const PATH = '/tmp/storage';

    public static function create(): void
    {
        $directories = [
            self::PATH . '/app',
            self::PATH . '/bootstrap/cache',
            self::PATH . '/framework/cache',
            self::PATH . '/framework/views',
        ];

        foreach ($directories as $directory) {
            if (!is_dir($directory)) {
                fwrite(STDERR, "Creating storage directory: $directory" . PHP_EOL);
                mkdir($directory, 0755, true);
            }
        }
    }
}
