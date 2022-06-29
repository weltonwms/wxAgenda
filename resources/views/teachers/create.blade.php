@extends('teachers.master-edit')

@section('edit-content')

{!! Form::open(['route'=>'teachers.store','class'=>'','id'=>'adminForm'])!!}
                @include('teachers.form')

 {!! Form::close() !!}
@endsection