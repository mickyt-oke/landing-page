<?php

use App\Http\Controllers\Web\Admin\AdminDashboardController;
use App\Http\Controllers\Web\Admin\ApplicationReviewController;
use App\Http\Controllers\Web\Admin\ReviewerDashboardController;
use App\Http\Controllers\Web\Admin\UserManagementController;
use App\Http\Controllers\Web\ApplicationController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\LandingController;
use Illuminate\Support\Facades\Route;

// ── Public ────────────────────────────────────────────────────
Route::get('/', [LandingController::class, 'index'])->name('home');
Route::get('/faq', [LandingController::class, 'faq'])->name('faq');
Route::get('/login',  [LandingController::class, 'index'])->name('login');
Route::post('/login', [LandingController::class, 'login'])->name('login.post');
Route::post('/register', [LandingController::class, 'register'])->name('register');
Route::post('/logout', [LandingController::class, 'logout'])->middleware('auth')->name('logout');

// ── Authenticated user ────────────────────────────────────────
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/applications/create', [ApplicationController::class, 'create'])->name('applications.create');
    Route::post('/applications', [ApplicationController::class, 'store'])->name('applications.store');
    Route::get('/applications/{application}/success', [ApplicationController::class, 'show'])->name('applications.success');
    Route::get('/applications/{application}/acknowledgement', [ApplicationController::class, 'acknowledgement'])->name('applications.acknowledgement');
    Route::get('/applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
    Route::get('/applications/{application}/documents/{document}/download', [ApplicationController::class, 'download'])->name('applications.documents.download');
});

// ── Admin area ────────────────────────────────────────────────
Route::prefix('admin')->middleware('auth')->name('admin.')->group(function () {

    // ── Admin + Superadmin: management dashboard + approve ────
    Route::middleware('role:admin,superadmin')->group(function () {
        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::post('/applications/{application}/approve', [ApplicationReviewController::class, 'approve'])->name('applications.approve');
    });

    // ── Reviewer + Admin + Superadmin: reviewer dashboard, vetting, reject ──
    Route::middleware('role:reviewer,admin,superadmin')->group(function () {
        Route::get('/reviewer', [ReviewerDashboardController::class, 'index'])->name('reviewer.dashboard');

        // Application view + document download (shared)
        Route::get('/applications/{application}', [ApplicationReviewController::class, 'show'])->name('applications.show');
        Route::get('/applications/{application}/documents/{document}/download', [ApplicationReviewController::class, 'downloadDocument'])->name('applications.documents.download');

        // Workflow actions
        Route::post('/applications/{application}/start-review', [ApplicationReviewController::class, 'startReview'])->name('applications.start-review');
        Route::post('/applications/{application}/vet',          [ApplicationReviewController::class, 'vet'])->name('applications.vet');
        Route::post('/applications/{application}/reject',       [ApplicationReviewController::class, 'reject'])->name('applications.reject');
    });

    // ── Superadmin only: user management ─────────────────────
    Route::middleware('role:superadmin')->group(function () {
        Route::get('/users', [UserManagementController::class, 'index'])->name('users.index');
        Route::patch('/users/{user}/role', [UserManagementController::class, 'updateRole'])->name('users.update-role');
    });
});
