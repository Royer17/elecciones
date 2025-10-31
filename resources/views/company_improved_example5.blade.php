@extends ('layouts.admin')
@section ('contenido')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <!-- Header con navegación -->
            <div class="page-header">
                <h1>
                    <i class="fa fa-cogs"></i> Configuración del Sistema
                </h1>
                <ol class="breadcrumb">
                    <li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
                    <li class="active">Gestión Institucional</li>
                </ol>
            </div>

            {!!Form::model($company,['method'=>'POST','route'=>['company.update'], 'files'=>'true'])!!}
            {{Form::token()}}

            <!-- Tabs para organización -->
            <div class="nav-tabs-custom">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#institucion" data-toggle="tab" aria-expanded="true">
                            <i class="fa fa-building"></i> Institución
                        </a></li>
                    <li><a href="#presidente" data-toggle="tab" aria-expanded="false">
                            <i class="fa fa-user-tie"></i> Presidente APAFA
                        </a></li>
                    <li><a href="#soporte" data-toggle="tab" aria-expanded="false">
                            <i class="fa fa-headset"></i> Soporte
                        </a></li>
                </ul>
                
                <div class="tab-content">
                    <!-- Tab Institución -->
                    <div class="tab-pane active" id="institucion">
                        <div class="row">
                            <div class="col-md-8">
                                <!-- Mensajes de error -->
                                @if (count($errors)>0)
                                <div class="alert alert-danger alert-dismissible">
                                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                    <h4><i class="fa fa-exclamation-triangle"></i> Errores de validación</h4>
                                    <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{$error}}</li>
                                    @endforeach
                                    </ul>
                                </div>
                                @endif

                                <div class="form-group">
                                    <label for="name" class="control-label">
                                        <i class="fa fa-asterisk text-danger"></i> Nombre de la Institución
                                    </label>
                                    <input type="text" name="name" class="form-control" value="{{$company->name}}" placeholder="Nombre oficial de la institución" required>
                                    <span class="help-block text-muted">Campo obligatorio</span>
                                </div>

                                <div class="form-group">
                                    <label for="logo" class="control-label">Logo Institucional</label>
                                    <div class="input-group">
                                        <span class="input-group-addon"><i class="fa fa-image"></i></span>
                                        <input type="file" name="logo" class="form-control" accept="image/*">
                                    </div>
                                    
                                    @if (($company->logo)!="")
                                    <div class="mt-2">
                                            <p class="text-info"><i class="fa fa-picture-o"></i> Logo actual:</p>
                                            <div class="thumbnail" style="max-width: 400px;">
                                                <img src="{{asset($company->logo)}}" class="img-responsive center-block" style="max-height: 200px;">
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <!-- Tab Presidente -->
                        <div class="tab-pane" id="presidente">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="video_url" class="control-label">
                                            <i class="fa fa-asterisk text-danger"></i> Nombres y apellidos
                                        </label>
                                        <input type="text" name="video_url" class="form-control" value="{{$company->video_url}}" placeholder="Nombre completo del presidente" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address" class="control-label">
                                            <i class="fa fa-asterisk text-danger"></i> DNI
                                        </label>
                                        <input type="text" name="address" class="form-control" value="{{$company->address}}" placeholder="Número de documento" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="control-label">
                                            <i class="fa fa-asterisk text-danger"></i> Correo electrónico
                                        </label>
                                        <input type="email" name="email" class="form-control" value="{{$company->email}}" placeholder="correo@institucion.edu.pe" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone_1" class="control-label">
                                            <i class="fa fa-asterisk text-danger"></i> Celular
                                        </label>
                                        <input type="text" name="phone_1" class="form-control" value="{{$company->phone_1}}" placeholder="Número de contacto" required>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tab Soporte -->
                        <div class="tab-pane" id="soporte">
                                <div class="alert alert-info">
                                    <h4><i class="fa fa-info-circle"></i> Información de Contacto</h4>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="small-box bg-aqua">
                                            <div class="inner">
                                                <h3>Luis Pérez</h3>
                                                <p>Técnico de Soporte</p>
                                            </div>
                                            <div class="icon">
                                                <i class="fa fa-user-cog"></i>
                                            </div>
                                            <a href="#" class="small-box-footer">
                                                    <i class="fa fa-arrow-circle-right"></i> Especialista en Sistemas
                                        </a>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="small-box bg-green">
                                        <div class="inner">
                                            <h3>Calana</h3>
                                            <p>Centro de Soporte</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="small-box bg-yellow">
                                            <div class="inner">
                                                <h3>9459459459</h3>
                                            <p>Contacto Directo</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Botones de acción fijos -->
                <div class="box-footer text-center" style="position: sticky; bottom: 0; background: #f4f4f4; padding: 15px; border-top: 1px solid #ddd;">
                    <button class="btn btn-primary btn-lg" type="submit" id="submitBtn">
                            <i class="fa fa-save"></i> <span id="submitText">Guardar Configuración</span>
                </button>
                <button class="btn btn-default btn-lg" type="reset" id="resetBtn">
                            <i class="fa fa-refresh"></i> Restablecer
                </button>
                <a href="" class="btn btn-danger btn-lg" id="cancelBtn">
                            <i class="fa fa-times"></i> Cancelar
                </a>
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

        // Validación por tabs
        $('form').on('submit', function(e) {
            var activeTab = $('.nav-tabs .active a').attr('href');
            var hasErrors = false;

            // Validar campos del tab activo
            $(activeTab + ' input[required]').each(function() {
                var $this = $(this);
                var value = $this.val();
                
                if (!value || !value.trim()) {
                        $this.closest('.form-group').addClass('has-error');
                        hasErrors = true;
                    } else {
                        $this.closest('.form-group').removeClass('has-error').addClass('has-success');
                }
            });

            if (hasErrors) {
                e.preventDefault();
                // Mostrar mensaje de error
                alert('Por favor complete todos los campos requeridos en la pestaña actual');
            } else {
                // Cambiar estado del botón
                $('#submitBtn').prop('disabled', true).addClass('disabled');
                $('#submitText').text('Guardando...');
            }
        });

        // Vista previa del logo
        $('input[name="logo"]').change(function() {
                var input = this;
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                            $('.thumbnail img').attr('src', e.target.result);
                }
            });

        // Navegación entre tabs con validación
        $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                    // Limpiar errores al cambiar de tab
                    $('.has-error').removeClass('has-error');
            });

        // Tooltips para todos los elementos con título
        $('[title]').tooltip({
                placement: 'top',
                trigger: 'hover'
            });
        });
    </script>
@endpush
@endsection