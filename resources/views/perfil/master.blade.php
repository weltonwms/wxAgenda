@extends('layouts.app')
@section('content')


<div class="tile">
    @include('users.change-password')
    <hr>
    @if (auth()->user()->isStudent)     
        @include('perfil.student')
    @endif   
</div>
@endsection