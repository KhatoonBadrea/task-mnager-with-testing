<?php

namespace App\Http\Traits;

trait ApiResponseTrait
{

    public function apiResponse($success, $message, $data = null, $statusCode = 200)
    {
        return response()->json([

            'success'  => $success,
            'message'  => $message,
            'data'     => $data,
        ], $statusCode);
    }

    public function successResponse($message, $data = null, $statusCode = 200)
    {
        return $this->apiResponse(true, $message, $data, $statusCode);
    }


    public function errorResponse($message, $statusCode = 400)
    {
        return $this->apiResponse(false, $message, null, $statusCode);
    }

    public function notFound($message)
    {

        return response()->json([
            'success'  => false,
            'message'  => $message,
            'code'     => 404
        ]);
    }
    public function Unauthorized($message)
    {

        return response()->json([
            'success'  => false,
            'message'  => $message,
            'code'     => 401
        ]);
    }

    //==========Auth
    public function loginResponse($data, $token)
    {
        return response()->json([
            'success' => true,
            'message' => 'login successfuly',
            'data'    => $data,
            'code'   => 200,
            'token'  => $token
        ]);
    }

    public function registerResponse($data)
    {
        return response()->json([
            'success' => true,
            'message' => 'register successfuly',
            'data'    => $data,
            'code'   => 201
        ]);
    }
}
