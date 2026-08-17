@extends('teachers.master-edit')

@section('edit-content')

<form method="POST" action="{{ route('teachers.update', $teacher->id) }}" id="adminForm">
    @csrf
    @method('PUT')
    @include('teachers.form')
</form>
@endsection
