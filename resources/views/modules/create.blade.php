@extends('modules.master-edit')

@section('edit-content')

{!! Form::open(['route'=>'modules.store','class'=>'','id'=>'adminForm'])!!}
                @include('modules.form')

 {!! Form::close() !!}
@endsection