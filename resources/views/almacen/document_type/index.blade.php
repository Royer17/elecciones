@extends ('layouts.admin')
@section ('contenido')
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h3 class="font-bold">Listado de documentos {{-- <a href="/admin/tipos-de-documento/create"><button class="btn btn-success">Nuevo</button></a> --}}</h3>
		@include('almacen.document_type.search')
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive table_solicitud mt-4">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead class="thead-dark">
					<th>Id</th>
					<th>Código</th>
					<th>Nombre</th>
					<th>SIGLA</th>
					<th>Opciones</th>
				</thead>
               @foreach ($document_types as $key => $document_type)
				<tr>
					<td>{{ $key+1}}</td>
					<td>{{ $document_type->code}}</td>
					<td>{{ $document_type->name}}</td>
					<td>{{ $document_type->sigla}}</td>
					<td>
						<a href="{{URL::action('DocumentTypeController@edit',$document_type->id)}}"><button class="btn btn-info">Editar</button></a>
                         <a href="" data-target="#modal-delete-{{$document_type->id}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a>
					</td>
				</tr>
				@include('almacen.document_type.modal')
				@endforeach
			</table>
		</div>
		{{$document_types->render()}}
	</div>
</div>
@push ('scripts')
<script>
$('#liAcceso').addClass("treeview active");
$('#liDocumentType').addClass("active");
</script>
@endpush
@endsection
