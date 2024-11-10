<?php

namespace App\Http\Controllers\Api;

use App\Services\AuthService;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Controllers\Controller; 
use App\Http\Traits\ApiResponseTrait;

class AuthController extends Controller
{
    use ApiResponseTrait;

    protected $authService; 

    public function __construct(AuthService $authService)
    {
        $this->middleware('auth:api', ['except' => ['login', 'register']]);
        $this->authService = $authService;
    }

    public function login(LoginRequest $request)
    {
        $result = $this->authService->login($request->validated());
       
        return response()->json($result);
    }

    public function register(RegisterRequest $request)
    {
        $user = $this->authService->register($request->validated());
        return response()->json($user, 201);
    }

    public function logout()
    {
        $this->authService->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }

    public function refresh()
    {
        return response()->json($this->authService->refresh());
    }
}
