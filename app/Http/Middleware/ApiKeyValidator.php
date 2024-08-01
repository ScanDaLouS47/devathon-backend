<?php

namespace App\Http\Middleware;

use App\BaseResponse;
use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Symfony\Component\HttpFoundation\Response;

class ApiKeyValidator
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            $res = $request->headers->get('x-api-key');
            $value = Crypt::decryptString($res);
            
            if ($value = env('API_KEY')) {
                return $next($request);
            } else {
                return BaseResponse::response(false, null, "Unauthorized request", 403);
            }
        } catch (Exception $e) {
            return BaseResponse::response(false, null, "Unauthorized request", 403);
        }
    }
}
