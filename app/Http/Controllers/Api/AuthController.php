<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\JWTGuard;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = User::query()->create([
            'name' => $validated['sname'].' '.$validated['fname'],
            'surname' => $validated['sname'],
            'first_name' => $validated['fname'],
            'other_names' => null,
            'passport_number' => $validated['pptno'],
            'passport_type' => $validated['ppttype'],  
            'nationality' => $validated['nationality'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => User::ROLE_USER,
        ]);

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'message' => 'Registered successfully.',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'surname' => $user->surname,
                'first_name' => $user->first_name,
                'passport_number' => $user->passport_number,
                'passport_type' => $user->passport_type,
                'nationality' => $user->nationality,
                'email' => $user->email,
                'role' => $user->role,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => app(JWTGuard::class)->factory()->getTTL() * 60,
        ], 201);
    }
// login method with JWT authentication and redirect user to dashboard based on role
    public function login(LoginRequest $request): JsonResponse
    {
        $credentials = $request->validated();

        if (!$token = Auth::guard('api')->attempt($credentials)) {
            return response()->json(['error' => 'Invalid credentials.'], 401);
        }
        // return user details along with token and redirect route based on role in the response (user->dashboard, admin->admin/dashboard)
        return response()->json([
            'message' => 'Logged in successfully.',
            'user' => Auth::guard('api')->user(),
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => app(JWTGuard::class)->factory()->getTTL() * 60,
            'redirect_route' => $this->getRedirectRoute(Auth::guard('api')->user()->role),
        ]);
    }
    
    public function passwordreset(): JsonResponse
    {
        return response()->json([
            'message' => 'Password reset link sent successfully.',
        ]);
    }

    public function me(): JsonResponse
    {
        return response()->json([
            'user' => Auth::guard('api')->user(),
        ]);
    }

    public function logout(): JsonResponse
    {
        Auth::guard('api')->logout();

        return response()->json([
            'message' => 'Logged out successfully.',
        ]);
    }

    public function refresh(): JsonResponse
    {
        return response()->json([
            'access_token' => JWTAuth::refresh(),
            'token_type' => 'bearer',
            'expires_in' => app(JWTGuard::class)->factory()->getTTL() * 60,
        ]);
    }

    /**
     * Get the redirect route based on user role.
     *
     * @param string $role
     * @return string
     */
    protected function getRedirectRoute($role)
    {
        switch ($role) {
            case User::ROLE_ADMIN:
                return '/admin/dashboard';
            case User::ROLE_USER:
                return '/dashboard';
            default:
                return '/dashboard';
        }
    }
}
