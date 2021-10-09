<?php

use Illuminate\Support\Facades\Route;
use \PaulhenriL\LaravelLambdaEngine\Http\Controllers;

Route::get('/lambda/warm', Controllers\WarmController::class)->name('lambda.warm');
