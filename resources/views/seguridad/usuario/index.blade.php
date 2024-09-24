@extends ('layouts.admin')
@section ('contenido')
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h3 class="font-bold">Listado de Usuarios {{-- <a href="usuario/create"><button class="btn btn-success">Nuevo</button></a> --}} </h3>
		@include('seguridad.usuario.search')
	</div>
</div>


<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive table_solicitud mt-4">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead class="thead-dark">
					<th>Id</th>
					<th>Nombre</th>
					<th>Username</th>
					<th>Estado</th>
					<th>Opciones</th>
				</thead>
               @foreach ($usuarios as $key => $usu)
				<tr>
					<td>{{ $key + 1}}</td>
					<td>{{ $usu->entity_name}} {{ $usu->entity_paternal_surname }} {{ $usu->entity_maternal_surname }}</td>
					<td>{{ $usu->user_name}}</td>
					<td>{{ $usu->activated ? 'Activo' : 'No activo'}}</td>
					<td>
						<a href="{{URL::action('UsuarioController@edit',$usu->user_id)}}"><button class="btn btn-info">Editar</button></a>
						@if($usu->activated)
                         	<a href="" data-index="{{ $usu->user_id}}" class="btn btn-danger user-suspend">Suspender</a>
                        @else
							<a href="" data-index="{{ $usu->user_id}}" class="btn btn-success user-active">Activar</a>
                        @endif
					</td>
				</tr>
				@include('seguridad.usuario.modal')
				@endforeach
			</table>
		</div>
		{{$usuarios->render()}}
	</div>
</div>
@push ('scripts')
<script>
$('#liAcceso').addClass("treeview active");
$('#liUsuarios').addClass("active");


$(`.user-suspend`).on('click', function(E){
	E.preventDefault();
	let that = $(this);
	let user_id = that[0].dataset.index;

	Swal.fire({
	  title: '¿Está seguro?',
	  text: "Va a suspender al usuario",
	  icon: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: 'Sí!',
	  cancelButtonText: 'No!'
	}).then((result) => {
	  if (result.value) {
	  	lockWindow();
	  	axios.put(`/admin/user/${user_id}/suspend`)
	  		.then((response) => {
	  			unlockWindow();
				location.reload();
	  		})
	  		.catch((err) => {
	  			console.log(err);
	  		});
	  }
	});
});

$(`.user-active`).on('click', function(E){
	E.preventDefault();
	let that = $(this);
	let user_id = that[0].dataset.index;

	Swal.fire({
	  title: '¿Está seguro?',
	  text: "Va a activar al usuario",
	  icon: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: 'Sí!',
	  cancelButtonText: 'No!'
	}).then((result) => {
	  if (result.value) {
	  	lockWindow();
	  	axios.put(`/admin/user/${user_id}/active`)
	  		.then((response) => {
	  			unlockWindow();
				location.reload();
	  		})
	  		.catch((err) => {
	  			console.log(err);
	  		});
	  }
	});
});


</script>
@endpush
@endsection
