@extends('layouts.admin')
@section('contenido')
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <!-- Header con breadcrumb -->
        <div class="page-header">
            <h1>
                <i class="fa fa-user-graduate"></i> Gestión de Estudiantes Registrados
            </h1>
            <ol class="breadcrumb">
                <li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
                <li>&nbsp;>&nbsp;</li>
                <li class="active">Listado de Estudiantes</li>
            </ol>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="box box-solid box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-list-alt"></i> Panel de Administración de Estudiantes
                        </h3>
                    </div>
                    <div class="box-body">
                        <!-- Mensajes de sesión -->
                        @if(session()->has('data'))
                            <div class="alert alert-warning alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                {{ session()->get('data')[0] }}
                            </div>
                    </div>
                    @endif

                    <!-- Filtros y búsqueda -->
                    <div class="card mb-4">
                        <!-- <div class="card-header py-2">
                            <h4 class="card-title">
                                <i class="fa fa-filter"></i> Filtros de Búsqueda
                            </h4>
                        </div> -->
                        <div class="card-body py-2">
                            <div class="row align-items-end">
                                <div class="col-6">
                                    {!!
                                    Form::open(array('url'=>'admin/estudiantes-registrados','method'=>'GET','autocomplete'=>'off','role'=>'search'))
                                    !!}
                                    {{ Form::token() }}
                                    <div class="form-group mb-0">
                                        <label class="control-label">Buscar por DNI o Nombres:</label>
                                        <div class="input-group">
                                            <input type="text" class="form-control" name="searchText"
                                                placeholder="Ingrese DNI, nombres o apellidos..."
                                                value="{{ $searchText }}">
                                            <div class="input-group-append">
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="fa fa-search"></i> Buscar
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    {{ Form::close() }}
                                </div>

                                <div class="col-auto">
                                    <div class="form-group mb-0">
                                        <label class="control-label">Importar desde Excel:</label>
                                        <div class="input-group">
                                            <input type="file" class="form-control" id="file">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <a href="/plantilla_importar_estudiantes.xlsx" class="btn btn-secondary"
                                        target="_blank"><i class="fa fa-download"></i> Plantilla</a>
                                </div>

                                <div class="col-auto">
                                    <a href="/admin/registrar-estudiante" class="btn btn-md btn-success"><i
                                            class="fa fa-plus-circle"></i> Nuevo estudiante</a>
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- Fin de filtros y búsqueda -->


                    <!-- Tabla de estudiantes -->
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-condensed table-hover table-sm">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Código</th>
                                    <th>Fecha de creación</th>
                                    <th>DNI</th>
                                    <th>Nombre</th>
                                    <th>Apellido</th>
                                    <th>Nivel</th>
                                    <th class="text-center">Opciones</th>
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
                                        <td>{{ $cat->name }}</td>
                                        <td>{{ $cat->paternal_surname }}</td>
                                        @if($cat->order_type_id == 1)
                                            <td>Primaria</td>
                                        @elseif($cat->order_type_id == 2)
                                            <td>Secundaria</td>
                                        @else
                                            <td class="text-danger">Error</td>
                                        @endif
                                        <td>
                                            <div class="dropdown export_matriculas">
                                                <button type="button" class="btn btn_state dropdown-toggle"
                                                    data-toggle="dropdown">Acción</button>
                                                <div class="dropdown-menu">
                                                    <a class="dropdown-item" data-i="{{ $cat->id }}" data-index="3"
                                                        data-action_text="Retirar" href="javascript:void(0);"
                                                        onclick="changeStatus(this)">Retirar</a>
                                                </div>
                                            </div>

                                        </td>
                                    </tr>
                                    @include('almacen.solicitude.entity')
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="text-center mt-3">
                        {{ $orders->appends(request()->input())->render() }}
                    </div>
                </div>
            </div>
        </div>
        @include('almacen.solicitude.modal')

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
                            notice(error.response.data.title, error.response.data.message,
                                `warning`);
                        });
                });
            </script>
        @endpush
        @endsection