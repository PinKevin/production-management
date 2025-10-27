<?php

namespace App;

class ApiResponse
{
    public static function sendResponse($message, $data, $success, $code)
    {
        $response = [
            'success' => $success,
            'message' => $message,
        ];

        if ($data) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }
}
