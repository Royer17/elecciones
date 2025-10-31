@extends ('layouts.admin')
@section ('contenido')
    <div class="row">
        <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
            <!-- Progress indicator -->
            <div class="progress" style="height: 5px; margin-bottom: 20px;">
                <div class="progress-bar progress-bar-primary" role="progressbar" style="width: 100%">
                </div>
            </div>

            {!!Form::model($company,['method'=>'POST','route'=>['company.update'], 'files'=>'true', 'id'=>'companyForm'])!!}
            {{Form::token()}}

            <!-- Sección de datos institucionales -->
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <i class="fa fa-building"></i> Datos de la Institución
                    </h3>
                </div>
                <div class="panel-body">
                    <!-- Mensajes de error mejorados -->
                    @if (count($errors)>0)
                    <div class="alert alert-danger alert-dismissible fade in">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4><i class="fa fa-exclamation-triangle"></i> Por favor corrija los siguientes errores:</h4>
                        <div class="error-list">
                            @foreach ($errors->all() as $error)
                                <div class="error-item">
                                    <i class="fa fa-times-circle text-danger"></i> {{$error}}
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <!-- Campo nombre con validación visual -->
                    <div class="form-group" id="nameGroup">
                <label for="name" class="control-label">
                    <i class="fa fa-asterisk text-danger"></i> Nombre de la Institución
                </label>
                <input type="text" name="name" class="form-control" value="{{$company->name}}" placeholder="Ingrese el nombre oficial de la institución" required>
                <span class="help-block text-muted">Este campo es obligatorio</span>
            </div>

            <!-- Campo logo con preview mejorado -->
            <div class="form-group">
                <label for="logo" class="control-label">Logo Institucional</label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-image"></i></span>
                <input type="file" name="logo" class="form-control" accept="image/*">
                </div>
                @if (($company->logo)!="")
                    <div class="logo-preview mt-2">
                        <p class="text-info"><i class="fa fa-eye"></i> Vista previa del logo actual:</p>
                        <div class="thumbnail" style="max-width: 300px;">
                            <img src="{{asset($company->logo)}}" class="img-responsive center-block" style="max-height: 200px;">
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Sección de datos del presidente -->
        <div class="panel panel-warning mt-3">
            <div class="panel-heading">
                <h4 class="panel-title">
                    <i class="fa fa-user-tie"></i> Datos del Presidente de APAFA
            </h4>
        </div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="video_url" class="control-label">
                            <i class="fa fa-asterisk text-danger"></i> Nombres y apellidos
                </label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-user"></i></span>
                        <input type="text" name="video_url" class="form-control" value="{{$company->video_url}}" placeholder="Nombre completo del presidente" required>
                </div>
                <span class="help-block text-muted">Nombre completo del presidente</span>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="address" class="control-label">
                            <i class="fa fa-asterisk text-danger"></i> DNI
                </label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-id-card"></i></span>
                        <input type="text" name="address" class="form-control" value="{{$company->address}}" placeholder="Número de documento" required>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-2">
        <div class="col-md-6">
            <div class="form-group">
                <label for="email" class="control-label">
                            <i class="fa fa-asterisk text-danger"></i> Correo electrónico
                </label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                        <input type="email" name="email" class="form-control" value="{{$company->email}}" placeholder="correo@ejemplo.com" required>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group">
                <label for="phone_1" class="control-label">
                            <i class="fa fa-asterisk text-danger"></i> Celular
                </label>
                <div class="input-group">
                    <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                        <input type="text" name="phone_1" class="form-control" value="{{$company->phone_1}}" placeholder="Número de contacto" required>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<!-- Sección de soporte técnico -->
<div class="panel panel-success mt-3">
    <div class="panel-heading">
        <h4 class="panel-title">
            <i class="fa fa-headset"></i> Información de Soporte
        </h4>
    </div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-4">
                <div class="info-box bg-aqua">
                    <span class="info-box-icon"><i class="fa fa-user-cog"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Técnico de Soporte</span>
                        <span class="info-box-number">Luis Pérez</span>
                        <span class="info-box-more">Especialista en Sistemas</span>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="info-box bg-green">
                    <span class="info-box-icon"><i class="fa fa-map-marker-alt"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Ubicación</span>
                        <span class="info-box-number">Calana</span>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="info-box bg-yellow">
                        <span class="info-box-icon"><i class="fa fa-phone"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Contacto</span>
                            <span class="info-box-number">9459459459</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Botones de acción con estados -->
    <div class="form-group mt-4 text-center">
        <button class="btn btn-primary btn-lg" type="submit" id="submitBtn">
                            <i class="fa fa-save"></i> <span id="submitText">Guardar Cambios</span>
                    </button>
                    <button class="btn btn-default btn-lg" type="reset" id="resetBtn">
                            <i class="fa fa-refresh"></i> Limpiar
                    </button>
                    <a href="{{route('home')}}" class="btn btn-danger btn-lg" id="cancelBtn">
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

        // Validación en tiempo real
        function validateField($field) {
            var value = $field.val();
            var $group = $field.closest('.form-group');
            
            if (!value || !value.trim()) {
                $group.removeClass('has-success').addClass('has-error');
                return false;
            } else {
                $group.removeClass('has-error').addClass('has-success');
                return true;
            }
        }

        // Validar al perder foco
        $('input[required]').on('blur', function() {
            validateField($(this));
        });

        // Vista previa del logo
        $('input[name="logo"]').change(function() {
            var input = this;
            if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('.logo-preview img').attr('src', e.target.result);
                }

            // Mostrar/ocultar estado de envío
            $('#companyForm').on('submit', function(e) {
                var isValid = true;
                $('input[required]').each(function() {
                        if (!validateField($(this))) {
                            isValid = false;
                        }
                    });

                    if (!isValid) {
                        e.preventDefault();
                        $('html, body').animate({
                            scrollTop: $('.has-error').first().offset().top - 100
                    }, 500);
                } else {
                    // Cambiar texto del botón durante envío
                        $('#submitBtn').prop('disabled', true).addClass('disabled');
                        $('#submitText').text('Guardando...');
                    }
                });

            // Resetear formulario
            $('#resetBtn').on('click', function() {
                $('.form-group').removeClass('has-error has-success');
            });
        });
    </script>
@endpush
@endsection