<?php

namespace App;
use Illuminate\Http\JsonResponse;

class NotFoundResponse
{
    public static function response(): JsonResponse
    {
        return BaseResponse::response(false, null, 'Resource not found', 404);
    }
}
