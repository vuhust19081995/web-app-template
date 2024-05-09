<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Api\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Jiannei\Response\Laravel\Support\Facades\Response;

class RefreshTokenController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $refreshToken = $this->tokenFromRequest();

        if (! $refreshToken) {
            Response::errorUnauthorized();
        }

        $token = $request->user()->tokens->where('token', $refreshToken)->first()->token;

        if (! $token) {
            Response::errorUnauthorized();
        }

        if ($token->isExpired()) {
            Response::errorUnauthorized();
        }

        if (! $tokenable = $token->tokenable) {
            Response::errorUnauthorized();
        }

        $token = Auth::tokenById($tokenable->id);

        return Response::success(
            [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => Auth::factory()->getTTL() * 60,
            ]
        );
    }

    private function tokenFromRequest()
    {
        if (Cookie::has('refresh_token')) {
            return Cookie::get('refresh_token');
        }

        return request('refresh_token');
    }
}
