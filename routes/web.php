<?php

use App\Http\Controllers\LoggroController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\GoogleController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Autenticación con Google
Route::get('/auth/google', [GoogleController::class, 'redirectToGoogle'])->name('google.login');
Route::get('/auth/google/callback', [GoogleController::class, 'handleGoogleCallback']);

Route::post('/admin/upload-loggro', [LoggroController::class, 'upload'])
    ->middleware(['auth'])
    ->can('admin-access') // Solo permite si el Gate 'admin-access' es true
    ->name('loggro.upload');

Route::get('/admin', [LoggroController::class, 'index'])
    ->middleware(['auth', 'can:admin-access'])
    ->name('admin.index');

require __DIR__.'/auth.php';
