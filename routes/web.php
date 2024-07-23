<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\PasswordController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Client\CartController;
use App\Http\Controllers\Client\DetailController;
use App\Http\Controllers\Client\HomeController;
use App\Http\Controllers\Client\ShopController;
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
Route::middleware('check_account')->group(function () {
    Route::get('/', [HomeController::class, 'home'])->name('/');
    Route::get('shop', [ShopController::class, 'shop'])->name('shop');
    Route::get('detail/{id}', [DetailController::class, 'detail'])->name('detail');
    Route::get('cart', [CartController::class, 'cart'])->name('cart');
});

Route::prefix('account')->as('account.')->group(function () {
    // Route đăng ký
    Route::get('show-register', [RegisterController::class, 'showRegister'])->name('showRegister');
    Route::post('register', [RegisterController::class, 'register'])->name('register');

    // Route đăng nhập
    Route::get('show-login', [LoginController::class, 'showLogin'])->name('showLogin');
    Route::post('login', [LoginController::class, 'login'])->name('login');
    Route::get('logout', [LoginController::class, 'logout'])->name('logout');

    // Các route cần phải đăng nhập mới dùng được
    Route::prefix('auth')->as('auth.')->middleware('auth', 'check_account')->group(function () {
        Route::get('password/reset', [PasswordController::class, 'showLinkRequestForm'])->name('password.request');
        Route::post('password/email', [PasswordController::class, 'sendResetLinkEmail'])->name('password.email');
        Route::get('password/reset/{token}/{email}', [PasswordController::class, 'showResetForm'])->name('password.reset');
        Route::post('password/reset', [PasswordController::class, 'reset'])->name('password.update');
        Route::get('profile', [ProfileController::class, 'profile'])->name('profile');
        Route::put('profile/{id}', [ProfileController::class, 'editProfile'])->name('editProfile');
        Route::post('changePassword/{id}', [ProfileController::class, 'changePassword'])->name('changePassword');
    });
});

Route::prefix('admin')->as('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'dashboard'])->name('dashboard');
    Route::resource('categories', CategoryController::class);
});
