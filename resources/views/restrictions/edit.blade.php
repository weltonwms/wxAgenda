@extends('restrictions.master-edit')

@section('edit-content')

<form method="POST" action="{{ route('restrictions.update', $restriction->id) }}" id="adminForm">
    @csrf
    @method('PUT')
    @include('restrictions.form')
</form>
@endsection
