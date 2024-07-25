<?php

namespace App;
use Illuminate\Http\JsonResponse;

class BaseResponse
{
    public static function response(bool $success, mixed $data, string $msg, int $status): JsonResponse
    {
        return response()->json([
            'ok' => $success,
            'data' => $data,
            'msg' => $msg
        ], $status);
    }
}
