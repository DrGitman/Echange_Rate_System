<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExchangeController;
use App\Http\Controllers\GraphController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;


Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.attempt');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::post('/register', [RegisterController::class, 'store'])->name('register.store');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');



Route::middleware('auth')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');

    Route::get('/calculator', [ExchangeController::class, 'index'])
        ->name('calculator.index');

    Route::post('/calculator', [ExchangeController::class, 'calculate'])
        ->name('calculator.calculate');

    // Graph Routes
    Route::get('/graph', [GraphController::class, 'index'])->name('graph');

    // API Routes for Graph/Live Data
    Route::get('/api/rates/{from}/{to}/{days?}', [\App\Http\Controllers\RateApiController::class, 'getRates'])->name('api.rates');

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'show'])
        ->name('profile.show');

    Route::post('/profile', [ProfileController::class, 'update'])
        ->name('profile.update');

    Route::post('/profile/avatar', [ProfileController::class, 'updateAvatar'])
        ->name('profile.avatar');

    Route::post('/profile/password', [ProfileController::class, 'updatePassword'])
        ->name('profile.password');
});