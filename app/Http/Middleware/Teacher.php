<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Carbon\CarbonInterface;

class Teacher
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
        if (auth()->user()->isAdm) {
            return $next($request);
        }

        if (auth()->user()->isTeacher) {
            if ($request->route()->getName() === 'celulasBath.store' || $request->route()->getName() === 'celulas.store') {
                if ($request->teacher_id != auth()->user()->getIdTeacher()) {
                    return response()->json(['error' => "Não Permitida Alteração em outro Professor"], 403);
                }
            }

            if ($request->route()->getName() === 'celulas.destroy') {
                $celula = $request->route('celula');
                if ($celula->teacher_id != auth()->user()->getIdTeacher()) {
                    return response()->json(['error' => "Não Permitida Alteração em outro Professor"], 403);
                }
                if ($celula->students->count() > 0) {
                    return response()->json(['error' => "Não Permitido Apagar Células com Aluno(s)"], 403);
                }
                if($this->isCurrentWeek($celula)) {
                    return response()->json(['error' => "Não é permitido Apagar Células da Semana Corrente"], 403);
                }

            }
            return $next($request);
        }

        return response()->json(['error' => "Não Autorizado"], 403);
    }

    private function isCurrentWeek($celula) {
        $dia = Carbon::parse($celula->dia);

        $startOfWeek = Carbon::now()->startOfWeek(CarbonInterface::SUNDAY);
        $endOfWeek = Carbon::now()->endOfWeek(CarbonInterface::SATURDAY);
    
        return $dia->between($startOfWeek, $endOfWeek);
    }
}