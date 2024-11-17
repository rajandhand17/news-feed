<?php

use App\Http\Controllers\Api\UserPreference\UserPreferenceController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('user-preference',[UserPreferenceController::class,'show']);
    Route::post('user-preference',[UserPreferenceController::class,'storeOrUpdate']);
    Route::get('news-feed',[UserPreferenceController::class,'personalizedFeed']);
});