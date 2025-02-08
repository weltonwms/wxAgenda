@extends('layouts.app')

@section('content')
@if(Request::session()->has('warningUltimaRecarga'))
    <div class="alert alert-danger alert-dismissable">
        <button type="button" class="close" data-dismiss="alert" 
                aria-hidden="true">&times;</button>
        {!!session('warningUltimaRecarga')!!}
    </div>
@endif

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
    </div>
</div>

<br>
@if(auth()->user()->isAdm)
    @include('dashboard.adm')
@endif
<br>
@if(auth()->user()->isStudent)
    @include('dashboard.student')
@endif

@if(auth()->user()->isTeacher)
    @include('dashboard.teacher')
@endif
<br>
@if($celulasInfo && $celulasInfo->count())
    @include('pendenciasinfostudent.modal')
@endif

@endsection


