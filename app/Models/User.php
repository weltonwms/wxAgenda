<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


//use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use  HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        "username",
        "password",
        "tipo",
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function saveUser($request,$entidade)
    {
        if(!isset($request['username']) || !$request['username']){
            return false; 
        }
       
        if($entidade->user_id){
            //update
            self::updateUser($request, $entidade->user_id);
        }
        else{
            //create
            $reflect = new \ReflectionClass($entidade);
            $request['tipo']= $reflect->getShortName();
            $user=self::createUser($request);
            //update entidade com user_id
            $entidade->user_id=$user->id;
            $entidade->save();
        }
    }

    private static function createUser($request)
    {
        $dados=  self::tratarDados($request);
        $user=User::create($dados);
        return $user;
    }

    private static function updateUser($request, $user_id)
    {
        $dados=  self::tratarDados($request);
        $user= User::find($user_id);
        $user->update($dados);

    }

    private static function tratarDados($dados)
    {
        if($dados['password']):
            $dados['password']=  bcrypt($dados['password']);
        else:
            unset($dados['password']);
        endif;
        return $dados;
    }

    public static function destroyUserBath($users_ids)
    {
        return User::destroy($users_ids);
    }

    public static function destroyUser($user_id)
    {
        $user= User::find($user_id);
        $user->delete();   
    }
}
