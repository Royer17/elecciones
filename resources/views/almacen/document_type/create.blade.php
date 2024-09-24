@extends ('layouts.admin')
@section ('contenido')
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<h3>Nuevo Documento</h3>
			@if (count($errors)>0)
			<div class="alert alert-danger">
				<ul>
				@foreach ($errors->all() as $error)
					<li>{{$error}}</li>
				@endforeach
				</ul>
			</div>
			@endif

			{!!Form::open(array('url'=>'admin/tipos-de-documento','method'=>'POST','autocomplete'=>'off'))!!}
                        {{Form::token()}}
            <div class="form-group">
            	<label for="name">Nombre</label>
            	<input type="text" name="name" class="form-control" placeholder="Nombre...">
            </div>

            <div class="form-group">
            	<label for="code">Código</label>
            	<input type="text" name="code" class="form-control" placeholder="Código...">
            </div>
            <div class="form-group">
                  <label for="sigla">Sigla</label>
                  <input type="text" name="sigla" class="form-control" placeholder="Sigla...">
            </div>
		<div class="form-group d-none">
                  <label for="sigla">Empezar con:</label>
                  <input type="text" name="start_with" class="form-control" placeholder="Número de inicio...">
            </div>

		<div class="form-group">
                  <label for="sigla">Tipo:</label>
                  <select name="is_multiple" class="form-control">
                  	<option value="0" selected>Simple</option>
                  	<option value="1">Múltiple</option>
                  </select>
            </div>



            <div class="form-group">
            	<button class="btn btn-primary" type="submit">Guardar</button>
                  <a href="/admin/tipos-de-documento" class="btn btn-danger">Cancelar</a>
            </div>

			{!!Form::close()!!}

		</div>
	</div>
@push ('scripts')
<script>
$('#liAlmacen').addClass("treeview active");
$('#liDocumentType').addClass("active");
</script>
@endpush
@endsection