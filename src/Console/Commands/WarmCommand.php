<?php

namespace PaulhenriL\LaravelLambdaEngine\Console\Commands;

use Aws\Lambda\LambdaClient;
use Illuminate\Console\Command;
use GuzzleHttp\Promise;
use Illuminate\Support\Facades\Log;

class WarmCommand extends Command
{
    protected $signature = 'laravel-lambda-engine:warm {functionName} {count?}';

    protected $description = 'PreWarm lambda';

    public function handle()
    {
        $client = $this->lambdaClient();
        $warmCount = $this->argument('count') ?? 1;
        $function = $this->argument('functionName') ?? 1;
        $requests = [];

        for ($i = 0; $i < $warmCount; $i++) {
            $requests[$i] = $client->invokeAsync([
                'FunctionName' => $function,
                'invocationType' => 'event',
                'Payload' => json_encode([
                    'body' => '',
                    'path' => route('laravel_lambda_engine.lambda.warm', [], false),
                    'httpMethod' => 'GET',
                ]),
            ]);
        }

        $responses = Promise\Utils::settle($requests)->wait();
        $results = [];

        foreach ($responses as $k => $v) {
            $results[$k] = $v['state'];
        }

        Log::info("[$function] Warming requests sent", $results);
    }

    protected function lambdaClient()
    {
        return new LambdaClient([
            'region' => $_ENV['AWS_DEFAULT_REGION'],
            'version' => 'latest',
            'http' => [
                'timeout' => 5,
                'connect_timeout' => 5,
            ],
        ]);
    }
}
