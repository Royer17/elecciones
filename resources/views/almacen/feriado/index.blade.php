@extends ('layouts.admin')
@section ('contenido')
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h3 class="font-bold">Feriados {{-- <a href="/admin/feriados/create"><button class="btn btn-success">Nuevo</button></a> --}}</h3>
		@include('almacen.feriado.search')
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive table_solicitud mt-4">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead class="thead-dark">
					<th>#</th>
					<th>Descripción</th>
					<th>Fecha</th>
					<th>Anual</th>
					<th>Opciones</th>
				</thead>
               @foreach ($feriados as $key => $feriado)
				<tr>
					<td>{{ $key+1}}</td>
					<td>{{ $feriado->description}}</td>
					@if(!$feriado->anual)					
						<td>{{ \Carbon\Carbon::parse($feriado->date)->format('d/m/Y') }}</td>
					@else
						<td>{{ $feriado->date_string }}</td>
					@endif
					<td>{{ $feriado->anual ? "Sí" : "No"}}</td>
					<td>
						<a href="{{URL::action('FeriadosController@edit',$feriado->id)}}"><button class="btn btn-info">Editar</button></a>
                         <a href="" data-target="#modal-delete-{{$feriado->id}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a>
					</td>
				</tr>
				@include('almacen.feriado.modal')
				@endforeach
			</table>
		</div>
		{{$feriados->appends(request()->input())->render()}}
	</div>
</div>
@push ('scripts')
<script>
$('#liAcceso').addClass("treeview active");
$('#liFeriados').addClass("active");
</script>
@endpush
@endsection
