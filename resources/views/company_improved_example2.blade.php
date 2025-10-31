@extends ('layouts.admin')
@section ('contenido')
    <div class="row">
        <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
            <!-- Card para datos de la institución -->
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title font-bold">
                        <i class="fa fa-building"></i> Datos de la Institución
                    </h3>
                </div>
                <div class="panel-body">
                    @if (count($errors)>0)
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                        <h4><i class="fa fa-exclamation-triangle"></i> Errores encontrados</h4>
                        <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                        </ul>
                    </div>
                    @endif

                    {!!Form::model($company,['method'=>'POST','route'=>['company.update'], 'files'=>'true'])!!}
                    {{Form::token()}}

                    <div class="row">
                        <div class="col-md-8">
                            <div class="form-group">
                                <label for="name" class="control-label">Nombre de la Institución</label>
                                <input type="text" name="name" class="form-control" value="{{$company->name}}" placeholder="Ingrese el nombre oficial de la institución">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="logo" class="control-label">Logo Institucional</label>
                                <input type="file" name="logo" class="form-control">
                                @if (($company->logo)!="")
                                    <div class="mt-2 text-center">
                                        <p class="text-muted small">Vista previa:</p>
                                        <img src="{{asset($company->logo)}}" class="img-responsive img-thumbnail" style="max-height: 120px;">
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Sección de datos del presidente -->
                    <div class="panel panel-warning mt-4">
                        <div class="panel-heading">
                            <h4 class="panel-title font-bold">
                                <i class="fa fa-user"></i> Datos del Presidente de APAFA
                            </h4>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="video_url" class="control-label">Nombres y apellidos</label>
                                        <input type="text" name="video_url" class="form-control" value="{{$company->video_url}}" placeholder="Apellidos y nombres completos">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="address" class="control-label">DNI</label>
                                        <input type="text" name="address" class="form-control" value="{{$company->address}}" placeholder="Número de documento">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email" class="control-label">Correo electrónico</label>
                                        <input type="email" name="email" class="form-control" value="{{$company->email}}" placeholder="ejemplo@correo.com">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone_1" class="control-label">Celular</label>
                                        <input type="text" name="phone_1" class="form-control" value="{{$company->phone_1}}" placeholder="Número de contacto">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sección de soporte -->
                    <div class="panel panel-success mt-4">
                        <div class="panel-heading">
                            <h4 class="panel-title">Soporte y mantenimiento</h4>
                        </div>
                        <div class="panel-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="well well-sm text-center">
                                        <h5 class="text-primary">Técnico de Soporte</h5>
                                    <p class="lead">Luis Pérez</p>
                                    <small class="text-muted">Especialista en sistemas</small>
                                </div>
                                <div class="col-md-4">
                                    <div class="well well-sm text-center">
                                        <h5 class="text-primary">Ubicación</h5>
                                        <p class="lead">Calana</p>
                                    </div>
                                <div class="col-md-4">
                                    <div class="well well-sm text-center">
                                        <h5 class="text-primary">Contacto</h5>
                                        <p class="lead">9459459459</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botones de acción -->
                    <div class="form-group mt-4 text-center">
                        <button class="btn btn-primary btn-lg" type="submit" title="Guardar cambios">
                            <i class="fa fa-save"></i> Guardar Cambios
                        </button>
                        <button class="btn btn-default btn-lg" type="reset" title="Restablecer formulario">
                            <i class="fa fa-refresh"></i> Limpiar
                        </button>
                    </div>

                    {!!Form::close()!!}
                </div>
            </div>
        </div>
    </div>
@push ('scripts')
<script>
    $(document).ready(function() {
        $('#liAcceso').addClass("treeview active");
        $('#liEmpresa').addClass("active");
        
        // Mostrar vista previa del logo
        $('input[name="logo"]').change(function() {
            var input = this;
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('.img-thumbnail').attr('src', e.target.result);
        });
        
        // Validación de campos requeridos
        $('form').on('submit', function(e) {
            var requiredFields = ['name', 'video_url', 'address', 'email', 'phone_1'];
            var isValid = true;
            
            requiredFields.forEach(function(field) {
                var value = $('input[name="' + field + '"]').val();
                if (!value || !value.trim()) {
                    isValid = false;
                    $('input[name="' + field + '"]').closest('.form-group').addClass('has-error');
                } else {
                    $('input[name="' + field + '"]').closest('.form-group').removeClass('has-error');
                }
            });
            
            if (!isValid) {
                e.preventDefault();
                alert('Por favor complete todos los campos requeridos');
            }
        });
    });
</script>
@endpush
@endsection