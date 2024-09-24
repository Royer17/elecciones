@extends ('layouts.admin')
@section ('contenido')
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<h3>Editar Usuario: {{ $usuario->name}}</h3>
			@if (count($errors)>0)
			<div class="alert alert-danger">
				<ul>
				@foreach ($errors->all() as $error)
					<li>{{$error}}</li>
				@endforeach
				</ul>
			</div>
			@endif

			{!!Form::model($usuario,['method'=>'PATCH','route'=>['seguridad.usuario.update',$usuario->id]])!!}
                <input type="hidden" name="id" value="{{ $usuario->id }}">
                <input type="hidden" name="entity_id" value="{{ $usuario->entity_id }}">

                <div class="form-group">
                    <label for="name">Nombre</label>
                    <select class="form-control" name="entity_selected" disabled="disabled">
                        <option value="">Seleccione</option>
                        @foreach($entities as $entity)
                            <option value="{{ $entity['id'] }}">{{ $entity['name'] }} {{ $entity['paternal_surname'] }} {{ $entity['maternal_surname'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input id="email" type="text" class="form-control" name="username" value="{{$usuario->email}}">
                </div>

                <div class="form-group">
                    <label for="name">Rol</label>
                    <select class="form-control" name="role_id">
                        <option value="">Seleccione</option>
                        @if($usuario->role_id == 1)
                            <option value="1" selected="selected">Responsable</option>
                            <option value="2">Administrador</option>
                        @else
                            <option value="1">Responsable</option>
                            <option value="2" selected="selected">Administrador</option>
                        @endif
                    </select>
                </div>

                <div class="form-group">
                    <label>Sigla</label>
                    <input type="text" class="form-control" name="sigla" value="{{ $usuario->sigla }}">
                </div>

                <div class="form-group">
                    <label for="password">Password</label>
                    <input id="password" type="password" class="form-control" name="password">
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmar Password</label>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation">
                </div>

                  <div class="form-group">
                    <button class="btn btn-primary" type="submit">Guardar</button>
                    <a href="/seguridad/usuario" class="btn btn-danger">Cancelar</a>
                  </div>
			{!!Form::close()!!}		
            
		</div>
	</div>
@push ('scripts')
<script>
$('#liAcceso').addClass("treeview active");
$('#liUsuarios').addClass("active");

$(`select[name="entity_selected"]`).val($(`input[name="entity_id"]`).val());


</script>
@endpush
@endsection