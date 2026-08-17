@extends('modules.master-edit')

@section('edit-content')

<form method="POST" action="{{ route('modules.store') }}" id="adminForm">
    @csrf
    @include('modules.form')
</form>
@endsection
