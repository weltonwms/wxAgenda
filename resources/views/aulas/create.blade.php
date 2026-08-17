@extends('aulas.master-edit')

@section('edit-content')

<form method="POST" action="{{ route('aulas.store') }}" id="adminForm">
    @csrf
    @include('aulas.form')
</form>
@endsection
