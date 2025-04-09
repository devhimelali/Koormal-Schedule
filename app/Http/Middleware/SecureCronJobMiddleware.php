<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecureCronJobMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Get the secret key from .env
        $secretKey = config('app.cron_secret');

        // Get the provided key from the request
        $providedKey = $request->header('X-CRON-KEY');

        // Validate header key
        if (!$providedKey || $providedKey !== $secretKey) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        return $next($request);
    }
}
