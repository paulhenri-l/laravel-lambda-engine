<?php

namespace PaulhenriL\LaravelLambdaEngine;

use Illuminate\Support\Facades\Config;
use PaulhenriL\LaravelEngineCore\EngineServiceProvider;
use PaulhenriL\LaravelLambdaEngine\Console\Commands\WarmCommand;
use Symfony\Component\HttpFoundation\Request;

class LaravelLambdaEngineServiceProvider extends EngineServiceProvider
{
    public function register()
    {
        $this->configureTrustedProxy();
    }

    public function boot()
    {
        $this->loadWebRoutes();
        $this->loadCommand(WarmCommand::class);
    }

    protected function configureTrustedProxy()
    {
        Config::set('trustedproxy.proxies', Config::get('trustedproxy.proxies', [
            '127.0.0.1', '0.0.0.0/0', '2000:0:0:0:0:0:0:0/3'
        ]));

        Config::set('trustedproxy.headers', Config::get('trustedproxy.headers', [
            Request::HEADER_X_FORWARDED_FOR | Request::HEADER_X_FORWARDED_HOST | Request::HEADER_X_FORWARDED_PORT | Request::HEADER_X_FORWARDED_PROTO
        ]));
    }
}
