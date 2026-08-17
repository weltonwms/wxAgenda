@extends('horarios.master-edit')

@section('edit-content')

<form method="POST" action="{{ route('horarios.store') }}" id="adminForm">
    @csrf
    @include('horarios.form')
</form>
@endsection
