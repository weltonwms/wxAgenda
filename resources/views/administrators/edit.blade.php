@extends('administrators.master-edit')

@section('edit-content')

<form method="POST" action="{{ route('administrators.update', $administrator->id) }}" id="adminForm">
    @csrf
    @method('PUT')
    @include('administrators.form')
</form>
@endsection
