<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Teacher;
use App\Models\Horario;
use App\Models\Celula;

class GradeController extends Controller
{
    public function index()
    {
        $teachersList = Teacher::getList();
        $horariosList = Horario::getList()->values();
        return view('grade.index', compact('teachersList', 'horariosList'));
    }

    public function getEventsCelula()
    {
        $mapCelulas=Celula::getEventsCelula(request('start'),request('end'),request('teacher_id'));
        return response()->json($mapCelulas);
    }

    public function getCelula(Celula $celula)
    {
        return response()->json($celula->load('students', 'aula'));
    }
}
