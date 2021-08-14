<?php

namespace PaulhenriL\LaravelLambdaEngine\Tests\Feature;

use PaulhenriL\LaravelLambdaEngine\LaravelLambdaEngineServiceProvider;
use PaulhenriL\LaravelLambdaEngine\Tests\TestCase;

class EngineLoadedTest extends TestCase
{
    public function test_that_the_engine_is_loaded()
    {
        $providers = $this->app->getLoadedProviders();

        $engineIsLoaded = $providers[LaravelLambdaEngineServiceProvider::class] ?? false;

        $this->assertTrue($engineIsLoaded);
    }
}
