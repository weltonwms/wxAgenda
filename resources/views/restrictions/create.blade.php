@extends('restrictions.master-edit')

@section('edit-content')

{!! Form::open(['route'=>'restrictions.store','class'=>'','id'=>'adminForm'])!!}
                @include('restrictions.form')

 {!! Form::close() !!}
@endsection