@extends('horarios.master-edit')

@section('edit-content')

{!! Form::open(['route'=>'horarios.store','class'=>'','id'=>'adminForm'])!!}
                @include('horarios.form')

 {!! Form::close() !!}
@endsection