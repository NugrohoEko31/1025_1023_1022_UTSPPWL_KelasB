<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;

// Halaman awal (Landing Page) -> Cek Autentikasi
Route::get('/', function () {
    return Auth::check() ? redirect('/home') : view('auth.login');
});

// Route untuk Autentikasi
Auth::routes();

// Middleware Auth untuk melindungi route
Route::middleware('auth')->group(function () {
    // Dashboard setelah login
    Route::get('/home', [HomeController::class, 'index'])->name('home');

    // Route CRUD untuk Users, Roles, dan Products
    Route::resource('roles', RoleController::class);
    Route::resource('users', UserController::class);
    Route::resource('products', ProductController::class);
});
