<!-- Sidebar menu-->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar">

  <ul class="app-menu">
    <li>
      <a class="app-menu__item {{Request::segment(1)=='home'?'active':null}}" href="{{ url('/home') }}">
        <i class="app-menu__icon fa fa-dashboard"></i><span class="app-menu__label">Painel de Controle</span>
      </a>
    </li>

    <li>
      <a class="app-menu__item {{Request::segment(1)=='modules'?'active':null}}" href="{{route('modules.index')}}">
        <i class="app-menu__icon fa fa-cubes"></i><span class="app-menu__label">Módulos</span>
      </a>
    </li>

    <li>
      <a class="app-menu__item {{Request::segment(1)=='disciplinas'?'active':null}}" href="{{route('disciplinas.index')}}">
        <i class="app-menu__icon fa fa-book"></i><span class="app-menu__label">Disciplinas</span>
      </a>
    </li>

    <li>
      <a class="app-menu__item {{Request::segment(1)=='aulas'?'active':null}}" href="{{route('aulas.index')}}">
        <i class="app-menu__icon fa fa-file-text"></i><span class="app-menu__label">Aulas</span>
      </a>
    </li>

    <li>
      <a class="app-menu__item {{Request::segment(1)=='restrictions'?'active':null}}" href="{{route('restrictions.index')}}">
        <i class="app-menu__icon fa fa-lock"></i><span class="app-menu__label">Restrições</span>
      </a>
    </li>

    <li>
      <a class="app-menu__item {{Request::segment(1)=='vendas'?'active':null}}" href="{{route('horarios.index')}}">
        <i class="app-menu__icon fa fa-clock-o"></i><span class="app-menu__label">Horários</span>
      </a>
    </li>

    <li>
      <a class="app-menu__item {{Request::segment(1)=='teachers'?'active':null}}" href="{{route('teachers.index')}}">
        <i class="app-menu__icon fa fa-blind"></i><span class="app-menu__label">Professores</span>
      </a>
    </li> 

    
    <li>
      <a class="app-menu__item {{Request::segment(1)=='vendas'?'active':null}}" href="{{route('students.index')}}">
        <i class="app-menu__icon fa fa-graduation-cap"></i><span class="app-menu__label">Alunos</span>
      </a>
    </li>

    <li class="treeview">
      <a class="app-menu__item {{Request::segment(1)=='relatorio'?'active':null}}" href="#" data-toggle="treeview">
        <i class="app-menu__icon fa fa-th-list"></i><span class="app-menu__label">Relatórios</span>
        <i class="treeview-indicator fa fa-angle-right"></i>
      </a>
      <ul class="treeview-menu">
       
        <li>
          <a class="treeview-item {{Request::segment(2)=='vendas'?'active':null}}" href="{{route('home')}}">
            <i class="icon fa fa-circle-o"></i> Vendas
          </a>
        </li>

        <li>
          <a class="treeview-item {{Request::segment(2)=='produtos'?'active':null}}" href="{{route('home')}}">
            <i class="icon fa fa-circle-o"></i> Produtos
          </a>
        </li>

        <li>
          <a class="treeview-item {{Request::segment(2)=='produtoVenda'?'active':null}}"
            href="{{route('home')}}">
            <i class="icon fa fa-circle-o"></i> Produtos Vendidos
          </a>
        </li>

        <li>
          <a class="treeview-item {{Request::segment(2)=='sellers'?'active':null}}" href="{{route('home')}}">
            <i class="icon fa fa-circle-o"></i> Vendedores
          </a>
        </li>

      </ul>
    </li>

    <li>
      <a class="app-menu__item {{Request::segment(1)=='administrators'?'active':null}}" href="{{route('administrators.index')}}">
        <i class="app-menu__icon fa fa-user-secret"></i>
        <span class="app-menu__label">Administradores</span>
      </a>
    </li>

  </ul>

</aside>