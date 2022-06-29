@extends('students.master-edit')

@section('edit-content')

{!! Form::open(['route'=>'students.store','class'=>'','id'=>'adminForm'])!!}
                @include('students.form')

 {!! Form::close() !!}
@endsection