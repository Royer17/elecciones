@extends ('layouts.admin')
@section ('contenido')
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<h3>Nuevo Tipo de atención</h3>
			@if (count($errors)>0)
			<div class="alert alert-danger">
				<ul>
				@foreach ($errors->all() as $error)
					<li>{{$error}}</li>
				@endforeach
				</ul>
			</div>
			@endif
			
			{!!Form::open(array('url'=>'admin/tipo-de-atencion','method'=>'POST','autocomplete'=>'off'))!!}
                        {{Form::token()}}
		            <div class="form-group">
		            	<label for="name">Nombre</label>
		            	<input type="text" name="name" class="form-control" placeholder="Nombre...">
		            </div>
		            
		            <div class="form-group">
		            	<button class="btn btn-primary" type="submit">Guardar</button>
		                  <a href="/admin/tipo-de-atencion" class="btn btn-danger">Cancelar</a>
		            </div>

			{!!Form::close()!!}

		</div>
	</div>
@push ('scripts')
<script>
$('#liAlmacen').addClass("treeview active");
$('#liOrderType').addClass("active");
</script>
@endpush
@endsection