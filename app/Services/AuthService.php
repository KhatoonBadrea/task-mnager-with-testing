<?php

namespace App\Services;

use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Request;

class AuthService
{
    use ApiResponseTrait;

    public function login(array $credentials)
    {

        if (!$token = auth()->attempt($credentials)) {
            return ['message' => 'Unauthorized'];
        }

        return $this->createNewToken($token);
    }





    public function register(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);
        return $this->registerResponse($user);
    }

    public function createNewToken($token)
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60,
            'user' => auth()->user()
        ];
    }

    public function logout()
    {
        Auth::logout();
    }

    public function refresh()
    {
        return $this->createNewToken(Auth::refresh());
    }
}
