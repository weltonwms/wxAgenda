<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class StaticTokenStudent
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
       
        $staticToken = env('STATIC_TOKEN_STUDENTS_API');
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
