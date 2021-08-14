<?php

namespace PaulhenriL\LaravelLambdaEngine\Tests;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Contracts\Foundation\Application;
use PaulhenriL\LaravelLambdaEngine\LaravelLambdaEngineServiceProvider;

class TestCase extends \Orchestra\Testbench\TestCase
{
    protected function resolveApplicationConfiguration($app)
    {
        parent::resolveApplicationConfiguration($app);

        $app->make('config')->set(
            'app.key', '00000000000000000000000000000000'
        );

        // If you want to use mysql for testing comment this line and edit the
        // env variables in phpunit.xml, you'll also need to uncomment the
        // services and env lines inside the `.github/workflows/Tests.yml` file.
        $this->useSqlite($app);
    }

    protected function getPackageProviders($app)
    {
        return [
            LaravelLambdaEngineServiceProvider::class
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        // Prevents an awful bug from happening. Without this line The Kernel
        // instance used by the EngineServiceProvider won't be the same as the
        // one used in the TestCase. (This issue only happens with orchestra)
        $app->make(Kernel::class);
    }

    /**
     * Use sqlite for testing
     */
    protected function useSqlite(Application $app)
    {
        $app->make('config')->set('database.default', 'sqlite');
        $app->make('config')->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);
    }
}
