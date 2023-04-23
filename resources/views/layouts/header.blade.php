<a class="app-header__logo" href="{{route('home')}}">
    <span class="d-none d-sm-block">{{ config('app.name', 'Laravel') }} </span>
</a>
<!-- Sidebar toggle button--><a class="app-sidebar__toggle" href="#" data-toggle="sidebar"
    aria-label="Hide Sidebar"></a>
<!-- Navbar Right Menu-->
<ul class="app-nav">


    <li>
        <a href="{{ route('messages.index') }}" class="app-nav__item" title="Mensagens">
            <i class="fa fa-envelope-o fa-lg position-relative" aria-hidden="true">
              @if(session('messages_not_read'))
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                {{session('messages_not_read')}}
                </span>
              @endif
            </i>
        </a>
    </li>

    <!--Notification Menu-->
    @if(auth()->user()->isAdm)
    <li>
        <a href="{{ route('configurations.index') }}" class="app-nav__item" title="Configurações">
            <i class="fa fa-cog fa-lg" aria-hidden="true"></i>
        </a>
    </li>
    @endif
    <!-- User Menu-->
    <li class="dropdown">
        <a class="app-nav__item profile" href="#" data-toggle="dropdown" aria-label="Open Profile Menu">
            <i class="fa fa-user fa-lg position-relative">

            </i>
            {{ Auth::user()->username }}
            <i class="fa fa-caret-down" aria-hidden="true"></i>

            </span>
        </a>
        <ul class="dropdown-menu settings-menu dropdown-menu-right">
            <li><a class="dropdown-item" href="{{route('messages.index')}}"><i class="fa fa-envelope-o fa-lg"></i>
                    Mensagens</a></li>

            <li><a class="dropdown-item" href="{{route('changePassword.show')}}"><i class="fa fa-user fa-lg"></i>
                    Perfil</a></li>
            <li>
                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                             document.getElementById('logout-form').submit();">
                    <i class="fa fa-sign-out fa-lg"></i> Sair

                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </li>



        </ul>
    </li>
</ul>