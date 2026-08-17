@extends('teachers.master-edit')

@section('edit-content')

<form method="POST" action="{{ route('teachers.store') }}" id="adminForm">
    @csrf
    @include('teachers.form')
</form>
@endsection
