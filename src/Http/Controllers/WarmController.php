<?php

namespace PaulhenriL\LaravelLambdaEngine\Http\Controllers;

use Illuminate\Http\JsonResponse;

class WarmController
{
    const ONE_MILLISECOND = 1000;

    public function index()
    {
        usleep(
            static::ONE_MILLISECOND * config('laravel_lambda_engine.warmer.latency', 50)
        );

        return new JsonResponse([
            'code' => 0,
            'message' => 'OK',
            'data' => []
        ]);
    }
}
