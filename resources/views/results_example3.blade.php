@extends('layouts.admin')
@section('contenido')
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <!-- Header con navegación -->
        <div class="page-header">
            <h1>
                <i class="fa fa-medal"></i> Sistema de Reporte de Resultados Electorales
            </h1>
            <ol class="breadcrumb">
                <li><a href="{{ route('home') }}"><i class="fa fa-home"></i> Inicio</a></li>
                <li class="active">Análisis Completo de Votación</h1>
            </ol>
        </div>

        {!!Form::open(array('method'=>'GET','autocomplete'=>'off','role'=>'search'))!!}
        {{ Form::token() }}

        <!-- Tabs para organización de resultados -->
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#resumen" data-toggle="tab" aria-expanded="true">
                        <i class="fa fa-chart-bar"></i> Resumen General
                    </a></li>
                <li><a href="#detalles" data-toggle="tab" aria-expanded="false">
                        <i class="fa fa-user-check"></i> Detalles por Nivel
                    </a></li>
                <li><a href="#graficos" data-toggle="tab" aria-expanded="false">
                        <i class="fa fa-chart-pie"></i> Gráficos
                    </a></li>
            </ul>

            <div class="tab-content">
                <!-- Tab Resumen -->
                <div class="tab-pane active" id="resumen">
                    <div class="box box-solid box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <i class="fa fa-table"></i> Tabla de Resultados
                            </h3>
                        </div>
                        <div class="box-body">
                            @foreach($results as $result)
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title">
                                            <i class="fa fa-info-circle"></i> Información General del Proceso Electoral
                                        </h4>
                                    </div>
                                    <div class="panel-body">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="info-box bg-green">
                                                    <span class="info-box-icon"><i class="fa fa-users"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Nivel:</span>
                                                        <span
                                                            class="info-box-number">{{ $result['nivel_text'] }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="info-box bg-blue">
                                                    <span class="info-box-text">Total Estudiantes:</span>
                                                    <span
                                                        class="info-box-number">{{ $result['total_students'] }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="info-box bg-yellow">
                                                    <span class="info-box-icon"><i class="fa fa-vote-yea"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Estudiantes que
                                                            votaron:</span>
                                                        <span
                                                            class="info-box-number">{{ $result['total_students_voted'] }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Tab Detalles -->
                                        <div class="tab-pane" id="detalles">
                                            <div class="box box-solid box-success">
                                                <div class="box-header with-border">
                                                    <h3 class="box-title">
                                                        <i class="fa fa-percentage"></i> Porcentaje de
                                                        Participación:</span>
                                                        <span class="info-box-number">
                                                            {{ round(($result['total_students_voted'] / $result['total_students']) * 100, 2) }}%
                                                    </h3>
                                                </div>
                                                <div class="box-body">
                                                    <blade
                                                        foreach|%20(%24result%5B%26%2339%3Bcandidates_results%26%2339%3B%5D%20as%20%24candidate)>
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="chart-container">
                                                                    <canvas
                                                                        id="electionChart{{ $result['nivel'] }}"></canvas>
                                                                </div>
                                                            </div>
                                                        </div>
                                                </div>
                                            </div>

                                            <!-- Tab Gráficos -->
                                            <div class="tab-pane" id="graficos">
                                                <div class="box box-solid box-info">
                                                    <div class="box-header with-border">
                                                        <h3 class="box-title">
                                                            <i class="fa fa-chart-line"></i> Evolución de la Votación
                                                        </h3>
                                                    </div>
                                                    <div class="box-body">
                                                        <div class="row">
                                                            <div class="col-md-6">
                                                                <div class="info-box bg-purple">
                                                                    <span class="info-box-icon"><i
                                                                            class="fa fa-chart-area"></i> Distribución
                                                                        de Votos
                                                                        </h3>
                                                                </div>
                                                                <div class="box-body">
                                                                    <div class="chart">
                                                                        <canvas
                                                                            id="barChart{{ $result['nivel'] }}"></canvas>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="info-box bg-pink">
                                                                        <span class="info-box-icon"><i
                                                                                class="fa fa-chart-bar"></i> Resultados
                                                                            por Candidato
                                                                            </h3>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- Botones de acción -->
                                                        <div class="row mt-4">
                                                            <div class="col-md-12 text-center">
                                                                <button class="btn btn-primary btn-lg" type="button">
                                                                    <i class="fa fa-print"></i> Imprimir Reporte
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        {!!Form::close()!!}
                                    </div>
                                </div>
                        </div>
                        @push('scripts')
                            <script>
                                $(document).ready(function () {
                                    $('#liGeneracionInterna').addClass("treeview active");
                                    $('#liResults').addClass("active");
                                });

                                // Configuración de todos los gráficos
                                <
                                blade foreach | % 20( % 24 results % 20 as % 20 % 24 result) >
                                    var electionChart {
                                        {
                                            %
                                            24 result % 5 B % 26 % 2339 % 3 Bnivel % 26 % 2339 % 3 B % 5 D
                                        }
                                    }
                                Canvas = $('#electionChart{{ $result['nivel'] }}').get(0)
                                    .getContext('2d');

                                var $labels {
                                    {
                                        %
                                        24 result % 5 B % 26 % 2339 % 3 Bnivel % 26 % 2339 % 3 B % 5 D
                                    }
                                } = JSON.parse($('#electionChart{{ $result['nivel'] }}')
                                    .parent().find('input[name="labels"]').val());
                                var $values {
                                    {
                                        %
                                        24 result % 5 B % 26 % 2339 % 3 Bnivel % 26 % 2339 % 3 B % 5 D
                                    }
                                } = JSON.parse($('#electionChart{{ $result['nivel'] }}')
                                    .parent().find('input[name="values"]').val());

                                var electionChart {
                                    {
                                        %
                                        24 result % 5 B % 26 % 2339 % 3 Bnivel % 26 % 2339 % 3 B % 5 D
                                    }
                                } = new Chart(electionChart {
                                        {
                                            %
                                            24 result % 5 B % 26 % 2339 % 3 Bnivel % 26 % 2339 % 3 B % 5 D
                                        }
                                    }
                                    Canvas);

                                electionChart {
                                    {
                                        %
                                        24 result % 5 B % 26 % 2339 % 3 Bnivel % 26 % 2339 % 3 B % 5 D
                                    }
                                }.Bar({
                                            labels: $labels {
                                                {
                                                    %
                                                    24 result % 5 B % 26 % 2339 % 3 Bnivel % 26 % 2339 % 3 B % 5 D
                                                }
                                            },
                                            datasets: [{
                                                    label: 'Votos por Candidato</label>
                                                    data: $values {
                                                        {
                                                            %
                                                            24 result % 5 B % 26 % 2339 % 3 Bnivel % 26 %
                                                                2339 % 3 B % 5 D
                                                        }
                                                    },
                                                    options: {
                                                        responsive: true,
                                                        maintainAspectRatio: true,
                                                        scales: {
                                                            y: {
                                                                beginAtZero: true
                                                            }
                                                        }
                                                    });
                                            });
                            </script>
                        @endpush
                        @endsection