<?php

namespace App\Services;

class AuthService
{
    const TOKEN_NAME = "auth_token";

    /**
     * Generate a token
     */
    public function generateToken(): string {
        return auth()->user()->createToken(static::TOKEN_NAME)->plainTextToken;
    }
}
