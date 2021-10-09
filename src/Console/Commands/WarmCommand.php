<?php

namespace PaulhenriL\LaravelLambdaEngine\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;

class WarmCommand extends Command
{
    protected $signature = 'lambda-engine:warm {count?}';

    protected $description = 'PreWarm lambda';

    public function handle()
    {
        $client = new Client();
        $warmCount = $this->argument('count') ?? 1;
        $requests = [];

        $this->info("Will send {$warmCount} warming requests");

        for ($i = 0; $i < $warmCount; $i++) {
            $this->comment(" - [$i] request sent");
            $requests[$i] = $client->getAsync(
                route('laravel_lambda_engine.lambda.warm')
            );
        }

        $this->info("Waiting for responses");
        $responses = Promise\Utils::settle($requests)->wait();

        foreach ($responses as $k => $v) {
            $this->comment(" - [$k] state : {$v['state']}");
        }

        $this->info("Done.");
    }
}
