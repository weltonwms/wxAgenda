@extends('modules.master-edit')

@section('edit-content')

<form method="POST" action="{{ route('modules.update', $module->id) }}" id="adminForm">
    @csrf
    @method('PUT')
    @include('modules.form')
</form>
@endsection
