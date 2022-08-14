<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\PerfilRequest;

class PerfilController extends Controller
{
    public function showChangePassword()
    {
        return view('users.change-password');
    }

    public function updatePassword(PerfilRequest $request)
    {

        $user = \Auth::user();
        $new_password = $request->input('password');
        $user->password = \Hash::make($new_password);
        $user->save();
        \Session::flash('mensagem', ['type' => 'success', 'conteudo' => trans('Senha Alterada com Sucesso!')]);
        return back();
    }
}
