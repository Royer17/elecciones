@extends('layouts.admin')
@section('contenido')
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <!-- Header con navegación -->
        <div class="page-header">
            <h1>
                <i class="fa fa-user-friends"></i> Sistema de Gestión de Estudiantes
            </h1>
            <ol class="breadcrumb">
                <li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
                <li class="active">Administración de Matrículas</li>
            </ol>
        </div>

        {!!Form::open(array('url'=>'admin/estudiantes-registrados','method'=>'GET','autocomplete'=>'off','role'=>'search'))!!}
        {{ Form::token() }}

        <div class="row">
            <!-- Columna principal -->
            <div class="col-md-8">
                <div class="box box-solid box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-table"></i> Listado Completo de Estudiantes Registrados
                        </h3>
                    </div>
                    <div class="box-body">
                        <!-- Panel de filtros -->
                        <div class="panel panel-default mb-4">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <i class="fa fa-sliders-h"></i> Panel de Filtros Avanzados
                                </h4>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label class="control-label">Buscar estudiant:</label>
                                            <div class="input-group">
                                                <input type="text" class="form-control" name="searchText"
                                                    placeholder="DNI, nombres, apellidos..." value="{{ $searchText }}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="control-label">Filtros de Búsqueda:</label>
                                                <div class="input-group">
                                                    <input type="file" class="form-control" id="file">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tabla de estudiantes -->
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-condensed table-hover">
                                        <thead class="thead-dark">
                                            <tr>
                                                <th class="text-center">#</th>
                                                <th>Código de Matrícula</th>
                                                <th>Fecha de Registro</th>
                                                <th>Documento de Identidad</th>
                                                <th>Nombre Completo</th>
                                                <th class="text-center">Nivel Educativo</th>
                                                <th class="text-center">Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($orders as $key => $cat)
                                                <tr>
                                                    <td class="text-center">{{ $key+1 }}</td>
                                                    <td>{{ $cat->code }}</td>
                                                    <td>{{ \Date::parse($cat->created_at)->format('d/F/Y H:i') }}
                                                    </td>
                                                    <td>{{ $cat->identity_document }}</td>
                                                    <td>{{ $cat->name }} {{ $cat->paternal_surname }}</td>
                                                    <td class="text-center">
                                                        @if($cat->order_type_id == 1)
                                                    <td class="text-success">Primaria</td>
                                                @elseif($cat->order_type_id == 2)
                                                    <td class="text-info">Secundaria</td>
                                                @else
                                                    <td class="text-danger">Estado no definido</td>
                                            @endif
                                            <td class="text-center">
                                                <div class="btn-group-vertical btn-group-xs" role="group">
                                                    <a href="javascript:void(0);" class="btn btn-info btn-xs"
                                                        title="Ver detales del estudiante">
                                                        <i class="fa fa-eye"></i> Ver
                                                    </a>
                                            </td>
                                            </tr>
                                            @include('almacen.solicitude.entity')
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- Columna lateral con herramientas -->
                        <div class="col-md-4">
                            <div class="box box-solid box-info">
                                <div class="box-header with-border">
                                    <h3 class="box-title">
                                        <i class="fa fa-tools"></i> Herramientas de Gestión
                                    </h3>
                                </div>
                                <div class="box-body">
                                    <div class="info-box bg-green">
                                        <span class="info-box-icon"><i class="fa fa-user-check"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Total Estudiantes</span>
                                            <span class="info-box-number">{{ $orders->count() }}</span>
                                        </div>
                                    </div>

                                    <div class="info-box bg-yellow">
                                        <span class="info-box-icon"><i class="fa fa-chart-pie"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Estudiantes Activos</span>
                                            <span class="info-box-number">
                                                {{ $orders->where('order_type_id', 1)->count() + $orders->where('order_type_id', 2)->count() }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="row mt-4">
                        <div class="col-md-12 text-center">
                            <a href="/admin/registrar-estudiante" class="btn btn-success btn-lg">
                                <i class="fa fa-user-plus"></i> Nuevo Estudiante
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {!!Form::close()!!}
        </div>
    </div>
    @push('scripts')
        <script>
            $(document).ready(function () {
                $('#liGeneracionInterna').addClass("treeview active");
                $('#liEnrollment').addClass("active");
            });

            // Funcionalidad para importar estudiantes
            $(`#student__import`).on('click', function (e) {
                e.preventDefault();

                if (!data_imports) {
                    notice("Advertencia", "No se han seleccionado datos para importar", `warning`);
                    return;
                }

                lockWindow();
                axios.post(`/admin/students/importv2`, {
                        nivel: nivel,
                        identity_document: identity_document,
                        surname: surname,
                        name: name
                    })
                    .then((response) => {
                        unlockWindow();
                        notice(response.data.title, response.data.message, `success`);
                        location.reload();
                    })
                    .catch((error) => {
                        unlockWindow();
                        notice(error.response.data.title, error.response.data.message, `warning`);
                    });
            });
        </script>
    @endpush
    @endsection