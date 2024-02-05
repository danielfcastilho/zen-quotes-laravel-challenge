<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\UserController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

Route::get('/today', function () {
    return Inertia::render('Quote/Today', [
        'quote' => ['quote_text' => "lorem ipsum [cached]"],
        'randomInspirationalImage' => "image"
    ]);
})->name('today');

Route::get('/api-test', function () {
    return Inertia::render('Api/Test', [
        'quote' => ['quote_text' => "lorem ipsum [cached]"],
        'randomInspirationalImage' => "image"
    ]);
})->name('api-test');

Route::middleware('guest')->group(function () {
    Route::get('/quotes', function () {
        return Inertia::render('Quote/Quotes', [
            'quote' => ['quote_text' => "lorem ipsum [cached]"],
            'randomInspirationalImage' => "image"
        ]);
    })->name('quotes');

    Route::get('register', [UserController::class, 'create'])->name('register');

    Route::post('register', [UserController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('/secure-quotes', function () {
        return Inertia::render('Quote/Secure', [
            'quote' => ['quote_text' => "lorem ipsum [cached]"],
            'randomInspirationalImage' => "image"
        ]);
    })->name('secure-quotes');
    
    Route::get('/favorite-quotes', function () {
        return Inertia::render('Quote/Favorite', [
            'quote' => ['quote_text' => "lorem ipsum [cached]"],
            'randomInspirationalImage' => "image"
        ]);
    })->name('favorite-quotes');
    
    Route::get('/report-favorite-quotes', function () {
        return Inertia::render('Quote/ReportFavorite', [
            'quote' => ['quote_text' => "lorem ipsum [cached]"],
            'randomInspirationalImage' => "image"
        ]);
    })->name('report-favorite-quotes');

    Route::get('/profile', [UserController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [UserController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [UserController::class, 'destroy'])->name('profile.destroy');

    Route::post('logout', [AuthenticationController::class, 'destroy'])->name('logout');
});
