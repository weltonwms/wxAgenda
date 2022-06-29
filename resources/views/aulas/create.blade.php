@extends('aulas.master-edit')

@section('edit-content')

{!! Form::open(['route'=>'aulas.store','class'=>'','id'=>'adminForm'])!!}
                @include('aulas.form')

 {!! Form::close() !!}
@endsection