@extends('layouts.admin')
@section('contenido')
<div class="row">
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <!-- Header con breadcrumb -->
        <div class="page-header">
            <h1>
                <i class="fa fa-user-edit"></i> Editar Usuario: {{ $usuario->name }}
            </h1>
            <ol class="breadcrumb">
                <li><a href=""><i class="fa fa-home"></i> Inicio</a></li>
                <li>&nbsp; > &nbsp;</li>
                <li><a href="/seguridad/usuario"><i class="fa fa-users"></i> Usuarios</a></li>
                <li>&nbsp; > &nbsp;</li>
                <li class="active">Modificar Datos</li>
            </ol>
        </div>

        <div class="row">
            <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
                <div class="box box-solid box-warning">
                    <div class="box-header with-border">
                        <h3 class="box-title">
                            <i class="fa fa-user-cog"></i> Editar Información del Usuario
                        </h3>
                    </div>
                    <div class="box-body">
                        <!-- Mensajes de error -->
                        @if(count($errors)>0)
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                                <h4><i class="fa fa-exclamation-triangle"></i> Errores encontrados:</h4>
                                <ul>
                                    @foreach($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {!!Form::model($usuario,['method'=>'PATCH','route'=>['seguridad.usuario.update',$usuario->id]])!!}
                        {{ Form::token() }}
                        <input type="hidden" name="id" value="{{ $usuario->id }}">
                        <input type="hidden" name="entity_id" value="{{ $usuario->entity_id }}">
                        <input type="hidden" name="entity_selected" value="{{ $usuario->entity_id }}">


                        <div class="form-group">
                            <label for="entity_selected" class="control-label">
                                <i class="fa fa-asterisk text-danger"></i> Persona Asignada
                            </label>
                            <select class="form-control" name="entity_selected" disabled="disabled">
                                <option value="">Seleccione una persona</option>
                                @foreach($entities as $entity)
                                    <option value="{{ $entity['id'] }}">
                                        {{ $entity['name'] }}
                                        {{ $entity['paternal_surname'] }}
                                        {{ $entity['maternal_surname'] }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="username" class="control-label">
                                <i class="fa fa-asterisk text-danger"></i> Username
                            </label>
                            <input id="email" type="text" class="form-control" name="username"
                                value="{{ $usuario->email }}">
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
                            <input id="password" type="password" class="form-control" name="password"
                                placeholder="Deje vacío para mantener la actual">
                        </div>

                        <div class="form-group">
                            <label for="password_confirmation" class="control-label">
                                <i class="fa fa-asterisk text-danger"></i> Confirmar Password
                            </label>
                            <input id="password-confirm" type="password" class="form-control"
                                name="password_confirmation" placeholder="Confirme la contraseña">
                        </div>

                        <div class="form-group">
                            <button class="btn btn-lg btn-primary" type="submit">
                                <i class="fa fa-save"></i> Guardar Cambios
                            </button>
                            <a href="/seguridad/usuario" class="btn btn-lg btn-danger">Cancelar</a>
                        </div>

                        {!!Form::close()!!}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@push('scripts')
    <script>
        $(document).ready(function () {
            $('#liAcceso').addClass("treeview active");
            $('#liUsuarios').addClass("active");

            // Establecer valores iniciales
            $(`select[name="entity_selected"]`).val($(`input[name="entity_id"]`).val());

            $('#liAcceso').addClass("treeview active");
            $('#liUsuarios').addClass("active");

            // Inicializar select2
            $('select[name="entity_id"]').select2();
        });
    </script>
@endpush
@endsection