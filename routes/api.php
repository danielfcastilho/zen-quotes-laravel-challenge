<?php

use App\Http\Controllers\FavoriteQuoteController;
use App\Http\Controllers\RandomQuoteController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/quotes/{new?}', [RandomQuoteController::class, 'index'])->where('option', 'new|')->name('secure-quotes-api');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/secure-quotes/{new?}', [RandomQuoteController::class, 'secureIndex'])->where('option', 'new|')->name('secure-quotes-api');

    Route::post('/favorite-quotes', [FavoriteQuoteController::class, 'update'])->name('update-favorite-quotes-api');
});
