@extends('layouts.app')
@section('breadcrumb')
@breadcrumbs(['title'=>'Horários', 'icon'=>'fa-clock-o', 'route'=>route('horarios.index'),'subtitle'=>'Gerenciamento de Horários'])

@endbreadcrumbs
@endsection

@section('toolbar')
@toolbar
<a href='' class="btn btn-sm btn-success mr-1 mb-1" onclick="adminFormSubmit(event)" > <i class="fa fa-save"></i>Salvar</a>
<a href='' class="btn btn-sm btn-outline-secondary mr-1 mb-1" data-close='1' onclick="adminFormSubmit(event)" > <i class="fa fa-save"></i>Salvar e Fechar</a>
<a class="btn btn-sm btn-outline-secondary mr-1 mb-1"  href="{{route('horarios.index')}}" > <i class="fa fa-close"></i>Cancelar</a>

@endtoolbar
@endsection
@section('content')

<div class="row">
    <div class="col-md-12">
        <div class="tile">


           @yield('edit-content')


        </div>
    </div>

</div>

@endsection
