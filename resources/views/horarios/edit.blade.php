@extends('horarios.master-edit')

@section('edit-content')

<form method="POST" action="{{ route('horarios.update', $horario->horario) }}" id="adminForm">
    @csrf
    @method('PUT')
    @include('horarios.form')
</form>
@endsection
