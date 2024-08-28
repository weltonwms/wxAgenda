<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Celula;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = auth()->user();
        $celulasInfo = null;
        $params = null;
        if ($user->isAdm || $user->isTeacher) {
            $params = $this->getParamsPendenciasInfoStudentOnCelula();
            $celulasInfo = Celula::getPendenciasInfoStudentOnCelula($params->start, $params->end, $params->teacher_id);
        }
        return view('dashboard.home', compact('celulasInfo', 'params'));
    }

    public function setSideBarToggle(Request $request)
    {
        $valueToggle = $request->sidenav_toggled == 'true';
        $isMobile = $request->isMobile == 'true';
        session(['sideBarToggle' => $valueToggle && !$isMobile ? "sidenav-toggled" : ""]);
        //var_dump($valueToggle);

        return response()->json([
            'sideBarToggle' => session('sideBarToggle'),

        ]);
    }

    public function pendenciasInfoStudentOnCelula()
    {
        $params = $this->getParamsPendenciasInfoStudentOnCelula();
        $celulasInfo = Celula::getPendenciasInfoStudentOnCelula($params->start, $params->end, $params->teacher_id);
        return view('pendenciasinfostudent.index', compact('celulasInfo', 'params'));
    }

    private function getParamsPendenciasInfoStudentOnCelula()
    {
        //Período de 7 dias atrás até o dia anterior 
        $startCarbon = \Carbon\Carbon::now()->subDays(7);
        $endCarbon = \Carbon\Carbon::now()->subDays(1);

        //Professor possui filtro para somente o professor autenticado
        $teacher_id = null;
        $teacher = auth()->user()->teacher;
        if ($teacher) {
            $teacher_id = $teacher->id;
        }

        $params = new \stdClass();
        $params->teacher_id = $teacher_id;
        $params->start = $startCarbon->format("Y-m-d");
        $params->end = $endCarbon->format("Y-m-d");
        $params->startBr = $startCarbon->format("d.m.Y");
        $params->endBr = $endCarbon->format("d.m.Y");        

        return $params;

    }
}
