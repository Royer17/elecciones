@extends ('layouts.admin')
@section ('contenido')
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h3 class="font-bold">TUPA
			<!-- <a href="/admin/tupa/create"><button class="btn btn-success">Nuevo</button></a> -->
		</h3>
		@include('almacen.tupa.search')
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive table_solicitud mt-4">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead class="thead-dark">
					<th>#</th>
					<th>Título</th>
					<th>Opciones</th>
				</thead>
               @foreach ($procediments as $key => $procediment)
				<tr>
					<td>{{ $key+1}}</td>
					<td>{{ $procediment->title}}</td>
					<td>
						<a href="{{URL::action('TupaController@edit',$procediment->id)}}"><button class="btn btn-info">Editar</button></a>
                         <a href="" data-target="#modal-delete-{{$procediment->id}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a>
					</td>
				</tr>
				@include('almacen.tupa.modal')
				@endforeach
			</table>
		</div>
		{{$procediments->render()}}
	</div>
</div>
@push ('scripts')
<script>
$('#liAcceso').addClass("treeview active");
$('#liTupa').addClass("active");
</script>
@endpush
@endsection
