<header id="header_2022" class="main-header header_2022">

  <!-- Logo -->
  <a href="/admin/registrados" class="logo">
    <!-- mini logo for sidebar mini 50x50 pixels -->
    <span class="logo-mini"><b>S</b>V</span>
    <!-- logo for regular state and mobile devices -->
    <span class="logo-lg"><b>SisVotacion</b></span>
  </a>

  <!-- Header Navbar: style can be found in header.less -->
  <nav class="navbar navbar-static-top" role="navigation">
    <!-- Sidebar toggle button-->
    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
      <!-- <span class="sr-only">Navegación</span> -->
    </a>
    <!-- Navbar Right Menu -->
    <div class="navbar-custom-menu">
      <ul class="nav navbar-nav">
        <!-- Messages: style can be found in dropdown.less-->
        @php
          $user = \Auth::user();
          $entity = \sisVentas\Entity::with('office')->whereId($user->entity_id)
            ->first();

          $name = $user->name;
          $office = "";
          if($entity)
          {
            $name = $entity->name." ".$entity->paternal_surname." ".$entity->maternal_surname;

            if($entity->office)
            {
              $office = $entity->office->code;
            }
          }


        @endphp
        <!-- User Account: style can be found in dropdown.less -->
        <!-- <li><div><a href="/admin/nuevas-solicitudes" title="Solicitudes por expirar"><i class="fa fa-search"></i></a><input type="text" name="" placeholder="Documento"></div></li> -->

        <li class="view_search_none">
          <div class="search-container">
            <form action="/admin/ruta-de-solicitud" method="POST" target="_blank">

              <input type="hidden" name="_token" value="{{ csrf_token() }}">
              <div class="input-group width_search_movil">
                <input type="text" class="form-control" name="solicitude_id" id="searchbox" placeholder="Documento">
                <div class="input-group-append">
                  <button class="btn btn-outline-secondary styled-link" type="submit"><i class="fa fa-search"></i></button>
                </div>
              </div>
              <!-- <input type="text" name="solicitude_id" id="searchbox" placeholder="Documento">
              <button type="submit" class="styled-link">
                <i class="fa fa-search"></i>
              </button> -->
            </form>
          </div>
        </li>

        {{-- 
        <li><div class="divisor_menu"></div></li>

        <li>
          <a href="/admin/recibidos" title="Solicitudes por expirar"><i class="fa fa-bell"></i>({{ $orders_received }})</a>
        </li>

        <li><div class="divisor_menu"></div></li>
        --}}
        <li class="dropdown user user-menu">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <div class="login_item">
              <img src="/img/user_icon.png" alt="">
              <p class="hidden_movil_name">
                <span class="name_login">{{ $name }}</span><br>
                {{-- <span class="office_login">{{ $office }}</span><br> --}}
                <span class="bg-red">Online</span>
              </p>
            </div>
            <!-- <small class="bg-red">Online</small>
            <span class="hidden-xs">{{ $name }} / {{ $office }}</span> -->
          </a>
          <ul class="dropdown-menu">
            <!-- User image -->
            <li class="user-header">
              <p class="font-bold">Sistema de trámites</p>
              <div class="login_option">
                <!-- <div class="dropdown-divider"></div>
                <a href="#">Editar Perfil</a> -->
                <div class="dropdown-divider"></div>
                <a href="#" data-target="#modal-password" data-toggle="modal">Cambiar contraseña</a>
                <div class="dropdown-divider"></div>
              </div>
            </li>

            <!-- Menu Footer-->
            <li class="user-footer">

              <div class="pull-right">
                <a href="{{url('/logout')}}" class="btn btn-default btn-flat">Cerrar Sesión</a>
              </div>
            </li>
          </ul>
        </li>

      </ul>
    </div>

  </nav>
</header>
