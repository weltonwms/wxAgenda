@extends('layouts.app')

@section('breadcrumb')
@breadcrumbs(['title'=>' Mensagens', 'icon'=>'fa-envelope-o', 'route'=>route('messages.index'),'subtitle'=>'Mensagens'])

@endbreadcrumbs
@endsection



@section('content')



<div class="row">
    <div class="col-md-3">
        @include('messages.sidebar')
    </div>
    <div class="col-md-9">
        @include($subView)
    </div>
</div>








@endsection