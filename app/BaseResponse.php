<?php

namespace App;
use Illuminate\Http\JsonResponse;

class BaseResponse
{
    public static function response(bool $success, mixed $data, string $msg, int $status): JsonResponse
    {
         
        $response = response()->json([
            'ok' => $success,
            'data' => $data,
            'msg' => $msg
        ], $status);

        $response->headers->set('Access-Control-Allow-Origin', 'http://localhost:5173');

        return $response;
    }
}
