@extends ('layouts.admin')
@section ('contenido')
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<h3><b>Nuevo Usuario</b></h3>
			@if (count($errors)>0)
			<div class="alert alert-danger">
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
                    <label for="name">Nombre</label>
                    <select class="form-control" name="entity_id">
                        <option value="">Seleccione</option>
                        @foreach($entities as $entity)
                            <option value="{{ $entity['id'] }}">{{ $entity['name'] }} {{ $entity['paternal_surname'] }} {{ $entity['maternal_surname'] }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="username">Username</label>
                    <input id="email" type="text" class="form-control" name="username">
                </div>

                <div class="form-group">
                    <label for="name">Rol</label>
                    <select class="form-control" name="role_id">
                        <option value="">Seleccione</option>
                        <option value="1">Responsable</option>
                        <option value="2">Administrador</option>
                    </select>
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

$('select[name="entity_id"]').select2();
</script>
@endpush
@endsection
