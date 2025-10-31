@extends ('layouts.admin')
@section ('contenido')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <!-- Header con navegación -->
        <div class="page-header">
            <h1>
                <i class="fa fa-user-cog"></i> Panel de Administración de Usuarios
        </h1>
        <ol class="breadcrumb">
            <li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
            <li class="active">Gestión de Accesos y Permisos</h1>
        </ol>
    </div>

    {!!Form::open(['method'=>'GET','url'=>'','class'=>'form-horizontal'])!!}
    {{Form::token()}}

    <!-- Tabs para organización -->
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a href="#activos" data-toggle="tab" aria-expanded="true">
                    <i class="fa fa-user-check"></i> Usuarios Activos
                </a></li>
            <li><a href="#suspendidos" data-toggle="tab" aria-expanded="false">
                            <i class="fa fa-user-slash"></i> Usuarios Suspendidos
                </a></li>
            <li><a href="#estadisticas" data-toggle="tab" aria-expanded="false">
                            <i class="fa fa-chart-pie"></i> Estadísticas
            </a></li>
        </ul>
        
        <div class="tab-content">
            <!-- Tab Usuarios Activos -->
            <div class="tab-pane active" id="activos">
                <div class="box box-solid box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-users"></i> Listado de Usuarios con Acceso Activo
                </h3>
            </div>
            <div class="box-body">
                <!-- Barra de búsqueda -->
                <div class="row">
                    <div class="col-md-8">
                        @include('seguridad.usuario.search')
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-condensed table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th class="text-center">#</th>
                                <th>Información Personal</th>
                                <th>Credenciales</th>
                                <th class="text-center">Estado</th>
                                <th class="text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($usuarios->where('activated', true) as $key => $usu)
                            <tr>
                                <td class="text-center">{{ $key + 1}}</td>
                                <td>
                                    <strong>{{ $usu->entity_name}} {{ $usu->entity_paternal_surname }} {{ $usu->entity_maternal_surname }}</td>
                                <td>{{ $usu->user_name}}</td>
                                <td class="text-center">
                                    <span class="label label-success">Activo</span>
                                </td>
                                <td class="text-center">
                                    <div class="btn-group-vertical btn-group-xs" role="group">
                                        <a href="{{URL::action('UsuarioController@edit',$usu->user_id)}}">
                                    <button class="btn btn-info btn-xs" title="Editar información del usuario">
                                        <i class="fa fa-edit"></i>
                                    </button>
                                    </a>
                                    <a href="" data-index="{{ $usu->user_id}}" class="btn btn-danger btn-xs user-suspend" title="Suspender acceso del usuario">
                                            <i class="fa fa-ban"></i> Suspender
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Tab Usuarios Suspendidos -->
        <div class="tab-pane" id="suspendidos">
                                <div class="box box-solid box-danger">
                                    <div class="box-header with-border">
                                        <h3 class="box-title">
                                            <i class="fa fa-user-slash"></i> Usuarios con Acceso Suspendido
            </h3>
        </div>
        <div class="box-body">
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-condensed table-hover">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>Información Personal</th>
                                                <th>Credenciales</th>
                                                <th class="text-center">Estado</th>
                                                <th class="text-center">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($usuarios->where('activated', false) as $key => $usu)
                                            <tr>
                                                <td class="text-center">{{ $key + 1}}</td>
                                                <td>
                                                    <strong>{{ $usu->entity_name}} {{ $usu->entity_paternal_surname }} {{ $usu->entity_maternal_surname }}</td>
                                                <td>{{ $usu->user_name}}</td>
                                                <td class="text-center">
                                                    <span class="label label-danger">Suspendido</span>
                                                </td>
                                                <td class="text-center">
                                                    <a href="" data-index="{{ $usu->user_id}}" class="btn btn-success btn-xs user-active" title="Activar usuario">
                                                        <i class="fa fa-check-circle"></i> Activar
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Estadísticas -->
                        <div class="tab-pane" id="estadisticas">
                                    <div class="box box-solid box-info">
                                        <div class="box-header with-border">
                                            <h3 class="box-title">
                                                <i class="fa fa-chart-line"></i> Resumen del Sistema
                            </h3>
                        </div>
                        <div class="box-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="info-box bg-green">
                                            <span class="info-box-icon"><i class="fa fa-user-check"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Usuarios Activos</span>
                                                <span class="info-box-number">
                                                    {{ $usuarios->where('activated', true)->count() }}
                                            </span>
                                            <span class="info-box-more">Total: {{ $usuarios->count() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="info-box bg-yellow">
                                            <span class="info-box-icon"><i class="fa fa-user-slash"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Usuarios Suspendidos</span>
                                            <span class="info-box-number">
                                                    {{ $usuarios->where('activated', false)->count() }}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                            <div class="info-box bg-blue">
                                                <span class="info-box-icon"><i class="fa fa-percentage"></i></span>
                                            <div class="info-box-content">
                                                <span class="info-box-text">Porcentaje Activos</span>
                                            <span class="info-box-number">
                                                @if($usuarios->count() > 0)
                                                {{ round(($usuarios->where('activated', true)->count() / $usuarios->count() * 100, 2) }}%
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="box-footer text-center" style="position: sticky; bottom: 0; background: #f4f4f4; padding: 15px; border-top: 1px solid #ddd;">
                <a href="usuario/create" class="btn btn-success btn-lg">
                            <i class="fa fa-user-plus"></i> Registrar Nuevo Usuario
                    </a>
                </div>
            </div>
        </div>
    </div>
@push ('scripts')
<script>
    $(document).ready(function() {
        // Activar menú
        $('#liAcceso').addClass("treeview active");
        $('#liUsuarios').addClass("active");
    });

    // Funcionalidad para suspender usuario
    $(`.user-suspend`).on('click', function(E){
        E.preventDefault();
        let that = $(this);
        let user_id = that[0].dataset.index;

        Swal.fire({
            title: '¿Está seguro?',
            text: "Va a suspender al usuario",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí!',
            cancelButtonText: 'No!'
        }).then((result) => {
            if (result.value) {
                lockWindow();
                axios.put(`/admin/user/${user_id}/suspend`)
                .then((response) => {
                    unlockWindow();
                    location.reload();
                })
                .catch((err) => {
                    console.log(err);
                });
            }
        });
    });

    // Funcionalidad para activar usuario
    $(`.user-active`).on('click', function(E){
            E.preventDefault();
            let that = $(this);
            let user_id = that[0].dataset.index;

            Swal.fire({
                title: '¿Está seguro?',
                text: "Va a activar al usuario",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí!',
                cancelButtonText: 'No!'
            }).then((result) => {
                if (result.value) {
                    lockWindow();
                    axios.put(`/admin/user/${user_id}/active`)
                    .then((response) => {
                        unlockWindow();
                        location.reload();
                    })
                    .catch((err) => {
                        console.log(err);
                    });
                }
            });
        });
    });
</script>
@endpush
@endsection