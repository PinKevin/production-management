<?php

namespace App;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ApiResponse
{
    public static function sendResponse($message, $data, $success, $code)
    {
        $baseResponse = [
            'success' => $success,
            'message' => $message,
        ];

        if ($data instanceof AnonymousResourceCollection && $data->resource instanceof LengthAwarePaginator) {
            $paginatedData = $data->response()->getData(true);
            $response = array_merge($baseResponse, $paginatedData);
        } else {
            if ($data) {
                $response = array_merge($baseResponse, ['data' => $data]);
            } else {
                $response = $baseResponse;
            }
        }

        return response()->json($response, $code);
    }
}
