<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sistema</title>
    <!-- Tell the browser to be responsive to screen width -->
    {{-- <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport"> --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap 3.3.5 -->
    <!-- <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}"> -->
    <!-- Bootstrap v5.1.3 -->
    <link rel="stylesheet" href="{{asset('/css/bootstrap-5/bootstrap.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('css/font-awesome.css')}}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('css/AdminLTE.min.css')}}">
    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="{{asset('css/_all-skins.min.css')}}">
    <link rel="apple-touch-icon" href="{{asset('img/apple-touch-icon.png')}}">
    <link rel="shortcut icon" href="#">
    <link href="{{ URL::asset('/plugins/sweet-alert-v2/sweetalert2.min.css') }}" rel="stylesheet">
    <link href="/plugins/daterangepicker/daterangepicker.css" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('css/admin.css')}}">
    <link rel="stylesheet" href="/plugins/select2/select2-madmin.css">
    <style type="text/css">

    #modal-track table {
      max-width: 960px;
      margin: 10px auto;
    }

    #modal-track caption {
      font-size: 1.6em;
      font-weight: 400;
      padding: 10px 0;
    }

    #modal-track thead th {
      font-weight: 400;
      background: #8a97a0;
      color: #FFF;
    }

    #modal-track tr {
      background: #f4f7f8;
      border-bottom: 1px solid #FFF;
      margin-bottom: 5px;
    }

    #modal-track tr:nth-child(even) {
      background: #e8eeef;
    }

    #modal-track th, td {
      text-align: left;
      padding: 20px;
      font-weight: 300;
    }

    #modal-track tfoot tr {
      background: none;
    }

    #modal-track tfoot td {
      padding: 10px 2px;
      font-size: 0.8em;
      font-style: italic;
      color: #8a97a0;
    }

    </style>

    @stack('styles')

  </head>
  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

      <header class="main-header">

        <!-- Logo -->
        <a href="/admin/solicitudes" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>S</b>T</span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>SisTramites</b></span>
        </a>

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Navegación</span>
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
                    $office = $entity->office->name;
                  }
                }


              @endphp
              <!-- User Account: style can be found in dropdown.less -->
              <!-- <li><div><a href="/admin/nuevas-solicitudes" title="Solicitudes por expirar"><i class="fa fa-search"></i></a><input type="text" name="" placeholder="Documento"></div></li> -->

              <li style="padding-right: 30px; padding-top: 14px;">
                <div class="row search-container">
                  <form action="/admin/ruta-de-solicitud" method="POST" target="_blank">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="text" name="solicitude_id" id="searchbox" placeholder="Documento">
                    <button type="submit" class="styled-link">
                      <i class="fa fa-search"></i>
                    </button>
                  </form>
                </div>
              </li>

              <li><a href="/admin/registrados" title="Solicitudes por "><i class="fa fa-bell"></i>({{ $quantity_requests_to_review }})</a></li>
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <small class="bg-red">Online</small>
                  <span class="hidden-xs">{{ $name }} / {{ $office }}</span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">

                    <p>
                      Sistema de trámites
                    </p>
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
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->

          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header"></li>
            {{--
            <li id="liEscritorio">
              <a href="{{url('home')}}">
                <i class="fa fa-dashboard"></i> <span>Escritorio</span>
              </a>
            </li>
            --}}

            <li id="liSolicitudes">
              <a href="{{url('/admin/solicitudes')}}">
                <i class="fa fa-folder-open"></i> <span>Expedientes</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
<!--                 <li id="liInternal">
                  <a href="#">
                    Internos
                  </a>
                </li>
                <li id="liExternal">
                  <a href="#">
                    Externos
                  </a>
                </li> -->
                <li id="liRegistrated">
                  <a href="/admin/registrados">
                    Registrados
                  </a>
                </li>
                <li id="liReceived">
                  <a href="/admin/recibidos">
                    Recibidos
                  </a>
                </li>
                <li id="liCC">
                  <a href="/admin/de-conocimiento">
                    CC
                  </a>
                </li>
                <!-- <li id="liDerivated">
                  <a href="/admin/derivados">
                    Derivados
                  </a>
                </li> -->
                <hr>
                <li id="liSent">
                  <a href="/admin/enviados">
                    Enviados
                  </a>
                </li>

                <li id="liFinished">
                  <a href="/admin/finalizados">
                    Finalizados
                  </a>
                </li>
              </ul>
            </li>

<!--             <li id="liConsult">
              <a href="#">
                <i class="fa fa-search"></i> <span>Consultar</span>
              </a>
            </li> -->

            <li id="liReports">
              <a href="/admin/reportes">
                <i class="fa fa-signal"></i> <span>Reportes</span>
              </a>
            </li>


            <li id="liSolicitude">
              <a href="{{url('/admin/crear-solicitud')}}">
                <i class="fa fa-building"></i> <span>Crear Solicitud</span>
              </a>
            </li>
            <li id="liMySolicitudeSent">
              <a href="{{url('/admin/mis-solicitudes-enviadas')}}">
                <i class="fa fa-building"></i> <span>Mis Solicitudes Enviadas</span>
              </a>
            </li>

<!--             <li id="liNewSolicitudes">
              <a href="{{url('/admin/nuevas-solicitudes')}}">
                <i class="fa fa-exclamation"></i> <span>Nuevas solicitudes</span>
              </a>
            </li> -->


            {{--
            <li id="liAlmacen" class="treeview">
              <a href="#">
                <i class="fa fa-laptop"></i>
                <span>Almacén</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li id="liArticulos"><a href="{{url('almacen/articulo')}}"><i class="fa fa-circle-o"></i> Artículos</a></li>
                <li id="liCategorias"><a href="{{url('almacen/categoria')}}"><i class="fa fa-circle-o"></i> Categorías</a></li>
              </ul>
            </li>

            <li id="liCompras" class="treeview">
              <a href="#">
                <i class="fa fa-th"></i>
                <span>Compras</span>
                 <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li id="liIngresos"><a href="{{url('compras/ingreso')}}"><i class="fa fa-circle-o"></i> Ingresos</a></li>
                <li id="liProveedores"><a href="{{url('compras/proveedor')}}"><i class="fa fa-circle-o"></i> Proveedores</a></li>
              </ul>
            </li>
            <li id="liVentas" class="treeview">
              <a href="#">
                <i class="fa fa-shopping-cart"></i>
                <span>Ventas</span>
                 <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li id="liVentass"><a href="{{url('ventas/venta')}}"><i class="fa fa-circle-o"></i> Ventas</a></li>
                <li id="liClientes"><a href="{{url('ventas/cliente')}}"><i class="fa fa-circle-o"></i> Clientes</a></li>
              </ul>
            </li>
            --}}

            @if(Auth::user()->role_id == 2)

            <li id="liAcceso" class="treeview">
              <a href="#">
                <i class="fa fa-folder"></i> <span>Administración</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li id="liEmpresa">
                  <a href="{{url('empresa')}}">
                    <i class="fa fa-building"></i> Empresa
                  </a>
                </li>
                <li id="liUsuarios">
                  <a href="{{url('seguridad/usuario')}}">
                    <i class="fa fa-user"></i> Usuarios</a>
                </li>
                <li id="liPersonal">
                  <a href="{{url('/admin/personal')}}">
                    <i class="fa fa-users"></i> Personal</a>
                </li>
                <li id="liProfesiones">
                  <a href="{{url('/admin/profesiones')}}">
                    <i class="fa fa-suitcase"></i> Profesiones
                  </a>
                </li>
                <li id="liOffice">
                  <a href="{{url('/admin/oficinas')}}">
                    <i class="fa fa-university"></i> Oficinas
                  </a>
                </li>


              </ul>
            </li>
            @endif

<!--             <li>
              <a href="https://www.youtube.com/watch?v=Zj0pshSSlEo&list=PLZPrWDz1MolrxS1uw-u7PrnK66DCFmhDR" target="_blank">
                <i class="fa fa-plus-square"></i> <span>Ayuda</span>
                <small class="label pull-right bg-red">PDF</small>
              </a>
            </li>
            <li>
              <a href="{{url('acerca')}}">
                <i class="fa fa-info-circle"></i> <span>Acerca De...</span>
                <small class="label pull-right bg-yellow">IT</small>
              </a>
            </li>
-->
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>





       <!--Contenido-->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">

        <!-- Main content -->
        <section class="content">

          <div class="row">
            <div class="col-md-12">
              <div class="box">
                <div class="box-header with-border">
                  <h3 class="box-title">Sistema de trámites</h3>
                  <div class="box-tools pull-right">
                    <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>

                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
                  </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                  	<div class="row">
	                  	<div class="col-md-12">
		                          <!--Contenido-->
                              @yield('contenido')
		                          <!--Fin Contenido-->
                           </div>
                        </div>

                  		</div>
                  	</div><!-- /.row -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      <!--Fin-Contenido-->


    @include('almacen.solicitude.track')

    <!-- jQuery 2.1.4 -->
    <script src="{{asset('js/jQuery-2.1.4.min.js')}}"></script>

    {{-- moment --}}
    <script src="/plugins/moment/moment.js"></script>

    {{-- DaterangePicker --}}
    <script src="/plugins/daterangepicker/daterangepicker.js"></script>

    {{-- Select2 --}}
    <script src="/plugins/select2/select2.js"></script>


    @stack('scripts')
    <!-- Bootstrap 3.3.5 -->
    <!-- <script src="{{asset('js/bootstrap.min.js')}}"></script> -->
    <script src="{{asset('js/bootstrap-select.min.js')}}"></script>
    <!-- Bootstrap v5.1.3 -->
    <script src="{{asset('/js/bootstrap-5/bootstrap.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('js/app.min.js')}}"></script>
    {{-- Sweet alert 2 --}}
    <script type="text/javascript" src="{{ URL::asset('/plugins/sweet-alert-v2/sweetalert2.min.js') }}"></script>
    {{-- Axios --}}
    <script src="{{ asset('/plugins/axios/axios.js') }}"></script>

    {{-- Jquery BlockUI --}}
    <script type="text/javascript" src="https://malsup.github.io/jquery.blockUI.js"></script>

    {{-- help functions --}}
    <script src="{{ asset('/js/some_functions.js') }}"></script>


  </body>
</html>
