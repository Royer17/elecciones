@extends ('layouts.admin')
@section ('contenido')

@push ('scripts')
<style type="text/css">
	/* Chrome, Safari, Edge, Opera */
	input::-webkit-outer-spin-button,
	input::-webkit-inner-spin-button {
	  -webkit-appearance: none;
	  margin: 0;
	}

	/* Firefox */
	input[type=number] {
	  -moz-appearance: textfield;
	}
</style>
@endpush

<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h3>Listado de Artículos <a href="articulo/create"><button class="btn btn-success">Nuevo</button></a> <a href="{{url('reportearticulos')}}" target="_blank"><button class="btn btn-info">Reporte</button></a></h3>
		@include('almacen.articulo.search')

        <input name="_token" value="{{ csrf_token() }}" id="token" type="hidden"></input>

		{{-- Import --}}
		<div class="form-group">
			<div class="input-group">
				<input type="file" name="" class="form-control" id="file">
				<span class="input-group-btn">
					<button class="btn btn-primary" id="import">Importar</button>
				</span>
			</div>
		</div>

		{{-- /Import --}}

	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead>
					<th>Id</th>
					<th>Nombre</th>
					<th>Código</th>
					<th>Categoría</th>
					<th>Stock</th>
					<th>Imagen</th>
					<th>Estado</th>
					<th>Opciones</th>
				</thead>
               @foreach ($articulos as $art)
				<tr>
					<td>{{ $art->idarticulo}}</td>
					<td>{{ $art->nombre}}</td>
					<td>{{ $art->codigo}}</td>
					<td>{{ $art->categoria}}</td>
					<td>{{ $art->stock}}</td>
					<td>
						<img src="{{asset($art->imagen)}}" alt="{{ $art->nombre}}" height="100px" width="100px" class="img-thumbnail">
					</td>
					<td>{{ $art->estado}}</td>
					<td>
						<a href="{{URL::action('ArticuloController@edit',$art->idarticulo)}}"><button class="btn btn-info">Editar</button></a>
                         <a href="" data-target="#modal-delete-{{$art->idarticulo}}" data-toggle="modal"><button class="btn btn-danger">Eliminar</button></a>
					</td>
				</tr>
				@include('almacen.articulo.modal')
				@endforeach
			</table>
		</div>
		<div class="btn-toolbar" role="toolbar">
			<div class="btn-group" role="group">
			  	<form method="GET" action="{{ route('almacen.articulo.index') }}" accept-charset="UTF-8" autocomplete="off" role="search" style="display: inline-block; padding-top: 20px" id="to-page-form">
					<button type="submit" class="btn btn-primary">Ir a</button>
					<input type="number" class="form-control" name="page" value="" style="width: 60px;padding-left: 6px;padding-right: 6px;" placeholder="página">
					<input type="hidden" name="searchText" id="hidden-search">
				</form>
			</div>
			<div class="btn-group" role="group" aria-label="...">
		  		{{--$articulos->render()--}}
		  		{{$articulos->appends(request()->input())->links()}}
			</div>
		</div>
	</div>
</div>
@push ('scripts')
<script>
$('#liAlmacen').addClass("treeview active");
$('#liArticulos').addClass("active");

$('#to-page-form').on('submit', function () {
	var search = $('#input-search').val();
	if (search) {
		$('#hidden-search').val(search);
	}
})
</script>
<script type="text/javascript" src="{{ URL::asset('/plugins/axios/axios.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/plugins/jszip/jszip.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('/plugins/xlsx/xlsx.js') }}"></script>
<script src="/js/import.js">

</script>
@endpush
@endsection