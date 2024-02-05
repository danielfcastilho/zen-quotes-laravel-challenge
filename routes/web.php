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

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('login', [AuthenticationController::class, 'create'])
    ->name('login');

Route::post('login', [AuthenticationController::class, 'store']);

Route::middleware('guest')->group(function () {
    Route::get('register', [UserController::class, 'create'])
        ->name('register');

    Route::post('register', [UserController::class, 'store']);
});

Route::middleware('auth')->group(function () {
    Route::get('/today', function () {
        return Inertia::render('Today', [
            'quote' => ['quote_text' => "lorem ipsum [cached]"],
            'randomInspirationalImage' => "image"
        ]);
    })->middleware(['auth'])->name('today');

    Route::get('/profile', [UserController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [UserController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [UserController::class, 'destroy'])->name('profile.destroy');

    Route::post('logout', [AuthenticationController::class, 'destroy'])
        ->name('logout');
});
