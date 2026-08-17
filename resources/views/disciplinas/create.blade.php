@extends('disciplinas.master-edit')

@section('edit-content')

<form method="POST" action="{{ route('disciplinas.store') }}" id="adminForm">
    @csrf
    @include('disciplinas.form')
</form>
@endsection
