<a class="app-header__logo" href="{{route('home')}}">{{ config('app.name', 'Laravel') }} </a>
    <!-- Sidebar toggle button--><a class="app-sidebar__toggle" href="#" data-toggle="sidebar"
      aria-label="Hide Sidebar"></a>
    <!-- Navbar Right Menu-->
    <ul class="app-nav">

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
          <i class="fa fa-user fa-lg"></i> {{ Auth::user()->username }} <i class="fa fa-caret-down" aria-hidden="true"></i>
        </a>
        <ul class="dropdown-menu settings-menu dropdown-menu-right">
        <li><a class="dropdown-item" href="{{route('changePassword.show')}}"><i class="fa fa-user fa-lg"></i> Perfil</a></li>
          <li>
              <a class="dropdown-item" href="{{ route('logout') }}"
              onclick="event.preventDefault();
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

   