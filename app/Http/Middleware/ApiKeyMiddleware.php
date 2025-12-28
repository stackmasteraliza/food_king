<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ApiKeyMiddleware
{

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->hasHeader('x-api-key')) {
            $apiKey = $request->header('x-api-key');
            if ($apiKey && trim($apiKey) === trim(env('MIX_API_KEY'))) {
                return $next($request);
            }
        }
        // Log::info(trim(env('MIX_API_KEY')));
        return response()->json(trans('all.message.invalid_api_key'), 400);
    }
}
