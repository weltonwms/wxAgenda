<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1,user-scalable=0">
  <!--CSS Template-->
  <link rel="stylesheet" type="text/css" href="{{ asset('template/css/main.css') }}">
   <!-- Font-icon css-->
  <link rel="stylesheet" type="text/css" href="{{ asset('template/css/font-awesome-4.7.0/css/font-awesome.min.css') }}">
     <!--CSS App-->
  <link rel="stylesheet" type="text/css" href="{{ asset('css/app.css') }}">
  <title>Login - {{ config('app.name', 'Laravel') }}</title>
  </head>
  <body>
    <section class="material-half-bg">
      <div class="cover"></div>
    </section>
    <section class="login-content">
      <div class="logo">
        {{-- <h1>{{ config('app.name', 'Laravel') }}</h1> --}}
      <img src="{{asset('img/logo.png')}}" alt="logo {{ config('app.name', 'Laravel') }}">
      </div>
      <div class="login-box">
        <form method="POST" class="login-form" action="{{ route('login') }}">
            @csrf
          <h3 class="login-head"><i class="fa fa-lg fa-fw fa-user"></i>LOG IN</h3>
          <div class="form-group">
            <label class="control-label" for="username">USUÁRIO</label>
            <input class="form-control @error('username') is-invalid @enderror" type="text" placeholder="Usuário" id="username" name="username" value="{{ old('username') }}" required autofocus>
            @error('username')
            <span class="invalid-feedback" role="alert">
                <strong>{{ $message }}</strong>
            </span>
            @enderror
          </div>
          <div class="form-group">
            <label class="control-label" for="password">SENHA</label>
            <input class="form-control @error('password') is-invalid @enderror" type="password" placeholder="Senha" id="password" name="password" required autocomplete="current-password">
            @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
            @enderror
          </div>
          <div class="form-group">
            <div class="utility">
              <div class="animated-checkbox">
                <label>
                  <input type="checkbox"><span class="label-text">Manter Conectado</span>
                </label>
              </div>
              {{-- <p class="semibold-text mb-2"><a href="#" data-toggle="flip">Forgot Password ?</a></p> --}}
            </div>
          </div>
          <div class="form-group btn-container">
            <button class="btn btn-primary btn-block"><i class="fa fa-sign-in fa-lg fa-fw"></i>ENTRAR</button>
          </div>
        </form>
        
      </div>
    </section>
    <!-- Essential javascripts for application to work-->
  <script src="{{ asset('template/js/jquery-3.3.1.min.js') }}"></script>
  <script src="{{ asset('template/js/popper.min.js') }}"></script>
  <script src="{{ asset('template/js/bootstrap.min.js') }}"></script>
  <script src="{{ asset('template/js/main.js') }}"></script>
  
  <!-- The javascript plugin to display page loading on top-->
  <script src="{{ asset('template/js/plugins/pace.min.js') }}"></script>
   
  </body>
</html>