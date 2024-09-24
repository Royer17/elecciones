@extends ('layouts.admin')
@section ('contenido')
<div class="row">
	<div class="col-sm-6">
		<h3 class="font-bold">Nuevo Documento</h3>
		@if (count($errors)>0)
		<div class="alert alert-danger">
			<ul>
			@foreach ($errors->all() as $error)
				<li>{{$error}}</li>
			@endforeach
			</ul>
		</div>
		@endif

		{!!Form::open(array('url'=>'admin/tupa','method'=>'POST','autocomplete'=>'off'))!!}
											{{Form::token()}}
							<div class="form-group">
								<label class="etiqueta" for="name">Título</label>
								<input type="text" name="title" class="form-control" placeholder="Título...">
							</div>

							<div class="form-group">
								<label class="etiqueta" for="code">Email</label>
								<input type="text" name="email" class="form-control" placeholder="Email...">
							</div>
							<div class="form-group">
										<label class="etiqueta" for="sigla">Celular</label>
										<input type="text" name="cellphone" class="form-control" placeholder="Celular...">
							</div>
							<div class="form-group">
										<label class="etiqueta" for="sigla">Teléfono</label>
										<input type="text" name="phone" class="form-control" placeholder="Teléfono...">
							</div>

							<div class="form-group">
								<button class="btn btn-primary" type="submit">Guardar</button>
										<a href="/admin/tupa" class="btn btn-danger">Cancelar</a>
							</div>

		{!!Form::close()!!}
	</div>
</div>
@push ('scripts')
<script>
$('#liAlmacen').addClass("treeview active");
$('#liTupa').addClass("active");
</script>
@endpush
@endsection
