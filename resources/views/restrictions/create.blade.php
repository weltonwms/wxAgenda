@extends('restrictions.master-edit')

@section('edit-content')

<form method="POST" action="{{ route('restrictions.store') }}" id="adminForm">
    @csrf
    @include('restrictions.form')
</form>
@endsection
