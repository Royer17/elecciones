@extends ('layouts.admin')
@section ('contenido')

<div class="row">

	@if (session()->has('data'))
	<div class="col-md-12 text-danger">
		{{ session()->get('data')[0] }}
	</div>
	@endif

	<div class="col-md-12">
		<h3 class="font-bold">Reporte de deudas</h3>
		<div class="row align-items-end">
			<div class="col-sm-auto mb-3">
				<label class="etiqueta">Año:</label><br>
				<input type="hidden" id="is_admin" value="{{ $admin }}">

				<select class="form-control" name="year">
					<option value="2023">2023</option>
					<option value="2024" selected>2024</option>
				</select>
			</div>
			<div class="col-sm-auto mb-3">
				<label class="etiqueta">Nivel:</label><br>
				<select name="order_type_id" class="form-control">
					<option value="">Seleccione</option>
					<option value="1">PRIMARIA</option>
					<option value="2">SECUNDARIA</option>
				</select>
				<!-- <label class="etiqueta">Número documento:</label><br> -->
				<!-- <input type="text" class="form-control" value="{{ $text }}" id="report_code" placeholder="Nro doc."> -->
			</div>
			<div class="col-sm-auto mb-3">
				<label class="etiqueta">Grado:</label>
				<select class="form-control" name="tupa_id">
					<option value="">Seleccione</option>
				</select>
				<!-- <button class="btn btn-primary" id="report_search">Buscar</button> -->
				<!-- <button class="btn btn-success" id="report_reset">Reset</button> -->
			</div>

			<div class="col-sm-auto mb-3">
				<label class="etiqueta">Sección:</label>
				<select class="form-control" name="subject">
					<option value="">Seleccione</option>
				</select>
			</div>




			<div class="col-sm-auto mb-3">
				<label class="etiqueta">Exportar en:</label><br>
				<button class="btn btn-primary" id="solicitudes_report">Excel</button>
				<!-- <a href="/admin/solicitudes-report-code-pdf?oficina={{ $office_id }}&text={{ $text }}" class="btn btn-primary" target="_blank">PDF</a> -->
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive table_solicitud mt-4">
			<table class="table table-striped table-bordered table-condensed table-hover table-sm" id="report-datatable" width="100%">
				<thead class="thead-dark">
					<th>#</th>
					<th>Año</th>
					<th>Código</th>
					<th>Fecha de creación</th>
					<th>DNI</th>
					<th>Nombre</th>
					<th>Apellido paterno</th>
					<th>Apellido materno</th>
					<th>Nivel</th>
					<th>Grado</th>
					<th>Sección</th>
					<th>Pagado</th>
					<th>Deuda</th>
				</thead>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</table>
		</div>
	</div>
</div>
@include('almacen.solicitude.modal')
@include('almacen.solicitude.extorno')


@push ('scripts')
<script>

$('#liReports').addClass("treeview active");
$('#liReportCode').addClass("active");


// document.querySelector('#report_search')
// 	.addEventListener('click', (e) => {
// 		e.preventDefault();
// 		location.replace(`/admin/reportes-codigo?oficina=${document.querySelector('#report_office').value}&text=${document.querySelector('#report_code').value}`);
// 	});


// document.querySelector('#report_reset')
// 	.addEventListener('click', (e) => {
// 		e.preventDefault();
// 		location.replace(`/admin/reportes-codigo`);
// 	});

$('#report_office').select2({
	width: 500
});

</script>
<script type="text/javascript" src="/js/solicitudes.crud.js"></script>
<script type="text/javascript" src="/js/report_code.js"></script>

@endpush
@endsection
