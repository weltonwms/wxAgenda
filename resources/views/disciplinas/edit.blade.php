@extends('disciplinas.master-edit')

@section('edit-content')

<form method="POST" action="{{ route('disciplinas.update', $disciplina->id) }}" id="adminForm">
    @csrf
    @method('PUT')
    @include('disciplinas.form')
</form>
@endsection
