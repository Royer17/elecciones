@extends ('layouts.admin')
@section ('contenido')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <!-- Header con breadcrumb -->
        <div class="page-header">
            <h1>
                <i class="fa fa-user-shield"></i> Administración de Usuarios
        </h1>
        <ol class="breadcrumb">
            <li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
            <li>&nbsp; > &nbsp;</li>
            <li class="active">Gestión de Accesos</li>
        </ol>
    </div>

    <div class="row">
        <!-- Columna principal -->
        <div class="col-md-8">
            <div class="box box-solid box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-table"></i> Listado de Usuarios Registrados
                    </h3>
                </div>
                <div class="box-body">
                    <!-- Barra de búsqueda -->
                    <div class="row">
                        <div class="col-md-12">
                            @include('seguridad.usuario.search')
                        </div>
                    </div>

                    <!-- Tabla de usuarios -->
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-condensed table-hover">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Información Personal</th>
                                    <th>Credenciales</th>
                                    <th>Estado</th>
                                    <th class="text-center">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($usuarios as $key => $usu)
                                <tr>
                                    <td class="text-center">{{ $key + 1}}</td>
                                    <td>
                                        <strong>{{ $usu->entity_name}} {{ $usu->entity_paternal_surname }} {{ $usu->entity_maternal_surname }}</td>
                                    <td>{{ $usu->user_name}}</td>
                                    <td class="text-center">
                                    @if($usu->activated)
                                        <span class="label label-success">Activo</span>
                                    @else
                                        <span class="label label-danger">Suspendido</td>
                                    @endif
                                    </td>
                                    <td class="text-center">
                                        <div class="btn-group" role="group">
                                            <a href="{{URL::action('UsuarioController@edit',$usu->user_id)}}">
                                        <button class="btn btn-info btn-xs" title="Editar información del usuario">
                                            <i class="fa fa-edit"></i> Editar
                                    </button>
                                    </a>
                                    @if($usu->activated)
                                        <a href="" data-index="{{ $usu->user_id}}" class="btn btn-danger btn-xs user-suspend" title="Suspender acceso del usuario">
                                            <i class="fa fa-lock"></i>
                                        </a>
                                        @else
                                        <a href="" data-index="{{ $usu->user_id}}" class="btn btn-success btn-xs user-active" title="Activar usuario">
                                            <i class="fa fa-unlock"></i>
                                        </a>
                                        @endif
                                    </div>
                                    </td>
                                </tr>
                                @include('seguridad.usuario.modal')
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="box-footer clearfix">
                        {{$usuarios->render()}}
                    </div>
                </div>
            </div>
        </div>

        <!-- Columna lateral con estadísticas -->
        <div class="col-md-4">
            <div class="box box-solid box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-chart-bar"></i> Estadísticas
                    </h3>
                </div>
                <div class="box-body">
                    <div class="info-box bg-green">
                        <span class="info-box-icon"><i class="fa fa-user-check"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Usuarios Activos</span>
                                <span class="info-box-number">
                                    {{ $usuarios->where('activated', true)->count() }}
                                </span>
                                <span class="info-box-more">Total del sistema</span>
                            </div>
                    </div>

                    <div class="info-box bg-yellow">
                        <span class="info-box-icon"><i class="fa fa-user-slash"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Usuarios Suspendidos</span>
                        <span class="info-box-number">
                            {{ $usuarios->where('activated', false)->count() }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!-- Botones de acción -->
<!-- <div class="row mt-3">
    <div class="col-md-12 text-center">
        <a href="usuario/create" class="btn btn-success btn-lg">
                    <i class="fa fa-plus-circle"></i> Nuevo Usuario
                </a>
            </div>
        </div>
    </div>
</div> -->
@push ('scripts')
<script>
    $(document).ready(function() {
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