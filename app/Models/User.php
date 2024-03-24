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

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function administrator()
    {
        return $this->hasOne(Administrator::class);
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function sentMessages()
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function receivedMessages()
    {
        return $this->hasMany(Message::class, 'recipient_id');
    }

    public function messageReplies()
    {
        return $this->hasMany(MessageReply::class);
    }

    public function entidade()
    {
        switch(strtolower($this->tipo)):
            case 'administrator': return $this->administrator(); 
            case 'teacher': return $this->teacher();
            default: return $this->student();            
        endswitch;       
    }

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
            $request['tipo']= strtolower($reflect->getShortName());
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
    public function getIsAdmAttribute()
    {
        return strtolower($this->tipo) == 'administrator'; //retorna true se for Adm
    }

    public function getNomeAttribute()
    {
        if($this->entidade){
            return $this->entidade->nome;
        }
        return $this->username;
    }
    public function getEmailAttribute()
    {
        if($this->entidade){
            return $this->entidade->email;
        }
        return "";
    }
    public function getChatIdAttribute()
    {
        if($this->entidade){
            return $this->entidade->chat_id;
        }
        return "";
    }

    public function getIsTeacherAttribute()
    {
        return strtolower($this->tipo) == 'teacher'; //retorna true se for Teacher
    }

    public function getIsStudentAttribute()
    {
        return strtolower($this->tipo) == 'student'; //retorna true se for Student
    }

   
    public function getIdTeacher()
    {
        if($this->entidade && $this->isTeacher){
            return $this->entidade->id;
        }
        return null;    
    }

    public function isActive()
    {
        if($this->entidade){
            return $this->entidade->active?true:false;
        }
        return true; //se nao tiver entidade considera um ativo especial como admin
    }

    public static function getList()
    {
        //Cuidado!! Gasta muitos recursos;
        return self::all()->mapWithKeys(function($item){
                  
            return [$item->id => $item->nome];
        });
    }

    public static function getListToMessages()
    {
        //considerando que 3 entidades: Teachers, Students, e Adminstrators
        //Posteriormente retirar ou colocar mais entidades na lista.
       $list= \DB::table('teachers')
                ->select('nome', 'user_id')
                ->selectRaw("'Professor' AS tipo")
                ->where('user_id', '>', 0)
                ->where('active',1)
            ->union(\DB::table('students')
                ->select('nome', 'user_id')
                ->selectRaw("'Aluno' AS tipo")
                ->where('user_id', '>', 0)
                ->where('active',1))
            ->union(\DB::table('administrators')
                ->select('nome', 'user_id')
                ->selectRaw("'Administrador' AS tipo")
                ->where('user_id', '>', 0)
                ->where('active',1))
            ->get();
        return $list->mapWithKeys(function($user){
            return [$user->user_id=>$user->nome."(".$user->tipo.")"];
        });        
    }

    public function onMessageRead()
    {
        //set session das messages_not_read        
        session(['messages_not_read'=>$this->countMessagesNotRead()]);

    }

    public function getMessagesNotRead()
    {
        return $this->receivedMessages->where('is_read',0)->where('recipient_delete',0);

    }

    public function countMessagesNotRead()
    {
        return $this->getMessagesNotRead()->count();
    }
}
