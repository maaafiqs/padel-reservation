<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\UserReservationController;
use App\Http\Controllers\UserProfileController;

// Public Routes
Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/lang/{locale}', [\App\Http\Controllers\LanguageController::class, 'switchLang'])->name('lang.switch');

// Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    // Password Reset Routes
    Route::get('/forgot-password', [\App\Http\Controllers\PasswordResetController::class, 'request'])->name('password.request');
    Route::post('/forgot-password', [\App\Http\Controllers\PasswordResetController::class, 'email'])->name('password.email');
    Route::get('/reset-password/{token}', [\App\Http\Controllers\PasswordResetController::class, 'reset'])->name('password.reset');
    Route::post('/reset-password', [\App\Http\Controllers\PasswordResetController::class, 'update'])->name('password.update');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // User Routes
    Route::middleware('role:user')->prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [UserReservationController::class, 'dashboard'])->name('dashboard');
        
        Route::get('/reservations', [UserReservationController::class, 'index'])->name('reservations.index');
        Route::get('/reservations/create', [UserReservationController::class, 'create'])->name('reservations.create');
        Route::post('/reservations', [UserReservationController::class, 'store'])->name('reservations.store');
        
        Route::get('/reservations/{reservation}/pay', [UserReservationController::class, 'pay'])->name('reservations.pay');
        Route::post('/reservations/{reservation}/pay', [UserReservationController::class, 'uploadPayment'])->name('reservations.uploadPayment');
        Route::get('/reservations/{reservation}/download', [UserReservationController::class, 'downloadTicket'])->name('reservations.download');

        // API for fetching booked slots
        Route::get('/api/booked-slots', [UserReservationController::class, 'getBookedSlots'])->name('api.booked.slots');
        Route::get('/api/available-coaches', [UserReservationController::class, 'getAvailableCoaches'])->name('api.available.coaches');

        Route::get('/profile', [UserProfileController::class, 'edit'])->name('profile.edit');
        Route::put('/profile', [UserProfileController::class, 'update'])->name('profile.update');
    });

    // Admin Routes
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        
        Route::resource('courts', \App\Http\Controllers\Admin\CourtController::class);
        Route::resource('reservations', \App\Http\Controllers\Admin\ReservationController::class);
        Route::resource('coaches', \App\Http\Controllers\Admin\CoachController::class);
        Route::get('inventories/{inventory}/qrcode', [\App\Http\Controllers\Admin\InventoryController::class, 'qrcode'])->name('inventories.qrcode');
        Route::resource('inventories', \App\Http\Controllers\Admin\InventoryController::class);
        Route::resource('announcements', \App\Http\Controllers\Admin\AnnouncementController::class);
        Route::resource('discounts', \App\Http\Controllers\Admin\DiscountController::class);
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);

        // Backup & Restore
        Route::get('/backup', [\App\Http\Controllers\Admin\BackupController::class, 'index'])->name('backup.index');
        Route::post('/backup/export', [\App\Http\Controllers\Admin\BackupController::class, 'export'])->name('backup.export');
        Route::post('/backup/restore', [\App\Http\Controllers\Admin\BackupController::class, 'restore'])->name('backup.restore');
    });
});
