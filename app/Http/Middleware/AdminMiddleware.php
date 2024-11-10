<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request. 
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            // dd( $user);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token is invalid'], 401);
            Log::error('Error in TaskService@create_Task: ' . $e->getMessage() . ' | User Role: ' . json_encode($user->role));
        }
    // dd($user->role->id != 1);
        // التأكد من أن المستخدم لديه دور 'admin' باستخدام علاقة role
        try{

            if ($user->role->id != 1) {
                return response()->json(['error' => 'Unauthorized'], 403);
            }
        }catch (JWTException $e) {
            // return response()->json(['error' => 'Token is invalid'], 401);
            Log::error('Error in TaskService@create_Task: ' . $e->getMessage() . ' | User Role: ' . json_encode($user->role));
        }

    
        return $next($request);
    }
    
}
