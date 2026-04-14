<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\UserChatController;
use App\Http\Controllers\Web\AdminController;

// User chat (public)
Route::get('/', [UserChatController::class, 'index']);

// Admin routes
Route::get('/admin', function () {
    return session('admin_logged_in') ? redirect()->route('admin.dashboard') : redirect()->route('admin.login.show');
});

Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class, 'showLogin'])->name('admin.login.show');
    Route::post('/login', [AdminController::class, 'login'])->name('admin.login');
    Route::post('/logout', [AdminController::class, 'logout'])->name('admin.logout')->middleware('admin.auth');

    Route::middleware('admin.auth')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/bots/{bot}/edit', [AdminController::class, 'edit'])->name('admin.bots.edit');
        Route::post('/bots', [AdminController::class, 'store'])->name('admin.bots.store');
        Route::put('/bots/{bot}', [AdminController::class, 'update'])->name('admin.bots.update');
        Route::delete('/bots/{bot}', [AdminController::class, 'destroy'])->name('admin.bots.destroy');
        Route::post('/bots/{bot}/set-default', [AdminController::class, 'setDefault'])->name('admin.bots.setDefault');
    });
});
