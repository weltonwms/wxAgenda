@extends('administrators.master-edit')

@section('edit-content')

<form method="POST" action="{{ route('administrators.store') }}" id="adminForm">
    @csrf
    @include('administrators.form')
</form>
@endsection
