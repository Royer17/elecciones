@extends ('layouts.admin')
@section ('contenido')

<div class="row">

	@if (session()->has('data'))
	<div class="col-md-12 text-danger">
		{{ session()->get('data')[0] }}
	</div>
	@endif

	<div class="col-md-12">
		<h3 class="font-bold">Búsqueda por fecha</h3>
		<div class="row align-items-end">
			<div class="col-md-3 mb-3">
				<div class="form-group daterangepicker-area mb-0">
			    <label class="etiqueta">Rango de fecha:</label>
					<input type="hidden" name="start_date" value="{{ $start_date }}">
					<input type="hidden" name="end_date" value="{{ $end_date }}">
					<input type="text" class="form-control" name="dates" value="" autocomplete="off">
			  </div>
			</div>
			<div class="col-sm-auto mb-3">
				<label class="etiqueta">Oficina:</label><br>
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
			<div class="col-sm-auto mb-3 d-none">
				<label class="etiqueta">Estado:</label><br>
				<select class="form-control" id="report_status">
						@foreach($document_statuses as $state)
							@if($document_status == $state->id)
								<option value = "{{ $state->id }}" selected>{{ $state->name }}</option>
							@else 
								<option value = "{{ $state->id }}">{{ $state->name }}</option>
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
				<a href="/admin/solicitudes-report-fecha-pdf?inicio={{ $start_date }}&fin={{ $end_date }}&oficina={{ $office_id }}" class="btn btn-primary" target="_blank">PDF</a>
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

									@if(false)
									<a href="" data-index="{{ $cat->id }}" class="btn btn-danger solicitude__delete py-0 px-1" title="Eliminar"><i class="fa fa-trash notPointerEvent"></i></a>
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

								@if(false)
								<a href="" data-index="{{ $cat->id }}" class="btn btn-danger solicitude__delete py-0 px-1" title="Eliminar"><i class="fa fa-trash notPointerEvent"></i></a>
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


@push ('scripts')
<script>

$('#liReports').addClass("treeview active");
$('#liReportDate').addClass("active");

//let startDate = document.querySelector('.daterangepicker-area input[name="start_date"]').value;
//let endDate = document.querySelector('.daterangepicker-area input[name="end_date"]').value;
let startDate = document.querySelector('.daterangepicker-area input[name="start_date"]').value;
let endDate = document.querySelector('.daterangepicker-area input[name="end_date"]').value;

if (startDate) {
   $('input[name="dates"]').val(startDate + ' - ' + endDate);
}

$('input[name="dates"]').daterangepicker({
    locale: {
		format: 'DD/MM/YYYY',
		"applyLabel": "Aplicar",
		"cancelLabel": "Cancelar",
		"fromLabel": "De",
		"toLabel": "Hasta",
		"customRangeLabel": "Personalizado",
		"daysOfWeek": [
		    "Do",
		    "Lu",
		    "Ma",
		    "Mi",
		    "Ju",
		    "Vi",
		    "Sa"
		],
		"monthNames": [
		    "Enero",
		    "Febrero",
		    "Marzo",
		    "Abril",
		    "Mayo",
		    "Junio",
		    "Julio",
		    "Agusto",
		    "Septiembre",
		    "Octubre",
		    "Noviembre",
		    "Diciembre"
		],
    },
    autoUpdateInput: false,

    // startDate: document.querySelector('.daterangepicker-area input[name="start_date"]').value ? document.querySelector('.daterangepicker-area input[name="start_date"]').value : null,
    // endDate: document.querySelector('.daterangepicker-area input[name="end_date"]').value ? document.querySelector('.daterangepicker-area input[name="end_date"]').value : moment(),
 //    maxSpan: {
 //    	"months": 6
	// },
dateLimit: {
    'months': 6,
    'days': -1
}

}, function(start, end) {
	//startDate = start.format('YYYY-MM-DD');
	startDate = start.format('DD/MM/YYYY');
	//endDate = end.format('YYYY-MM-DD');
	endDate = end.format('DD/MM/YYYY');

});

$('input[name="dates"]').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
});

document.querySelector('#report_search')
	.addEventListener('click', (e) => {
		e.preventDefault();
		location.replace(`/admin/reportes?inicio=${startDate}&fin=${endDate}&oficina=${document.querySelector('#report_office').value}`);
	});


document.querySelector('#report_reset')
	.addEventListener('click', (e) => {
		e.preventDefault();
		location.replace(`/admin/reportes`);
	});

document.querySelector('#solicitudes_report')
	.addEventListener('click', () => {
		window.open(`/admin/reporte?oficina=${document.querySelector('#report_office').value}&inicio=${startDate}&fin=${endDate}`);
	});

$('#report_office').select2({
	width: 450
});

</script>
<script type="text/javascript" src="/js/solicitudes.crud.js"></script>

@endpush
@endsection