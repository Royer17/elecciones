{!! Form::open(array('url'=>'admin/solicitudes','method'=>'GET','autocomplete'=>'off','role'=>'search')) !!}
<div id="filters_accordion">
	<div class="card">
		<div class="card-header py-1">
			<a class="collapsed card-link" data-toggle="collapse" href="#filter_1"><div class="title_filters"><label>Filtros</label><i class="fa fa-filter"></i></div></a>
		</div>
		<div id="filter_1" class="collapse" data-parent="#filters_accordion">
			<div class="card-body py-1">
				<div class="row align-items-end">

					<div class="col-md-3 mb-3 pr-md-0">
						<div class="form-group mb-0">
							<label class="etiqueta">Buscar Trámite:</label>
							<input type="text" class="form-control" name="searchText" placeholder="Buscar por código, DNI o RUC..." value="{{$searchText}}">
						</div>
					</div>
					<div class="col-md-3 mb-3 px-md-1">
						<div class="form-group mb-0">
							<label class="etiqueta">Tipo de Trámite:</label>
							<select name="document_status" class="form-control">
								<option value="">TODOS</option>
								@foreach($document_statuses as $state)
									@if($state['id'] == $document_status)
										<option value="{{ $state['id'] }}" selected="selected">{{ $state['name'] }}</option>
									@else
										<option value="{{ $state['id'] }}">{{ $state['name'] }}</option>
									@endif
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-sm-auto pl-md-0 mb-3">
						<button type="submit" class="btn btn-primary">Buscar</button>
					</div>

					<div class="col-md-3 mb-3">
						<div class="form-group daterangepicker-area mb-0">
							<label class="etiqueta">Rango de fecha:</label>
							<input type="hidden" name="start_date" value="{{ $start_date }}">
							<input type="hidden" name="end_date" value="{{ $end_date }}">
							<input type="text" class="form-control" name="dates" value="">
						</div>
					</div>
					<div class="col-sm-auto mb-3">
						<label class="etiqueta">Exportar en:</label><br>
						<button class="btn btn-primary" id="solicitudes_report">Excel</button>
						<a href="/admin/solicitudes-report-pdf" class="btn btn-primary" target="_blank">PDF</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{{Form::close()}}
