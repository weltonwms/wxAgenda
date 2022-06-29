@extends('teachers.master-edit')

@section('edit-content')

{!! Form::model($teacher,['route'=>['teachers.update',$teacher->id],'class'=>'','id'=>'adminForm','method'=>'PUT'])!!}
        @include('teachers.form')


{!! Form::close() !!}
@endsection