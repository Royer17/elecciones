@extends ('layouts.admin')
@section ('contenido')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <!-- Header con navegación -->
        <div class="page-header">
            <h1>
                <i class="fa fa-user-cog"></i> Panel de Edición de Usuario
    </h1>
    <ol class="breadcrumb">
        <li><a href="{{route('home')}}"><i class="fa fa-home"></i> Inicio</a></li>
        <li><a href="/seguridad/usuario"><i class="fa fa-users"></i> Listado</a></li>
        <li class="active">Modificar Datos de: {{ $usuario->name}}</h1>
    </ol>
</div>

{!!Form::model($usuario,['method'=>'PATCH','route'=>['seguridad.usuario.update',$usuario->id]])!!}
{{Form::token()}}
<input type="hidden" name="id" value="{{ $usuario->id }}">
</div>

<div class="row">
    <!-- Columna principal -->
    <div class="col-md-8">
        <div class="box box-solid box-warning">
            <div class="box-header with-border">
                <h3 class="box-title">
                    <i class="fa fa-id-card"></i> Información del Usuario
</h3>
</div>
<div class="box-body">
    <!-- Mensajes de error -->
    @if (count($errors)>0)
    <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
            </button>
            <h4><i class="fa fa-exclamation-triangle"></i> Corrija los siguientes errores:</h4>
    <ul>
    @foreach ($errors->all() as $error)
        <li>{{$error}}</li>
    @endforeach
    </ul>
</div>
@endif

<div class="form-group">
    <label for="entity_selected" class="control-label">
                    <i class="fa fa-user"></i> Persona Asignada
    </label>
    <select class="form-control" name="entity_selected" disabled="disabled">
        <option value="">Seleccione una persona</option>
    @foreach($entities as $entity)
        <option value="{{ $entity['id'] }}">{{ $entity['name'] }} {{ $entity['paternal_surname'] }} {{ $entity['maternal_surname'] }}</option>
    @endforeach
    </select>
</div>

<div class="form-group">
    <label for="username" class="control-label">
                            <i class="fa fa-asterisk text-danger"></i> Username
    </label>
    <input id="email" type="text" class="form-control" name="username" value="{{$usuario->email}}">
</div>

<div class="form-group">
    <label for="role_id" class="control-label">
                    <i class="fa fa-asterisk text-danger"></i> Rol del Usuario
    </label>
    <select class="form-control" name="role_id">
        <option value="">Seleccione el rol</option>
    @if($usuario->role_id == 1)
    <option value="1" selected="selected">Responsable</option>
    @else
    <option value="2" selected="selected">Administrador</option>
    @endif
    </select>
</div>

<div class="form-group">
    <label for="password" class="control-label">
                    <i class="fa fa-asterisk text-danger"></i> Password
    </label>
    <input id="password" type="password" class="form-control" name="password" placeholder="Deje vacío para mantener la actual">
</div>

<div class="form-group">
    <label for="password_confirmation" class="control-label">
                    <i class="fa fa-asterisk text-danger"></i> Confirmar Password
    </label>
    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirme la contraseña">
</div>
</div>
</div>

<!-- Columna lateral con información -->
<div class="col-md-4">
    <div class="box box-solid box-info">
        <div class="box-header with-border">
            <h3 class="box-title">
                <i class="fa fa-info-circle"></i> Notas Importantes</h4>
</div>
<div class="box-body">
    <div class="info-box bg-green">
        <span class="info-box-icon"><i class="fa fa-user-check"></i></span>
        <div class="info-box-content">
            <span class="info-box-text">Usuario ID</span>
        <span class="info-box-number">{{ $usuario->id }}</span>
    </div>
</div>
</div>
</div>
</div>

<!-- Botones de acción -->
<div class="row mt-3">
    <div class="col-md-12 text-center">
        <button class="btn btn-primary btn-lg" type="submit">
                    <i class="fa fa-save"></i> Guardar Cambios
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
    
    // Establecer valores iniciales
    $(`select[name="entity_selected"]`).val($(`input[name="entity_id"]`).val());
    
    // Validación del formulario
    $('form').on('submit', function(e) {
            var requiredFields = ['username', 'role_id'];
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