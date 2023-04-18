<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class Adm
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * 
     */
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->user()->isAdm) {
            return response()->json(['error'=>"Ação Não Autorizada! "],403);
            //return redirect('login');
        }
        return $next($request);
    }
}
