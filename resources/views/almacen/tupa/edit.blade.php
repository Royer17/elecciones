@extends ('layouts.admin')
@section ('contenido')
<h3 class="font-bold">Editar Procedimiento: {{ $tupa->title}}</h3>
@if (count($errors)>0)
<div class="alert alert-danger">
	<ul>
	@foreach ($errors->all() as $error)
		<li>{{$error}}</li>
	@endforeach
	</ul>
</div>
@endif

<div class="row">
	<div class="col-sm-6">
		<div class="card">
			<div class="card-header text-white bg-primary"><h5>Datos de Información</h5></div>
			<div class="card-body bg-light">
				{!!Form::model($tupa,['method'=>'PATCH','action'=>['TupaController@update',$tupa->id]])!!}
	                  {{Form::token()}}
	                  <input type="hidden" name="id" value="{{ $tupa->id }}">
	            	<div class="form-group">
	            	<label class="etiqueta" for="name">Título</label>
	            	<input type="text" name="title" class="form-control" value="{{$tupa->title}}" placeholder="Título...">
	            	</div>
	            	<div class="form-group">
	            		<label class="etiqueta" for="code">Email</label>
	            		<input type="text" name="email" class="form-control" value="{{$tupa->email}}" placeholder="Código...">
	            	</div>

		            <div class="form-group">
		                  <label class="etiqueta" for="sigla">Celular</label>
		                  <input type="text" name="cellphone" class="form-control" value="{{$tupa->cellphone}}" placeholder="Celular...">
		            </div>

		            <div class="form-group">
		                  <label class="etiqueta" for="sigla">Teléfono</label>
		                  <input type="text" name="phone" class="form-control" value="{{$tupa->phone}}" placeholder="Teléfono...">
		            </div>

		            <div class="form-group">
		            	<button class="btn btn-primary" type="submit">Guardar</button>
		                  <a href="/admin/tupa" class="btn btn-danger">Cancelar</a>
		            </div>

				{!!Form::close()!!}
			</div>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="card">
			<div class="card-header text-white bg-primary"><h5>Requisitos</h5></div>
			<div class="card-body bg-light">
				<form method="POST" action="/admin/tupa-requirement" enctype="multipart/form-data">
				{{ csrf_field() }}
				<input type="hidden" name="tupa_id" value="{{ $tupa->id }}">
					<div class="row align-items-end">
						<div class="col-md-5 pr-md-1">
							<div class="form-group mb-1">
								<label class="etiqueta">Nuevo Requisito:</label>
								<input type="text" name="name" class="form-control" value="" placeholder="Nombre...">
							</div>
						</div>
						<div class="col-md px-md-0">
							<div class="form-group mb-1">
								<label class="etiqueta">Archivo:</label>
								<input type="file" name="attached_file" class="form-control file_format">
							</div>
						</div>
						<div class="col-md-auto pl-md-1">
							<div class="form-group mb-1"><button class="btn btn-primary" type="submit">Registrar</button></div>
						</div>
					</div>
				</form>
				<hr class="mb-0">
				@foreach($requirements as $key => $requirement)
				<form method="POST" action="/admin/tupa-requirement/{{ $requirement->id }}" enctype="multipart/form-data">
					<input type="hidden" name="_method" value="PUT">
					{{ csrf_field() }}
					<div class="font-bold mt-2">Requisito {{ $requirement->id }}:</div>
					<div class="row mx-0 pb-3" style="border-bottom: 1px solid #ccc;">
						<div class="col-4 px-0"><input type="text" name="name" class="form-control" value="{{ $requirement->name }}" placeholder="Nombre..."></div>
						<div class="col pl-1 pr-0"><input type="file" name="attached_file" class="form-control-file file_format text-uppercase"></div>
						@if($requirement->link)
						<div class="col-auto pl-1 pr-0"><a href="{{ $requirement->link }}" target="_blank" class="btn btn-info">Archivo</a></div>
						@endif
						<div class="col-auto pl-1 pr-0"><button class="btn btn-success" type="submit">Actualizar</button></div>
						<div class="col-auto pl-1 pr-0"><button type="button" class="btn btn-danger" data-index="{{ $requirement->id }}" onclick="deleteRequirement(this)"><i class="fa fa-times notPointerEvent"></i></button></div>
					</div>
				</form>
				@endforeach
			</div>
		</div>
	</div>
</div>
@push ('scripts')
<script>
$('#liAlmacen').addClass("treeview active");
$('#liTupa').addClass("active");

function deleteRequirement(btn)
{
 	Swal.fire({
	  title: '¿Está seguro?',
	  text: "Va a eliminar el requisito",
	  icon: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: 'Sí!',
	  cancelButtonText: 'No!'
	}).then((result) => {
	  if (result.value) {
	  	lockWindow();

	  	axios.delete(`/admin/tupa-requirement/${btn.dataset.index}`)
	  		.then((response) => {
	  			unlockWindow();
				location.reload();
	  		})
	  		.catch((err) => {
	  			console.log(err);
	  		});
	  }
	});


}

</script>
@endpush
@endsection
