@extends ('layouts.admin')
@section ('contenido')

<div class="row">

	@if (session()->has('data'))
	<div class="col-md-12 text-danger">
		{{ session()->get('data')[0] }}
	</div>
	@endif

	<div class="col-md-12">
		<h3 class="font-bold">Búsqueda por tipo de documento</h3>
		<div class="row align-items-end">
			<div class="col-sm-auto mb-3 d-none">
				<label class="etiqueta">Año:</label><br>
				<input type="text" class="form-control" id="report_year" value="{{ $year }}" placeholder="Año">
			</div>

			<div class="col-sm-auto mb-3">
				<label class="etiqueta">Oficina:</label><br>
				<input type="hidden" id="is_admin" value="{{ $admin }}">

				<select class="form-control" id="report_office">
					@if($admin)
						@foreach($offices as $office)
							@if($office->id == $office_id)
								<option value = "{{ $office->id }}" selected>{{ $office->name }}</option>
							@else
								<option value = "{{ $office->id }}">{{ $office->name }}</option>
							@endif
						@endforeach
					@else
						<option value = "{{ $office->id }}">{{ $office->name }}</option>
					@endif
				</select>
			</div>
			<div class="col-sm-auto mb-3">
				<select class="form-control" id="report_document">
						<option value="">Seleccione</option>
						@foreach($document_types as $document_type)
							@if($document_type->id == $document_type_id)
							<option value = "{{ $document_type->id }}" selected>{{ $document_type->name }}</option>
							@else
							<option value = "{{ $document_type->id }}">{{ $document_type->name }}</option>
							@endif
						@endforeach
				</select>
			</div>

			<div class="col-sm-auto mb-3">
				<button class="btn btn-primary" id="report_search">Buscar</button>
				<button class="btn btn-success" id="report_reset">Reset</button>
			</div>
			<div class="col-sm-auto mb-3">
				<label class="etiqueta">Exportar en:</label><br>
				<button class="btn btn-primary" id="solicitudes_report">Excel</button>
				<a href="/admin/solicitudes-report-pdf?oficina={{ $office_id }}&documento={{ $document_type_id }}" class="btn btn-primary" target="_blank">PDF</a>
			</div>
		</div>
	</div>

</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive table_solicitud mt-4">
			<table class="table table-striped table-bordered table-condensed table-hover table-sm">
				<thead class="thead-dark">
					<th>#</th>
					<th>Código</th>
					<th>Fecha Ingreso</th>
					<th>Numeración</th>
					<th>Número</th>
					<th>Asunto</th>
					<th>De:</th>
					<th>Doc. Adjunto</th>
					<th>Opciones</th>
				</thead>
				@php
					$row = 0;
				@endphp
				@foreach ($orders as $key => $cat)
					@if($cat->multiple && !$admin)
						@if($cat->office_id_origen != $current_office_id)
							<tr>
								<td>{{ $row+1}}</td>
								<td>{{ $cat->code}}</td>
								<td>{{ \Date::parse($cat->created_at)->format('d/F/Y H:i') }}</td>
								<td>{{ $cat->internal_code ? strtoupper($cat->internal_code) : $cat->document_type_name }}</td>
								<td>{{ $cat->number}}</td>
								<td>{{ substr($cat->subject, 0, 50)}}...</td>
								<td><a href="" data-toggle="modal" data-target="#modal-entity-{{$cat->id}}"> {{ $cat->name }} {{ $cat->paternal_surname }} {{ $cat->maternal_surname }}</a></td>
								<td>
									@if($cat->attached_file)
									<a href="{{ $cat->attached_file}}" class="btn btn-success py-0 px-1" target="_blank"><i class="fa fa-eye mr-2 notPointerEvent"></i>Ver</a>
									@else
										No archivo adjunto
									@endif
								</td>
								<td>
									<a href="" data-index="{{ $cat->id }}" class="btn btn-primary solicitude__see py-0 px-1" title="Seguimiento"><i class="fa fa-search-plus notPointerEvent"></i></a>

									@if($admin)
										<a href="" data-index="{{ $cat->id }}" class="btn btn-primary solicitude__extorno py-0 px-1" title="Extorno"><i class="fa fa-arrow-down notPointerEvent"></i></a>
									@endif

									@if($admin)
									<a href="" data-index="{{ $cat->id }}" class="btn btn-danger py-0 px-1" title="Eliminar" onclick="deleteRecord(this); return false;"><i class="fa fa-trash notPointerEvent"></i></a>
									@endif
								</td>
							</tr>
							@php
								$row++;
							@endphp
							@include('almacen.solicitude.entity')

						@endif

					@else
						<tr>
							<td>{{ $row+1}}</td>
							<td>{{ $cat->code}}</td>
							<td>{{ \Date::parse($cat->created_at)->format('d/F/Y H:i') }}</td>
							<td>{{ $cat->internal_code ? strtoupper($cat->internal_code) : $cat->document_type_name}}</td>
							<td>{{ $cat->number}}</td>
							<td>{{ substr($cat->subject, 0, 50)}}...</td>
							<td><a href="" data-toggle="modal" data-target="#modal-entity-{{$cat->id}}"> {{ $cat->name }} {{ $cat->paternal_surname }} {{ $cat->maternal_surname }}</a></td>
							<td>
								@if($cat->attached_file)
								<a href="{{ $cat->attached_file}}" class="btn btn-success py-0 px-1" target="_blank"><i class="fa fa-eye mr-2 notPointerEvent"></i>Ver</a>
								@else
									No archivo adjunto
								@endif
							</td>
							<td>
								<a href="" data-index="{{ $cat->id }}" class="btn btn-primary solicitude__see py-0 px-1" title="Seguimiento"><i class="fa fa-search-plus notPointerEvent"></i></a>

								@if($admin)
									<a href="" data-index="{{ $cat->id }}" class="btn btn-primary solicitude__extorno py-0 px-1" title="Extorno"><i class="fa fa-arrow-down notPointerEvent"></i></a>
								@endif

								@if($admin)
								<a href="" data-index="{{ $cat->id }}" class="btn btn-danger py-0 px-1" title="Eliminar" onclick="deleteRecord(this); return false;"><i class="fa fa-trash notPointerEvent"></i></a>
								@endif
							</td>
						</tr>
						@php
							$row++;
						@endphp
						@include('almacen.solicitude.entity')
					@endif
				@endforeach
      </table>
		</div>
		{{$orders->appends(request()->input())->render()}}
	</div>
</div>
@include('almacen.solicitude.modal')
@include('almacen.solicitude.extorno')



@push ('scripts')
<script>

$('#liReports').addClass("treeview active");
$('#liReportDocumentType').addClass("active");


document.querySelector('#report_search')
	.addEventListener('click', (e) => {
		e.preventDefault();
		location.replace(`/admin/reportes-documento?oficina=${document.querySelector('#report_office').value}&documento=${document.querySelector('#report_document').value}`);
	});


document.querySelector('#report_reset')
	.addEventListener('click', (e) => {
		e.preventDefault();
		location.replace(`/admin/reportes-codigo`);
	});

$('#report_office').select2({
	width: 500
});

</script>
<script type="text/javascript" src="/js/solicitudes.crud.js"></script>
<script type="text/javascript" src="/js/report_document.js"></script>
@endpush
@endsection