@extends ('layouts.admin')
@section ('contenido')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <!-- Header con breadcrumb -->
        <div class="page-header">
            <h1>
                <i class="fa fa-user-plus"></i> Registrar Nuevo Usuario
        </h1>
        <ol class="breadcrumb">
            <li><a href="{{route('home')}}"><i class="fa fa-home"></i> Inicio</a></li>
            <li><a href="/seguridad/usuario"><i class="fa fa-users"></i> Usuarios</a></li>
            <li class="active">Nuevo Registro</li>
        </ol>
    </div>

    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
            <div class="box box-solid box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-id-card"></i> Información del Nuevo Usuario
            </h3>
        </div>
        <div class="box-body">
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

            {!!Form::open(array('url'=>'seguridad/usuario','method'=>'POST','autocomplete'=>'off'))!!}
            {{Form::token()}}

            <div class="form-group">
                <label for="entity_id" class="control-label">
                    <i class="fa fa-asterisk text-danger"></i> Persona
                </label>
                <select class="form-control select2" name="entity_id" required>
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
                <input id="email" type="text" class="form-control" name="username" placeholder="Ingrese el username">
            </div>

            <div class="form-group">
                <label for="role_id" class="control-label">
                        <i class="fa fa-asterisk text-danger"></i> Rol
                </label>
                <select class="form-control" name="role_id" required>
                    <option value="">Seleccione el rol</option>
                    <option value="1">Responsable</option>
                    <option value="2">Administrador</option>
                </select>
            </div>

            <div class="form-group">
                <label for="password" class="control-label">
                        <i class="fa fa-asterisk text-danger"></i> Password
                </label>
                <input id="password" type="password" class="form-control" name="password" placeholder="Ingrese la contraseña" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation" class="control-label">
                        <i class="fa fa-asterisk text-danger"></i> Confirmar Password
                </label>
                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirme la contraseña" required>
            </div>

            <div class="form-group">
                <button class="btn btn-primary" type="submit">
                        <i class="fa fa-save"></i> Guardar Usuario
                </button>
                <a href="/seguridad/usuario" class="btn btn-danger">Cancelar</a>
            </div>

            {!!Form::close()!!}
        </div>
    </div>
</div>
@push ('scripts')
<script>
    $(document).ready(function() {
        $('#liAcceso').addClass("treeview active");
        $('#liUsuarios').addClass("active");
        
        $('select[name="entity_id"]').select2();
    });
</script>
@endpush
@endsection