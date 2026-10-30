<?php

use App\Http\Controllers\MovieController;
use App\Http\Controllers\Admin\RecommendationController;
use Illuminate\Support\Facades\Route;

// 1. Route Publik (Akses Pengunjung)
Route::get('/', [MovieController::class, 'index'])->name('movies.index');
Route::get('/movie/{id}', [MovieController::class, 'show'])->name('movies.show');

// 2. Route Autentikasi (Login/Logout Admin)
Route::get('/login', [RecommendationController::class, 'showLoginForm'])->name('login');
Route::post('/login', [RecommendationController::class, 'login'])->name('login.post');
Route::post('/logout', [RecommendationController::class, 'logout'])->name('logout');

// 3. Route Dashboard Pengelolaan Film (Hanya Bisa Diakses Setelah Login)
Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/recommendations', [RecommendationController::class, 'index'])->name('admin.recommendations');
    Route::post('/recommendations', [RecommendationController::class, 'store'])->name('admin.recommendations.store');
    Route::delete('/recommendations/{id}', [RecommendationController::class, 'destroy'])->name('admin.recommendations.destroy');
});
