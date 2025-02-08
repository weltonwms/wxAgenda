<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\User;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username()
    {
        return "username";
    }

    /**
     * @override
     */
    protected function validateLogin(Request $request)
    {
        $user= User::where('username', $request->username)->first();       
        
        if( $user && !$user->isActive()){
            throw ValidationException::withMessages([$this->username() => 'Usuário Desativado.']);
        }

        
        if($user && !$user->validateUltimaRecargaStudent()){
            $msg = "Você passou mais de 30 dias sem efetuar recarga. Faça uma recarga para continuar seus estudos";
            throw ValidationException::withMessages([$this->username() => $msg]);
        }

        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);
    }

    /*
    * Fluxos Após o Usuário ser autenticado
    */
    protected function authenticated(Request $request, $user)
    {
      //set session das messages_not_read        
      session(['messages_not_read'=>$user->countMessagesNotRead()]);
      
    }
}
