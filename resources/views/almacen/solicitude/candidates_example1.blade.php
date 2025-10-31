@extends('layouts.admin')
@section('contenido')
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <!-- Header con breadcrumb -->
        <div class="page-header">
            <h1>
                <i class="fa fa-user-tie"></i> Gestión de Candidatos
            </h1>
            <ol class="breadcrumb">
                <li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
                <li class="active">Listado de Candidatos</li>
            </ol>
        </div>

        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="box box-solid box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-users"></i> Panel de Administración de Candidatos
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
                        @endif
                    </div>


                    <!-- Tabla de candidatos -->
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered table-condensed table-hover table-sm">
                            <thead class="thead-dark">
                                <tr>
                                    <th class="text-center">#</th>
                                    <th>Fecha de creación</th>
                                    <th>Cédula</th>
                                    <th>Nombres</th>
                                    <th>Apellidos</th>
                                    <th>Cargo</th>
                                    <th>Foto</th>
                                    <th>Logo</th>
                                    <th class="text-center">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($candidates as $key => $cat)
                                    <tr>
                                        <td class="text-center">{{ $key+1 }}</td>
                                        <td>{{ \Date::parse($cat->created_at)->format('d/F/Y H:i') }}
                                        </td>
                                        <td>{{ $cat->cedula }}</td>
                                        <td>{{ $cat->firstname }}</td>
                                        <td>{{ $cat->lastname }}</td>
                                        <td><b>{{ $cat->position }}</b></td>
                                        <td class="text-center">
                                            <img src="{{ $cat->photo }}" width="100">
                                        </td>
                                        <td class="text-center">
                                            <img src="{{ $cat->logo }}" width="100">
                                        </td>
                                        <td class="text-center">
                                            @if($admin)
                                                <a href="javascript:void(0);" data-id="{{ $cat->id }}"
                                                    class="btn py-0 px-1" title="Editar" onclick="editCandidate(this)">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <!-- Paginación -->
                    <div class="text-center mt-3">
                        {{ $candidates->appends(request()->input())->render() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@include('almacen.solicitude.new_candidate')

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#liGeneracionInterna').addClass("treeview active");
            $('#liCandidates').addClass("active");
        });

        // Funcionalidad para editar candidato
        function editCandidate(element) {
            let candidateId = element.getAttribute('data-id');

            // Mostrar modal de edición
            $('#modal-candidate').modal('show');
        });
    </script>
@endpush
@endsection