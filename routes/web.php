<?php

use App\Http\Controllers\Web\Admin\ApplicationReviewController;
use App\Http\Controllers\Web\Admin\UserManagementController;
use App\Http\Controllers\Web\ApplicationController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\LandingController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LandingController::class, 'index'])->name('home');

/**
 * Auth (web)
 * - GET  /login: show login page (or redirect to home if login is a modal/section)
 * - POST /login: perform login
 */
Route::get('/login', [LandingController::class, 'index'])->name('login');
Route::post('/login', [LandingController::class, 'login'])->name('login.post');

Route::post('/register', [LandingController::class, 'register'])->name('register');
Route::post('/logout', [LandingController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');


    Route::middleware(['role:user,reviewer,admin,superadmin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('/create-new', [ApplicationController::class, 'create'])->name('create');
        Route::post('/applications', [ApplicationController::class, 'store'])->name('applications.store');
        Route::get('/applications/{application}', [DashboardController::class, 'show'])->name('applications.show');
        Route::get('/applications/create', [ApplicationController::class, 'create'])->name('web.applications.create');
        Route::post('/applications', [ApplicationController::class, 'store'])->name('web.applications.store');
        Route::get('/documents/{document}/download', [ApplicationController::class, 'download'])
            ->name('web.documents.download');
    });

    Route::prefix('admin')->name('web.admin.')->group(function () {
        Route::middleware(['role:reviewer,admin,superadmin'])->group(function () {
            Route::get('/dashboard', [ApplicationReviewController::class, 'index'])->name('dashboard');
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
});
