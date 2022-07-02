
{{ Form::bsText('username',null,['label'=>"Username *"]) }}
{{ Form::bsPassword('password',['label'=>isset($user)?'Senha':'Senha ']) }}