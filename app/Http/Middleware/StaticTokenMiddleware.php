<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StaticTokenMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        file_put_contents(storage_path('logs/payment.log'), "\n=============================================================================================================\n", FILE_APPEND);
        Log::channel('payment')->info("Request Captured Before Middleware:\n" . json_encode($request->all(), JSON_PRETTY_PRINT));

        $staticToken = env('STATIC_TOKEN_API');

        if (!$staticToken) {
            return response()->json(['success' => false, 'message' => 'STATIC_TOKEN_API not configured'], 500);
        }
        // Token pode ser passado no header ou como query parameter
        $providedToken = $request->header('Authorization') ?: $request->query('token');
        // Verifica se o token no cabeçalho segue o formato "Bearer {token}"
        if ($providedToken && strpos($providedToken, 'Bearer ') === 0) {
            $providedToken = substr($providedToken, 7); // Remove "Bearer " e pega o token real
        }

        if ($providedToken !== $staticToken) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 401);
        }

        return $next($request);
    }
}
