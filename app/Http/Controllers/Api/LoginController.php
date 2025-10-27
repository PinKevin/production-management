<?php

namespace App\Http\Controllers\Api;

use App\ApiResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        $token = auth()->guard()->attempt($credentials);

        if (!$token) {
            return ApiResponse::sendResponse('Wrong email or password', [], false, 401);
        }

        $data = [
            'user'    => auth()->guard()->user(),
            'token'   => $token
        ];

        return ApiResponse::sendResponse('Login successfull', $data, true, 201);
    }
}
