<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Sistema de APAFA</title>
    <!-- Tell the browser to be responsive to screen width -->
    {{-- <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport"> --}}
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Bootstrap 3.3.5 -->
    <!-- <link rel="stylesheet" href="{{asset('css/bootstrap.min.css')}}"> -->
    <!-- Bootstrap v4 -->
    <link rel="stylesheet" href="{{asset('/css/bootstrap-4/bootstrap.min.css')}}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('/fonts/fontawesome-6/css/all.min.css')}}">
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
    {{-- Datatables style --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/datatables/1.10.16/css/dataTables.bootstrap.min.css">
    {{-- Datepicker --}}
    <link href="{{ URL::asset('/plugins/datepicker/datepicker.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{asset('css/admin.css')}}">
    <link rel="stylesheet" href="/plugins/select2/select2-madmin.css">

    <link rel="stylesheet" href="{{asset('/css/custom_style.css')}}">

    <style type="text/css">
      .notPointerEvent {
        pointer-events: none;
      }

    </style>

    @stack('styles')
    @yield('custom-css')

  </head>
  <body class="hold-transition skin-blue sidebar-mini body_2022">
    <div class="wrapper">

      @include('layouts.sections.header')

      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->

          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">

            <li class="header"></li>

<!--             <li id="liSolicitudes">
              <a href="{{url('/admin/solicitudes')}}">
                <i class="fa fa-folder-open"></i> <span>Expedientes</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">

                <li id="liRegistrated">
                  <a href="/admin/registrados">
                    Bandeja de entrada ({{ $orders_registrated }})
                  </a>
                </li>
                <li id="liReceived">
                  <a href="/admin/recibidos">
                    Doc. recibidos ({{ $orders_received }})
                  </a>
                </li>
                <li id="liCC">
                  <a href="/admin/de-conocimiento">
                    Doc. conocimiento ({{ $orders_cc }})
                  </a>
                </li>

                <hr>
                <li id="liSent">
                  <a href="/admin/enviados">
                    Derivados ({{ $orders_derivated }})
                  </a>
                </li>

                <li id="liFinished">
                  <a href="/admin/finalizados">
                    Doc. finalizados ({{ $orders_finalized }})
                  </a>
                </li>
              </ul>
            </li>
 -->
<!--             <li id="liReports">
              <a href="/admin/reportes">
                <i class="fa fa-signal"></i> <span>Reportes</span>
              </a>
            </li> -->

            <li id="liReports" class="treeview">
              <a href="#">
                <i class="fa fa-folder"></i> <span>Reportes</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li id="liReportCode">
                  <a href="{{url('/admin/reportes-codigo')}}">
                    <i class="fa fa-building"></i> Deudas y pagos
                  </a>
                </li>
<!--                 <li id="liReportDate">
                  <a href="{{url('/admin/reportes')}}">
                    <i class="fa fa-building"></i> Por fecha
                  </a>
                </li>
                <li id="liReportDocumentType">
                  <a href="{{url('/admin/reportes-documento')}}">
                    <i class="fa fa-building"></i> Por tipo de documento
                  </a>
                </li> -->

              </ul>
            </li>


            <li id="liGeneracionInterna" class="treeview">
              <a href="#">
                <i class="fa fa-folder"></i> <span>Generación interna</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
<!--                 <li id="liSolicitude">
                  <a href="{{url('/admin/crear-solicitud')}}">
                    <i class="fa fa-building"></i> Crear solicitud
                  </a>
                </li> -->
                
                <li id="liRegisterStudent">
                  <a href="{{url('/admin/registrar-estudiante')}}">
                    <i class="fa fa-building"></i> Registrar estudiante
                  </a>
                </li>

                <li id="liEnrollment">
                  <a href="{{url('/admin/estudiantes-registrados')}}">
                    <i class="fa fa-building"></i> Estudiantes registrados
                  </a>
                </li>

                <li id="liRegisterFicha">
                  <a href="{{url('/admin/ficha-de-matricula')}}">
                    <i class="fa fa-building"></i> Ficha de matrícula
                  </a>
                </li>

                <li id="liMySolicitudeSent">
                  <a href="{{url('/admin/lista-de-fichas')}}">
                    <i class="fa fa-building"></i> Lista de fichas
                  </a>
                </li>

              </ul>
            </li>


<!--             <li id="liSolicitude">
              <a href="{{url('/admin/crear-solicitud')}}">
                <i class="fa fa-building"></i> <span>Crear Solicitud</span>
              </a>
            </li>
            <li id="liMySolicitudeSent">
              <a href="{{url('/admin/mis-solicitudes-enviadas')}}">
                <i class="fa fa-building"></i> <span>Mis Solicitudes Enviadas</span>
              </a>
            </li> -->

            @if(Auth::user()->role_id == 2)

            <li id="liAcceso" class="treeview">
              <a href="#">
                <i class="fa fa-folder"></i> <span>Administración</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li id="liEmpresa">
                  <a href="{{url('empresa')}}">
                    <i class="fa fa-building"></i> Institución
                  </a>
                </li>
                <li id="liUsuarios">
                  <a href="{{url('seguridad/usuario')}}">
                    <i class="fa fa-user"></i> Usuarios</a>
                </li>
                <!-- <li id="liPersonal">
                  <a href="{{url('/admin/personal')}}">
                    <i class="fa fa-users"></i> Personal</a>
                </li>
                <li id="liProfesiones">
                  <a href="{{url('/admin/profesiones')}}">
                    <i class="fa fa-suitcase"></i> Profesiones
                  </a>
                </li> -->
                <li id="liOffice" class="">
                  <a href="{{url('/admin/oficinas')}}">
                    <i class="fa fa-fax"></i> Conceptos de pago
                  </a>
                </li>
                <!-- <li id="liDocumentType">
                  <a href="{{url('/admin/tipos-de-documento')}}">
                    <i class="fa fa-file"></i> Tipo de documento
                  </a>
                </li>
                <li id="liTupa">
                  <a href="{{url('/admin/tupa')}}">
                    <i class="fa fa-file"></i> TUPA
                  </a>
                </li>
                <li id="liOrderType">
                  <a href="{{url('/admin/tipo-de-atencion')}}">
                    <i class="fa fa-file"></i> Tipo de atención
                  </a>
                </li>
                <li id="liFeriados">
                  <a href="{{url('/admin/feriados')}}">
                    <i class="fa fa-file"></i> Feriados
                  </a>
                </li> -->


              </ul>
            </li>
            @endif

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
                  <h3 class="box-title">Sistema de APAFA</h3>
                  <div class="box-tools pull-right">
                    <!-- <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                    <button class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button> -->
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
                  	</div><!-- /.row -->
                </div><!-- /.box-body -->
              </div><!-- /.box -->
            </div><!-- /.col -->
          </div><!-- /.row -->

        </section><!-- /.content -->
      </div><!-- /.content-wrapper -->
      <!--Fin-Contenido-->
    </div>

    @include('almacen.solicitude.track')
    @include('seguridad.change_password')

    <!-- jQuery 2.1.4 -->
    <script src="{{asset('js/jQuery-2.1.4.min.js')}}"></script>
    <script src="{{asset('js/jquery-ui.min.js')}}"></script>

    {{-- moment --}}
    <script src="/plugins/moment/moment.js"></script>

    {{-- DaterangePicker --}}
    <script src="/plugins/daterangepicker/daterangepicker.js"></script>


    {{-- DatePicker --}}
    <script src="/plugins/datepicker/bootstrap-datepicker.js"></script>
    <script type="text/javascript" src="{{ URL::asset('/plugins/datepicker/locales/bootstrap-datepicker.es.js') }}"></script>

    {{--Datatable js--}}
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src='https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap.min.js'> </script>


    {{-- Select2 --}}
    <script src="/plugins/select2/select2.js"></script>

    <script type="text/javascript" src="{{asset('/js/jquery.sticky.js')}}"></script>
    <script>
      $(window).load(function(){
        $("#header_2022").sticky({
          topSpacing: 0,
          zIndex: 1030
        });
      });
      $(document).ready(function() {
          $('.select_2').select2();
      });


      document.querySelector('#modal-password .update')
        .addEventListener('click', () => {

          if (!document.querySelector('#modal-password .password').value) {
            notice("Advertencia", "El campo contraseña no puede estar vacío.", `warning`);
            return;
          }

          if (document.querySelector('#modal-password .password').value.length < 6) {
            notice("Advertencia", "La contraseña debe ser mayor o igual a 6 caracteres.", `warning`);
            return;
          }

         if (document.querySelector('#modal-password .password').value != document.querySelector('#modal-password .confirm-password').value) {
            notice("Advertencia", "La contraseña no coincide con la confirmación.", `warning`);
            return;
          }

          $('#change-password-form').submit();

        });

    </script>
    {{-- help functions --}}
    <script src="{{ asset('/js/some_functions.js') }}"></script>
    <!-- axios -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/0.18.0/axios.js"></script>
    @stack('scripts')
    <!-- Bootstrap 3.3.5 -->
    <!-- <script src="{{asset('js/bootstrap.min.js')}}"></script> -->
    <script src="{{asset('js/bootstrap-select.min.js')}}"></script>
    <!-- Bootstrap v4 -->
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="{{asset('/js/bootstrap-4/bootstrap.min.js')}}"></script>
    <!-- AdminLTE App -->
    <script src="{{asset('js/app.min.js')}}"></script>
    {{-- Sweet alert 2 --}}
    <script type="text/javascript" src="{{ URL::asset('/plugins/sweet-alert-v2/sweetalert2.min.js') }}"></script>
    {{-- Axios --}}
    <script src="{{ asset('/plugins/axios/axios.js') }}"></script>
    
    {{-- Jquery BlockUI --}}
    <!-- <script type="text/javascript" src="https://malsup.github.io/jquery.blockUI.js"></script> -->
    <script type="text/javascript" src="{{ URL::asset('/js/jquery.blockUI.js') }}"></script>




  </body>
</html>
