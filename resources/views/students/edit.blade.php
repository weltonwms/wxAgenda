@extends('students.master-edit')

@section('edit-content')

{!! Form::model($student,['route'=>['students.update',$student->id],'class'=>'','id'=>'adminForm','method'=>'PUT'])!!}
        @include('students.form')


{!! Form::close() !!}

@include('students.credits.modal')
@endsection