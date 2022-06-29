<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=0">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Laravel') }} - Sistema</title>

  <meta name="description"  content="Sistema {{ config('app.name', 'Laravel') }} - Venda de Produtos">
  
  <!-- Font-icon css-->
  <link rel="stylesheet" type="text/css" href="{{ asset('template/css/font-awesome-4.7.0/css/font-awesome.min.css') }}">
  <!--CSS Template-->
  <link rel="stylesheet" type="text/css" href="{{ asset('template/css/main.css') }}">
   <!--CSS Datatables Responsive-->
  <link rel="stylesheet" type="text/css" href="{{ asset('template/css/responsive.bootstrap.min.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('template/css/select.dataTables.min.css') }}">

  <!--CSS APP-->
  <link href="{{ asset('css/app.css') }}" rel="stylesheet">

  <script> var laravel_token = '{{ csrf_token() }}';</script>
  <script> var asset = "{{asset('/')}}"</script>
</head>

<body class="app sidebar-mini">
  <!-- Navbar-->
  <header class="app-header">
    @include('layouts.header') 
  </header>
  <!-- Sidebar menu-->
  @include('layouts.sidebar') 


  <main class="app-content">
    @yield('breadcrumb')
    <div class="tile tile-mensagens">

      @if(Request::session()->has('mensagem'))
      
            <div class="alert alert-{{session('mensagem.type')}} alert-dismissable ">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                {!!session('mensagem.conteudo')!!}
            </div>
     @endif

    </div>

    @yield('toolbar')
    @yield('content')

  </main>
  

  <!-- Essential javascripts for application to work-->
  <script src="{{ asset('template/js/jquery-3.3.1.min.js') }}"></script>
  <script src="{{ asset('template/js/popper.min.js') }}"></script>
  <script src="{{ asset('template/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('template/js/main.js') }}"></script>
  <script src="{{ asset('template/js/plugins/bootstrap-notify.min.js') }}"></script>
   <script src="{{ asset('template/js/plugins/moment.min.js') }}"></script>
   <script src="{{ asset('template/js/plugins/sweetalert.min.js') }}"></script>
   <script src="{{ asset('template/js/plugins/jquery.mask.min.js') }}"></script>
   <script src="{{ asset('template/js/plugins/select2.min.js') }}"></script>
  <!-- The javascript plugin to display page loading on top-->
  <script src="{{ asset('template/js/plugins/pace.min.js') }}"></script>
  <!-- Main javascript to App-->
  <script src="{{ asset('js/app.js') }}"></script>
  @stack('scripts')
 
  
</body>

</html>