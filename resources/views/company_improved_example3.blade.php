@extends ('layouts.admin')
@section ('contenido')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <!-- Header con breadcrumb -->
            <div class="page-header">
                <h1>
                    <i class="fa fa-cog"></i> Configuración Institucional
                </h1>
                <ol class="breadcrumb">
                    <li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
                    <li>&nbsp;>&nbsp;</li>
                    <li class="active">Datos de la Institución</li>
                </ol>
            </div>

            {!!Form::model($company,['method'=>'POST','route'=>['company.update'], 'files'=>'true', 'class'=>'form-horizontal'])!!}
            {{Form::token()}}

            <!-- Mensajes de error -->
            @if (count($errors)>0)
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4><i class="fa fa-exclamation-circle"></i> Se encontraron los siguientes errores:</h4>
                <ul>
                @foreach ($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
                </ul>
            </div>
            @endif

            <div class="row">
                <!-- Columna izquierda - Datos principales -->
                <div class="col-md-8">
                    <div class="box box-solid box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <i class="fa fa-university"></i> Información Institucional
                            </h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="name" class="col-sm-3 control-label">Nombre:</label>
                                <div class="col-sm-9">
                                    <input type="text" name="name" class="form-control" value="{{$company->name}}" placeholder="Nombre oficial de la institución">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="logo" class="col-sm-3 control-label">Logo:</label>
                                <div class="col-sm-9">
                                    <input type="file" name="logo" class="form-control">
                                    @if (($company->logo)!="")
                                        <div class="mt-2">
                                            <p class="text-info"><i class="fa fa-image"></i> Logo actual:</p>
                                            <img src="{{asset($company->logo)}}" class="img-responsive img-rounded" style="max-height: 150px; border: 2px solid #ddd;">
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Datos del presidente -->
                    <div class="box box-solid box-warning mt-3">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <i class="fa fa-user-tie"></i> Presidente de APAFA
                            </h3>
                        </div>
                        <div class="box-body">
                            <div class="form-group">
                                <label for="video_url" class="col-sm-3 control-label">Nombres:</label>
                                <div class="col-sm-9">
                                    <input type="text" name="video_url" class="form-control" value="{{$company->video_url}}" placeholder="Nombres y apellidos completos">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="address" class="col-sm-3 control-label">DNI:</label>
                                <div class="col-sm-9">
                                    <input type="text" name="address" class="form-control" value="{{$company->address}}" placeholder="Número de documento">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email" class="col-sm-3 control-label">Email:</label>
                                <div class="col-sm-9">
                                    <input type="email" name="email" class="form-control" value="{{$company->email}}" placeholder="correo@institucion.edu.pe">
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="phone_1" class="col-sm-3 control-label">Celular:</label>
                                <div class="col-sm-9">
                                    <input type="text" name="phone_1" class="form-control" value="{{$company->phone_1}}" placeholder="Número de contacto">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna derecha - Información de soporte -->
                <div class="col-md-4">
                    <div class="box box-solid box-success">
                        <div class="box-header with-border">
                            <h3 class="box-title">
                                <i class="fa fa-headset"></i> Soporte Técnico
                            </h3>
                        </div>
                        <div class="box-body">
                            <div class="callout callout-info">
                                <h4><i class="fa fa-info-circle"></i> Información de Contacto</h4>
                            </div>
                            
                            <div class="info-box bg-aqua">
                                <span class="info-box-icon"><i class="fa fa-user-cog"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Técnico</span>
                                    <span class="info-box-number">Luis Pérez</span>
                                </div>
                            </div>

                            <div class="info-box bg-green">
                                <span class="info-box-icon"><i class="fa fa-map-marker-alt"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Ubicación</span>
                                    <span class="info-box-number">Calana</span>
                                </div>
                            </div>

                            <div class="info-box bg-yellow">
                                <span class="info-box-icon"><i class="fa fa-phone"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Contacto</span>
                                    <span class="info-box-number">9459459459</span>
                                </div>
                            </div>

                            <div class="alert alert-warning mt-3">
                                <h4><i class="fa fa-clock"></i> Horario de Atención</h4>
                                    <p class="mb-0">Lunes a Viernes</p>
                                    <p class="mb-0">8:00 AM - 5:00 PM</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Botones de acción -->
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-solid">
                        <div class="box-body text-center">
                            <button class="btn btn-primary btn-lg" type="submit" title="Guardar todos los cambios">
                                <i class="fa fa-save"></i> Guardar Configuración
                            </button>
                            {{--
                            <button class="btn btn-default btn-lg" type="reset" title="Restablecer todos los campos">
                                <i class="fa fa-undo"></i> Limpiar
                            </button>
                            <a href="" class="btn btn-danger btn-lg" title="Cancelar y volver al inicio">
                                <i class="fa fa-times"></i> Cancelar
                            </a>
                            --}}
                        </div>
                    </div>
                </div>
            </div>

            {!!Form::close()!!}
        </div>
    </div>
@push ('scripts')
<script>
    $(document).ready(function() {
        // Activar menú
        $('#liAcceso').addClass("treeview active");
        $('#liEmpresa').addClass("active");

        // Vista previa del logo
        $('input[name="logo"]').change(function() {
            var input = this;
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('.img-rounded').attr('src', e.target.result);
        });

        // Validación en tiempo real
        $('input[type="text"], input[type="email"]').on('blur', function() {
            var $this = $(this);
            var value = $this.val();
            
            if (!value || !value.trim()) {
                    $this.closest('.form-group').addClass('has-error');
                } else {
                    $this.closest('.form-group').removeClass('has-error').addClass('has-success');
                }
            });

        // Confirmación antes de enviar
        $('form').on('submit', function(e) {
                if (!confirm('¿Está seguro de guardar los cambios en la configuración institucional?')) {
                    e.preventDefault();
                }
            });

        // Tooltips
        $('[title]').tooltip();
    });
</script>
@endpush
@endsection