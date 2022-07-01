<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use Illuminate\Http\Request;
use App\Http\Requests\HorarioRequest;

class HorarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $horarios = Horario::all();
        //dd($horarios);
        return view("horarios.index", compact('horarios'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dados = [
            'turnosList' => Horario::getTurnosList(),
        ];
        return view('horarios.create', $dados);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(HorarioRequest $request)
    {

        try {
            $horario = \DB::transaction(function () use ($request) {
                $horario = new Horario;
                $horario->horario = $request->horario;
                $horario->turno_id = $request->turno_id;
                $horario->save();
                return $horario;
            });

            \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionCreate')]);
            if ($request->input('fechar') == 1):
                return redirect()->route('horarios.index');
            endif;
            return redirect()->route('horarios.edit', $horario->horario);
        }
        catch (\Exception $e) {
            \Session::flash('mensagem', ['type' => 'danger', 'conteudo' => $this->getMessageError($e)]);
            return redirect()->route('horarios.index');
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Horario  $horario
     * @return \Illuminate\Http\Response
     */
    public function show(Horario $horario)
    {
        return $horario;
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Horario  $horario
     * @return \Illuminate\Http\Response
     */
    public function edit(Horario $horario)
    {
        $dados = [
            'turnosList' => Horario::getTurnosList(),
            'horario' => $horario
        ];
        return view('horarios.edit', $dados);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Horario  $horario
     * @return \Illuminate\Http\Response
     */
    public function update(HorarioRequest $request, Horario $horario)
    {
        try {
            \DB::transaction(function () use ($horario, $request) {
                $horario->horario = $request->horario;
                $horario->turno_id = $request->turno_id;
                $horario->save();
                \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionUpdate')]);

            });
        }
        catch (\Exception $e) {
            \Session::flash('mensagem', ['type' => 'danger', 'conteudo' => $this->getMessageError($e)]);
        }

        if ($request->input('fechar') == 1):
            return redirect()->route('horarios.index');
        endif;
        return redirect()->route('horarios.edit', $horario->horario);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Horario  $horario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Horario $horario)
    {
        $retorno = $horario->verifyAndDelete();
        if ($retorno):
            \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('messages.actionDelete')]);
        endif;
        return redirect()->route('horarios.index');
    }

    public function destroyBath()
    {
        $retorno = Horario::verifyAndDestroy(request('ids'));

        if ($retorno):
            \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans_choice('messages.actionDelete', $retorno)]);
        endif;
        return redirect()->route('horarios.index');

    }

    private function getMessageError($e)
    {
        $errorCode = $e->errorInfo[1];
        if ($errorCode == 1062) {
            return "Horário Duplicado";
        }
        return $e->getMessage();
    }
}
