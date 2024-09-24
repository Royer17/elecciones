@extends ('layouts.admin')
@section ('contenido')
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h3 class="font-bold">Tipos de atención {{-- <a href="/admin/tipo-de-atencion/create"><button class="btn btn-success">Nuevo</button></a> --}}</h3>
		@include('almacen.order_type.search')
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive table_solicitud mt-4">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead class="thead-dark">
					<th>#</th>
					<th>Nombre</th>
					<th>Opciones</th>
				</thead>
               @foreach ($order_types as $key => $order_type)
				<tr>
					<td>{{ $key+1}}</td>
					<td>{{ $order_type->name}}</td>
					<td>
						<a href="{{URL::action('OrderTypeController@edit',$order_type->id)}}"><button class="btn btn-info">Editar</button></a>
                         <a href="" data-target="#modal-delete-{{$order_type->id}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a>
					</td>
				</tr>
				@include('almacen.order_type.modal')
				@endforeach
			</table>
		</div>
		{{$order_types->render()}}
	</div>
</div>
@push ('scripts')
<script>
$('#liAcceso').addClass("treeview active");
$('#liOrderType').addClass("active");
</script>
@endpush
@endsection
