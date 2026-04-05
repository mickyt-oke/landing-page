<?php

use App\Http\Controllers\Api\AuthController;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->name('api.auth.')->group(function () {
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

    Route::middleware('auth:api')->group(function () {
        Route::get('me', [AuthController::class, 'me']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('refresh', [AuthController::class, 'refresh']);
        Route::post('update-profile', [AuthController::class, 'updateProfile']);
        Route::post('change-password', [AuthController::class, 'changePassword']);
    });

    // other API routes (e.g. applications, admin actions) would go here, protected by auth:api middleware as needed
    Route::middleware('auth:api')->group(function () {
        Route::get('protected', function () {
            return response()->json(['message' => 'You are authenticated.']);
        });
    });
});

// Public status check route — POST /api/status/check
Route::post('status/check', function (Request $request) {
    $reference      = $request->input('reference');
    $passportNumber = $request->input('passport_number');

    if (!$reference && !$passportNumber) {
        return response()->json(['message' => 'Please provide a reference number or passport number.'], 422);
    }

    $query = Application::query();

    if ($reference) {
        $query->where('application_reference', $reference)
              ->orWhere('ack_ref_number', $reference);
    } elseif ($passportNumber) {
        $query->where('passport_number', $passportNumber);
    }

    $application = $query->latest()->first();

    if (!$application) {
        return response()->json(['message' => 'No application found for the details provided.'], 404);
    }

    return response()->json([
        'status'     => $application->status,
        'name'       => $application->full_name,
        'reference'  => $application->application_reference,
        'created_at' => $application->submitted_at?->format('d M Y') ?? $application->created_at->format('d M Y'),
        'updated_at' => $application->updated_at->format('d M Y'),
    ]);
})->middleware('throttle:10,1');
