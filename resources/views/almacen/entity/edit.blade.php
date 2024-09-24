@extends ('layouts.admin')
@section ('contenido')
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<h3>Editar Personal: {{ $entity->name}}</h3>
			@if (count($errors)>0)
			<div class="alert alert-danger">
				<ul>
				@foreach ($errors->all() as $error)
					<li>{{$error}}</li>
				@endforeach
				</ul>
			</div>
			@endif

	     {!!Form::model($entity,['method'=>'PATCH','action'=>['EntityController@update',$entity->id]])!!}
            {{Form::token()}}

            <div class="form-group">
                  <label for="identity_document">DNI</label>
                  <input type="text" name="identity_document" class="form-control" placeholder="Documento de identidad..." value="{{ $entity->identity_document }}">
            </div>

            <div class="form-group">
                  <label for="name">Nombre</label>
                  <input type="text" name="name" class="form-control" placeholder="Nombre..." value="{{ $entity->name }}">
            </div>
            <div class="form-group">
                  <label for="paternal_surname">Apellido Paterno</label>
                  <input type="text" name="paternal_surname" class="form-control" placeholder="Apellido Paterno..." value="{{ $entity->paternal_surname }}">
            </div>
            <div class="form-group">
                  <label for="maternal_surname">Apellido Materno</label>
                  <input type="text" name="maternal_surname" class="form-control" placeholder="Apellido Materno..." value="{{ $entity->maternal_surname }}">
            </div>

            <div class="form-group">
                  <label for="outstanding">Profesión</label>
                  <select class="form-control" name="profession_id">
                        @foreach($professions as $profession)
                              @if($profession['id'] == $entity['profession_id'])
                                    <option value="{{ $profession['id'] }}" selected="selected">{{ $profession['name'] }}</option>
                              @else
                                    <option value="{{ $profession['id'] }}">{{ $profession['name'] }}</option>
                              @endif
                        @endforeach
                  </select>
            </div>

            <div class="form-group">
                  <label for="cellphone">Celular</label>
                  <input type="text" name="cellphone" class="form-control" placeholder="Celular..." value="{{ $entity->cellphone }}">
            </div>

            <div class="form-group">
                  <label for="email">Email</label>
                  <input type="text" name="email" class="form-control" placeholder="Email..." value="{{ $entity->email }}">
            </div>

            <div class="form-group">
                  <label for="address">Dirección</label>
                  <input type="text" name="address" class="form-control" placeholder="Dirección..." value="{{ $entity->address }}">
            </div>

            <div class="form-group">
                  <label for="office_id">Oficina a la que pertenece</label>
                  <select class="form-control" name="office_id" {{ $disabled }}>
                        <option value="0">No tiene</option>
                        @foreach($offices as $office)
                              @if($office->id == $entity['office_id'])
                              <option value="{{ $office->id }}" selected="selected">{{ $office->name }}</option>
                              @else
                              <option value="{{ $office->id }}">{{ $office->name }}</option>
                              @endif
                        @endforeach
                  </select>
            </div>

            <div class="form-group">
            	<button class="btn btn-primary" type="submit">Guardar</button>
                  <a href="/admin/personal" class="btn btn-danger">Cancelar</a>
            </div>

		{!!Form::close()!!}

		</div>
	</div>
@push ('scripts')
<script>
$('#liAlmacen').addClass("treeview active");
$('#liCategorias').addClass("active");
</script>
@endpush
@endsection