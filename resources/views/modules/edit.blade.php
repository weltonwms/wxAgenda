@extends('modules.master-edit')

@section('edit-content')

{!! Form::model($module,['route'=>['modules.update',$module->id],'class'=>'','id'=>'adminForm','method'=>'PUT'])!!}
        @include('modules.form')


{!! Form::close() !!}
@endsection