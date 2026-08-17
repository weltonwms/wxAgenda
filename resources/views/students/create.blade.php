@extends('students.master-edit')

@section('edit-content')

<form method="POST" action="{{ route('students.store') }}" id="adminForm">
    @csrf
    @include('students.form')
</form>
@endsection
