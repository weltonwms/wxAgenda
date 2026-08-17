@extends('students.master-edit')

@section('edit-content')

<form method="POST" action="{{ route('students.update', $student->id) }}" id="adminForm">
    @csrf
    @method('PUT')
    @include('students.form')
</form>

@include('students.credits.modal')
@endsection
