@extends ('layouts.admin')
@section ('contenido')
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<h3>Editar Profesión: {{ $profession->name}}</h3>
			@if (count($errors)>0)
			<div class="alert alert-danger">
				<ul>
				@foreach ($errors->all() as $error)
					<li>{{$error}}</li>
				@endforeach
				</ul>
			</div>
			@endif

		{!!Form::model($profession,['method'=>'PATCH','action'=>['ProfessionController@update',$profession->id]])!!}
                  {{Form::token()}}
                  <div class="form-group">
                  	<label for="name">Nombre</label>
                  	<input type="text" name="name" class="form-control" value="{{$profession->name}}" placeholder="Nombre...">
                  </div>
                  <div class="form-group">
                  	<label for="code">Código</label>
                  	<input type="text" name="code" class="form-control" value="{{$profession->code}}" placeholder="Código...">
                  </div>

                  <div class="form-group">
                        <label for="sigla">SIGLA</label>
                        <input type="text" name="sigla" class="form-control" value="{{$profession->sigla}}" placeholder="SIGLA...">
                  </div>

                  <div class="form-group">
                  	<button class="btn btn-primary" type="submit">Guardar</button>
                        <a href="/admin/profesiones" class="btn btn-danger">Cancelar</a>

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