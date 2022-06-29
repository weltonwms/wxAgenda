@extends('aulas.master-edit')

@section('edit-content')

{!! Form::model($aula,['route'=>['aulas.update',$aula->id],'class'=>'','id'=>'adminForm','method'=>'PUT'])!!}
        @include('aulas.form')


{!! Form::close() !!}
@endsection