@extends('layouts.app')
@section('content')


<div class="tile">
    <div class="col-md-6 offset-md-3">

        <h4 class="tile-title text-center">Mudar sua Senha</h4>

        {{ Form::open(array('url' => 'perfil/changePassword')) }}


        {{ Form::bsPassword('password',['label'=>'Entre com a Nova Senha:']) }}
        {{ Form::bsPassword('password_confirmation',['label'=>'Confirme a Nova Senha:']) }}


        <div class="tile-footer text-center">
            <button type="submit" class="btn btn-primary"> <i class="fa fa-fw fa-lg fa-check-circle"></i> Mudar Senha
            </button>
        </div>


        {!! Form::close() !!}


    </div>
</div>
@endsection