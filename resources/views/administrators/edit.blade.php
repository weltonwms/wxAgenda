@extends('administrators.master-edit')

@section('edit-content')

{!! Form::model($administrator,['route'=>['administrators.update',$administrator->id],'class'=>'','id'=>'adminForm','method'=>'PUT'])!!}
        @include('administrators.form')


{!! Form::close() !!}
@endsection