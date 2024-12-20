<h5 class="text-center">Mudar sua Senha</h5>

{{ Form::open(array('url' => 'perfil/changePassword')) }}

<div class="row">
    <div class="col-md-6 offset-md-3">
        {{ Form::bsPassword('password',['label'=>'Entre com a Nova Senha:']) }}
        {{ Form::bsPassword('password_confirmation',['label'=>'Confirme a Nova Senha:']) }}
    </div>
</div>

<div class="text-center">
    <button type="submit" class="btn btn-primary"> <i class="fa fa-fw fa-lg fa-check-circle"></i> Mudar Senha
    </button>
</div>


{!! Form::close() !!}
<br>