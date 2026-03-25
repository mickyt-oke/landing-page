<?php

use App\Http\Controllers\Web\Admin\ApplicationReviewController;
use App\Http\Controllers\Web\Admin\UserManagementController;
use App\Http\Controllers\Web\ApplicationController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\LandingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/faq', [LandingController::class, 'faq'])->name('faq');

/**
 * Auth (web)
 * - GET  /login: show login page (or redirect to home if login is a modal/section)
 * - POST /login: perform login
 */
Route::get('/login', [LandingController::class, 'index'])->name('login');
Route::post('/login', [LandingController::class, 'login'])->name('login.post');
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware('auth')->name('dashboard');

Route::post('/register', [LandingController::class, 'register'])->name('register');
Route::post('/logout', [LandingController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/applications/create', [ApplicationController::class, 'create'])->name('applications.create');
    Route::post('/applications', [ApplicationController::class, 'store'])->name('applications.store');  
    Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
});

    Route::prefix('admin')->name('web.admin.')->group(function () {
        Route::middleware(['role:reviewer,admin,superadmin'])->group(function () {
            Route::get('/admin/dashboard', [ApplicationReviewController::class, 'index'])->name('dashboard.admin');
            Route::post('/applications/{application}/start-review', [ApplicationReviewController::class, 'startReview'])->name('applications.start-review');
        });

        Route::middleware(['role:admin,superadmin'])->group(function () {
            Route::post('/applications/{application}/approve', [ApplicationReviewController::class, 'approve'])->name('applications.approve');
            Route::post('/applications/{application}/reject', [ApplicationReviewController::class, 'reject'])->name('applications.reject');
        });

        Route::middleware(['role:superadmin'])->group(function () {
            Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
            Route::patch('/users/{user}/role', [UserManagementController::class, 'updateRole'])->name('users.update-role');
        });
    });
