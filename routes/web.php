<?php

use App\Http\Controllers\BusinessController;
use App\Http\Controllers\CashbookController;
use App\Http\Controllers\CashInController;
use App\Http\Controllers\CashOutController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ProfileController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::resource('businesses', BusinessController::class);
    Route::get('/businesses/export/{type}', [BusinessController::class, 'export'])->name('businesses.export');

    Route::prefix('business')->group(function () {
        Route::resource('members', MemberController::class);
        Route::resource('cashbooks', CashbookController::class);

        Route::prefix('cashbook')->group(function () {
            Route::resource('cash-ins', CashInController::class);
            Route::resource('cash-outs', CashOutController::class);
        });
    });
});
