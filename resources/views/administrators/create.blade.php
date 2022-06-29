@extends('administrators.master-edit')

@section('edit-content')

{!! Form::open(['route'=>'administrators.store','class'=>'','id'=>'adminForm'])!!}
                @include('administrators.form')

 {!! Form::close() !!}
@endsection