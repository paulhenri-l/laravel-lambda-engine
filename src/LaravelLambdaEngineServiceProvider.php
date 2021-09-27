<?php

namespace PaulhenriL\LaravelLambdaEngine;

use Illuminate\Support\Facades\Config;
use PaulhenriL\LaravelEngineCore\EngineServiceProvider;
use PaulhenriL\LaravelLambdaEngine\Concerns\ConfiguresAssets;
use PaulhenriL\LaravelLambdaEngine\Concerns\ConfiguresDynamoDb;
use PaulhenriL\LaravelLambdaEngine\Concerns\ConfiguresSqs;
use PaulhenriL\LaravelLambdaEngine\Concerns\ConfiguresStorage;

class LaravelLambdaEngineServiceProvider extends EngineServiceProvider
{
    use ConfiguresAssets,
        ConfiguresDynamoDb,
        ConfiguresSqs,
        ConfiguresStorage;

    public function register()
    {
        $this->ensureAssetPathsAreConfigured();
        $this->ensureDynamoDbIsConfigured();
        $this->ensureSqsIsConfigured();
        $this->configureTrustedProxy();
        $this->ensureStorageIsConfigured();
    }

    public function boot()
    {
        //
    }

    protected function configureTrustedProxy()
    {
        Config::set('trustedproxy.proxies', Config::get('trustedproxy.proxies', [
            '127.0.0.1', '0.0.0.0/0', '2000:0:0:0:0:0:0:0/3'
        ]));
    }
}
