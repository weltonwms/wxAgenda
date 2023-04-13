@extends('layouts.app')

@section('content')
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
                    <button type="button" class="btn btn-lg btn-danger" data-toggle="popover" title="Popover title" data-content="And here's some amazing content. It's very engaging. Right?">Click to toggle popover</button>
                    <a class="pop-over-link" href="#" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="maiasd asdf asdf asdf adf weltonmoreira dos santos">maiasd asd</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
