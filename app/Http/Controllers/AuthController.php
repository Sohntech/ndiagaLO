<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Interfaces\AuthServiceInterface;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        return $this->authService->login($credentials);
    }

    public function refresh(Request $request)
    {
        $refreshToken = $request->validate(['refresh_token' => 'required'])['refresh_token'];
        return $this->authService->refresh($refreshToken);
    }

    public function logout(Request $request)
    {
        return $this->authService->logout($request->bearerToken());
    }
}