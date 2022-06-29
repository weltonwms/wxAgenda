@extends('disciplinas.master-edit')

@section('edit-content')

{!! Form::open(['route'=>'disciplinas.store','class'=>'','id'=>'adminForm'])!!}
                @include('disciplinas.form')

 {!! Form::close() !!}
@endsection