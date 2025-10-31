@extends ('layouts.admin')
@section ('contenido')
    <div class="row">
        <div class="col-lg-10 col-md-10 col-sm-12 col-xs-12">
        <!-- Header con navegación -->
        <div class="page-header">
            <h1>
                <i class="fa fa-user-plus"></i> Creación de Nuevo Usuario
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('home')}}"><i class="fa fa-home"></i> Inicio</a></li>
            <li><a href="/seguridad/usuario"><i class="fa fa-users"></i> Listado de Usuarios</a></li>
            <li class="active">Nuevo Registro</h1>
        </ol>
    </div>

    {!!Form::open(array('url'=>'seguridad/usuario','method'=>'POST','autocomplete'=>'off'))!!}
    {{Form::token()}}

    <div class="row">
        <!-- Columna principal -->
        <div class="col-md-8">
            <div class="box box-solid box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-user-circle"></i> Datos del Nuevo Usuario
    </h3>
</div>
<div class="box-body">
    <!-- Mensajes de error -->
    @if (count($errors)>0)
    <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4><i class="fa fa-exclamation-circle"></i> Se encontraron errores:</h4>
                    <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{$error}}</li>
                    @endforeach
                    </ul>
                </div>
                @endif

                <div class="form-group">
                    <label for="entity_id" class="control-label">
                            <i class="fa fa-asterisk text-danger"></i> Persona
                    </label>
                    <select class="form-control select2" name="entity_id" required>
                        <option value="">Seleccione una persona del sistema</option>
                        @foreach($entities as $entity)
                            <option value="{{ $entity['id'] }}">{{ $entity['name'] }} {{ $entity['paternal_surname'] }} {{ $entity['maternal_surname'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="username" class="control-label">
                            <i class="fa fa-asterisk text-danger"></i> Username
                    </label>
                    <input id="email" type="text" class="form-control" name="username" placeholder="Ingrese el nombre de usuario" required>
                </div>

                <div class="form-group">
                    <label for="role_id" class="control-label">
                            <i class="fa fa-asterisk text-danger"></i> Rol del Usuario
                    </label>
                    <select class="form-control" name="role_id" required>
                        <option value="">Seleccione el rol</option>
                        <option value="1">Responsable</option>
                        <option value="2">Administrador</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="password" class="control-label">
                            <i class="fa fa-asterisk text-danger"></i> Contraseña
                    </label>
                    <input id="password" type="password" class="form-control" name="password" placeholder="Ingrese la contraseña" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation" class="control-label">
                            <i class="fa fa-asterisk text-danger"></i> Confirmar Contraseña
                    </label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirme la contraseña" required>
                </div>
            </div>
        </div>

        <!-- Columna lateral -->
        <div class="col-md-4">
            <div class="box box-solid box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-info-circle"></i> Información Importante</h4>
        </div>
        <div class="box-body">
            <div class="callout callout-warning">
                    <h4><i class="fa fa-lightbulb"></i> Recomendaciones</h4>
                </div>
                <div class="alert alert-info">
                    <p><i class="fa fa-check"></i> Todos los campos son obligatorios</p>
                <p><i class="fa fa-check"></i> El username debe ser único</p>
                <p><i class="fa fa-check"></i> La contraseña debe tener al menos 6 caracteres</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Botones de acción -->
    <div class="row">
        <div class="col-md-12 text-center">
                <button class="btn btn-primary btn-lg" type="submit">
                            <i class="fa fa-save"></i> Registrar Usuario
                </button>
                <a href="/seguridad/usuario" class="btn btn-danger btn-lg">
                            <i class="fa fa-times"></i> Cancelar
            </a>
        </div>
    </div>
</div>

{!!Form::close()!!}
</div>
</div>
@push ('scripts')
<script>
    $(document).ready(function() {
        $('#liAcceso').addClass("treeview active");
        $('#liUsuarios').addClass("active");
    
    // Inicializar select2
    $('select[name="entity_id"]').select2();
    
    // Validación de campos requeridos
    $('form').on('submit', function(e) {
            var requiredFields = ['entity_id', 'username', 'role_id', 'password', 'password_confirmation'];
            var isValid = true;
            
            requiredFields.forEach(function(field) {
                var value = $('[name="' + field + '"]').val();
                if (!value || !value.trim()) {
                    isValid = false;
                    $('[name="' + field + '"]').closest('.form-group').addClass('has-error');
                } else {
                    $('[name="' + field + '"]').closest('.form-group').removeClass('has-error').addClass('has-success');
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