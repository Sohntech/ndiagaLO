<?php

namespace App\Repositories;

use App\Interfaces\AuthRepositoryInterface;
use App\Models\BlacklistedToken;
use App\Models\UserMysql;
use Illuminate\Support\Facades\Hash;

class AuthRepository implements AuthRepositoryInterface
{
    public function findUserByRefreshToken(string $refreshToken)
    {
        return UserMysql::where('refresh_token', $refreshToken)->first();
    }

    public function blacklistToken(string $token, string $type)
    {
        return BlacklistedToken::create([
            'token' => $token,
            'type' => $type,
            'revoked_at' => now(),
        ]);
    }

    public function findUserByCredentials(array $credentials)
    {
        $user = UserMysql::where('email', $credentials['email'])->first();

        if ($user) {
            if (Hash::check($credentials['password'], $user->password)) {
                return $user;
            }
        }
        
        return null;
    }

}