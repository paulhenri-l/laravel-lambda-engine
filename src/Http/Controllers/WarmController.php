<?php

namespace PaulhenriL\LaravelLambdaEngine\Http\Controllers;

use Illuminate\Http\JsonResponse;

class WarmController
{
    const FIVE_HUNDRED_MILLISECONDS = 500000;

    public function __invoke()
    {
        usleep(static::FIVE_HUNDRED_MILLISECONDS);

        return new JsonResponse([
            'code' => 0,
            'message' => 'OK',
            'data' => []
        ]);
    }
}
