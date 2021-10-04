<?php

namespace PaulhenriL\LaravelLambdaEngine;

use Illuminate\Support\Facades\Config;
use PaulhenriL\LaravelEngineCore\EngineServiceProvider;

class LaravelLambdaEngineServiceProvider extends EngineServiceProvider
{
    public function register()
    {
        $this->configureTrustedProxy();
    }

    protected function configureTrustedProxy()
    {
        Config::set('trustedproxy.proxies', Config::get('trustedproxy.proxies', [
            '127.0.0.1', '0.0.0.0/0', '2000:0:0:0:0:0:0:0/3'
        ]));
    }
}
