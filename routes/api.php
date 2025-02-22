<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/articles', [ArticleController::class, 'getArticles']);
Route::get('/fetch-articles', [ArticleController::class, 'fetchArticles']);
