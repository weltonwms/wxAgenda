@extends('disciplinas.master-edit')

@section('edit-content')

{!! Form::model($disciplina,['route'=>['disciplinas.update',$disciplina->id],'class'=>'','id'=>'adminForm','method'=>'PUT'])!!}
        @include('disciplinas.form')


{!! Form::close() !!}
@endsection