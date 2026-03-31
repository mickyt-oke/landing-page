<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->group(function () {
    Route::middleware('throttle:5,1')->group(function () {
        Route::post('register', [AuthController::class, 'register'])->name('register');
        Route::post('login', [AuthController::class, 'login'])->name('login');
    });

    // Optional: prevent browser navigation to this endpoint (GET) from throwing MethodNotAllowed
    // by returning a clear JSON response. Kept OUTSIDE throttle middleware to avoid 500 when
    // the "api" rate limiter isn't defined on this project.
    Route::get('login', function () {
        return response()->json([
            'message' => 'Method Not Allowed. Use POST /api/auth/login.',
        ], 405);
    });

// authentication routes

    Route::middleware('auth:api')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::post('update-profile', [AuthController::class, 'updateProfile']);
        Route::post('change-password', [AuthController::class, 'changePassword']);
    });

// status check route (/api/status/check)
    Route::get('status/check', function () {
        return response()->json(['status' => 'API is working']);
    });
    Route::post('status/check', function () {
        return response()->json(['status' => 'API is working']);
    });
    

// other API routes (e.g. applications, admin actions) would go here, protected by auth:api middleware as needed
    Route::middleware('auth:api')->group(function () {
        // Example protected route
        Route::get('protected', function () {
            return response()->json(['message' => 'You are authenticated.']);
        });
    });
});
