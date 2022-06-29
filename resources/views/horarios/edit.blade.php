@extends('horarios.master-edit')

@section('edit-content')

{!! Form::model($horario,['route'=>['horarios.update',$horario->horario],'class'=>'','id'=>'adminForm','method'=>'PUT'])!!}
        @include('horarios.form')


{!! Form::close() !!}
@endsection