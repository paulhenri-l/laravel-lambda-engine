<?php

namespace PaulhenriL\LaravelLambdaEngine;

use PaulhenriL\LaravelEngineCore\Console\InstallTasks\PublishConfig;
use PaulhenriL\LaravelEngineCore\EngineServiceProvider;
use PaulhenriL\LaravelLambdaEngine\Console\Commands\WarmCommand;

class LaravelLambdaEngineServiceProvider extends EngineServiceProvider
{
    public function register()
    {
        $this->registerConfig();
    }

    public function boot()
    {
        $this->loadWebRoutes();
        $this->loadApiRoutes();
        $this->loadCommand(WarmCommand::class);
        $this->addInstallCommand(PublishConfig::class);
    }
}
