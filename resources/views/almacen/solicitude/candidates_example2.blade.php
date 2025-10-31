@extends('layouts.admin')
@section('contenido')
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <!-- Header con navegación -->
        <div class="page-header">
            <h1>
                <i class="fa fa-user-tie"></i> Sistema de Gestión de Candidatos
            </h1>
            <ol class="breadcrumb">
                <li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
                <li>&nbsp;>&nbsp;</li>
                <li class="active">Administración de Candidatos</h1>
            </ol>
        </div>

        {!!Form::open(array('method'=>'GET','autocomplete'=>'off','role'=>'search'))!!}
        {{ Form::token() }}

        <div class="row">
            <!-- Columna principal -->
            <div class="col-md-12">
                <div class="box box-solid box-success">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-id-card"></i> Listado Completo de Candidatos Registrados
                        </h3>
                    </div>
                    <div class="box-body">
                        <!-- Panel de información -->
                        <div class="panel panel-info mb-4">
                            <div class="panel-heading">
                                <h4 class="panel-title">
                                    <button type="button" class="btn btn-primary btn-md" id="new_candidate">
                                        <i class="fa fa-plus-circle"></i> Nuevo Candidato
                                    </button>
                                </h4>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table
                                        class="table table-striped table-bordered table-condensed table-hover table-sm">
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
                                                <th class="text-center">Acciones</th>
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
                                                        <a href="javascript:void(0);" data-id="{{ $cat->id }}"
                                                            onclick="editCandidate(this)" class="btn btn-info btn-xs"
                                                            title="Editar candidato">
                                                            <i class="fas fa-pencil-alt"></i> Editar
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {!!Form::close()!!}
            </div>
            {{--
            <!-- Columna lateral con herramientas -->
            <div class="col-md-4">
                <div class="box box-solid box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-tools"></i> Herramientas de Gestión
                        </h3>
                    </div>
                    <div class="box-body">
                        <div class="info-box bg-green">
                            <span class="info-box-icon"><i class="fa fa-user-plus"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Candidatos:</span>
                                <span class="info-box-number">{{ $candidates->count() }}</span>
        </div>
    </div>

    <div class="info-box bg-blue">
        <span class="info-box-icon"><i class="fa fa-chart-pie"></i></span>
        <div class="info-box-content">
            <span class="info-box-text">Candidatos Activos:</span>
            <span class="info-box-number">
                {{ $candidates->where('active', true)->count() }}
            </span>
        </div>
    </div>
</div>
</div>
</div>
--}}

</div>

</div>
</div>
@include('almacen.solicitude.new_candidate')

@push('scripts')
    <script type="text/javascript" src="/js/candidates.js"></script>

    <script>
        $(document).ready(function () {
            $('#liGeneracionInterna').addClass("treeview active");
            $('#liCandidates').addClass("active");
        });

        // Funcionalidad para crear nuevo candidato
        $('#new_candidate').on('click', function () {
            $('#modal-candidate').modal('show');
            $('.update').hide();
            $('.create').show();
        });
    </script>
@endpush
@endsection