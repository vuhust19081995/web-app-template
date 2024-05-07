<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    public function __construct(protected AuthService $authService){}

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): JsonResponse
    {
        if (! Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            return $this->responseErrorUnauthorized();
        }

        $token = $this->authService->generateToken();

        // $refreshToken = $request->user()->createRefreshToken()->plainTextToken;

        return $this->respondWithToken($token);
    }

    protected function respondWithToken(string $token)
    {
        return $this->success(
            [
                'access_token' => $token,
                'token_type' => 'Bearer',
                'expires_in' => null,
                // 'refresh_token' => $refreshToken,
                'user' => Auth::user(),
            ]
        );
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): JsonResponse
    {
        $request->user()->tokens()->delete();

        return $this->success([], __('auth.logout_success'));
    }
}
