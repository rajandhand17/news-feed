<?php

use App\Http\Controllers\Api\Article\ArticleController;
use Illuminate\Support\Facades\Route;

Route::prefix('articles')->group(function () {
    Route::get('/', [ArticleController::class, 'index'])->name('articles.index'); // fetch articles with pagination and filters
    Route::get('/{id}', [ArticleController::class, 'show'])->name('articles.show'); // get a single article's details
});
