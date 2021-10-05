<?php

namespace PaulhenriL\LaravelLambdaEngine;

use Aws\Ssm\SsmClient;
use Illuminate\Support\Str;

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

class SecretsLoader
{
    public static function addToEnvironment($parameters): void
    {
        foreach (static::all($parameters) as $key => $value) {
            fwrite(STDERR, "Injecting secret [{$key}] into runtime." . PHP_EOL);

            $_ENV[$key] = $value;
            $_SERVER[$key] = $value;
        }
    }

    public static function all(array $parameters = []): array
    {
        if (empty($parameters)) {
            return [];
        }

        $ssm = new SsmClient([
            'region' => $_ENV['AWS_DEFAULT_REGION'] ?? 'eu-west-3',
            'version' => 'latest',
        ]);

        return collect($parameters)->chunk(10)->reduce(function ($acc, $parameters) use ($ssm) {
            $ssmResponse = $ssm->getParameters([
                'Names' => $parameters->toArray(),
                'WithDecryption' => true
            ]);

            $parsedSecrets = static::parseSecrets(
                $ssmResponse['Parameters'] ?? []
            );

            return array_merge($acc, $parsedSecrets);
        }, []);
    }

    protected static function parseSecrets(array $secrets): array
    {
        $parsedSecrets = [];

        foreach ($secrets as $secret) {
            $name = explode('/', $secret['Name']);
            $name = array_pop($name);
            $name = Str::snake(Str::camel($name)); // camel to snake is needed
            $name = mb_strtoupper($name);

            $parsedSecrets[$name] = $secret['Value'];
        }

        return $parsedSecrets;
    }
}
