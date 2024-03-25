<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\JobController;
use App\Http\Controllers\SkillController;
use App\Http\Controllers\JobSkillController;
use App\Http\Controllers\SubscriberController;

Route::group([
	'prefix' => 'v1'
], function ($router) {
	Route::apiResource('jobs', JobController::class);
	Route::apiResource('skills', SkillController::class);
	Route::apiResource('jobs/{job}/skills', JobSkillController::class);
	Route::post('/subscribe', [SubscriberController::class, 'subscribe']);
});
