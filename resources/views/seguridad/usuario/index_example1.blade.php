@extends ('layouts.admin')
@section ('contenido')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="page-header">
            <h1>
                <i class="fa fa-users"></i> Gestión de Usuarios
            </h1>
            <ol class="breadcrumb">
                <li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
            <li class="active">Listado de Usuarios</li>
            </ol>
        </div>

        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-list"></i> Usuarios del Sistema
                </h3>
            </div>
            
            <div class="box-body">
                <!-- Barra de búsqueda -->
                <div class="row">
                    <div class="col-md-8">
                        @include('seguridad.usuario.search')
                    </div>
                </div>

                <!-- Tabla de usuarios -->
                <div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Nombre Completo</th>
                                <th>Username</th>
                                <th>Estado</th>
                                <th>Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($usuarios as $key => $usu)
                            <tr>
                                <td>{{ $key + 1}}</td>
                                <td>{{ $usu->entity_name}} {{ $usu->entity_paternal_surname }} {{ $usu->entity_maternal_surname }}</td>
                                <td>{{ $usu->user_name}}</td>
                                <td>
                                    @if($usu->activated)
                                        <span class="label label-success">Activo</span>
                                    @else
                                        <span class="label label-danger">Suspendido</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{URL::action('UsuarioController@edit',$usu->user_id)}}">
                                        <button class="btn btn-info btn-sm" title="Editar usuario">
                                            <i class="fa fa-edit"></i> Editar
                                    </button>
                                    </a>
                                    @if($usu->activated)
                                        <a href="" data-index="{{ $usu->user_id}}" class="btn btn-danger btn-sm user-suspend" title="Suspender usuario">
                                        <i class="fa fa-ban"></i> Suspender
                                    </a>
                                    @else
                                        <a href="" data-index="{{ $usu->user_id}}" class="btn btn-success btn-sm user-active" title="Activar usuario">
                                            <i class="fa fa-check"></i> Activar
                                    </a>
                                    @endif
                                </td>
                            </tr>
                            @include('seguridad.usuario.modal')
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Paginación -->
                <div class="text-center mt-3">
                    {{$usuarios->render()}}
                </div>
            </div>
        </div>
    </div>
@push ('scripts')
<script>
    $(document).ready(function() {
        $('#liAcceso').addClass("treeview active");
        $('#liUsuarios').addClass("active");
    
    // Función para suspender usuario
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

    // Función para activar usuario
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