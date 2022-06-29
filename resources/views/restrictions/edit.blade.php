@extends('restrictions.master-edit')

@section('edit-content')

{!! Form::model($restriction,['route'=>['restrictions.update',$restriction->id],'class'=>'','id'=>'adminForm','method'=>'PUT'])!!}
        @include('restrictions.form')


{!! Form::close() !!}
@endsection