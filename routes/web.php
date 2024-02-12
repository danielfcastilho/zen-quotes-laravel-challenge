<?php

use App\Http\Controllers\ApiTestController;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\DailyQuoteController;
use App\Http\Controllers\FavoriteQuoteController;
use App\Http\Controllers\RandomQuoteController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::redirect('/', '/today');

Route::get('login', [AuthenticationController::class, 'create'])->name('login');

Route::post('login', [AuthenticationController::class, 'store']);

Route::get('/today/{new?}', [DailyQuoteController::class, 'show'])->where('option', 'new|')->name('today');

Route::get('/api-test', ApiTestController::class)->name('api-test');

Route::middleware('guest')->group(function () {
    Route::get('/quotes/{new?}', [RandomQuoteController::class, 'index'])->where('option', 'new|')->name('quotes');

    Route::get('register', [UserController::class, 'create'])->name('register');
    Route::post('register', [UserController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('/secure-quotes/{new?}', [RandomQuoteController::class, 'secureIndex'])->where('option', 'new|')->name('secure-quotes');

    Route::get('/favorite-quotes', [FavoriteQuoteController::class, 'index'])->name('favorite-quotes');

    Route::get('/report-favorite-quotes', [FavoriteQuoteController::class, 'report'])->name('report-favorite-quotes');

    Route::get('/profile', [UserController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [UserController::class, 'update'])->name('profile.update');

    Route::post('logout', [AuthenticationController::class, 'destroy'])->name('logout');
});
