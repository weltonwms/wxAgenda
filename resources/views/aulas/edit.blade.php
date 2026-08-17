@extends('aulas.master-edit')

@section('edit-content')

<form method="POST" action="{{ route('aulas.update', $aula->id) }}" id="adminForm">
    @csrf
    @method('PUT')
    @include('aulas.form')
</form>
@endsection
