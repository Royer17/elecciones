{!! Form::open(array('url'=>'admin/nuevas-solicitudes','method'=>'GET','autocomplete'=>'off','role'=>'search')) !!}
<div class="form-group">
	<div class="input-group">
		<div class="col-md-6" style="padding-left: 0px;padding-right: 0px">
			<input type="text" class="form-control" name="searchText" placeholder="Buscar por código, DNI o RUC..." value="{{$searchText}}">
		</div>
		<span class="input-group-btn" style="padding-left: 0px;">
			<button type="submit" class="btn btn-primary">Buscar</button>
		</span>
	</div>
</div>

{{Form::close()}}

<div class="form-group daterangepicker-area">
	<div class="input-group">
		<div class="col-md-12" style="padding-left: 0px;padding-right: 0px">
			<input type="hidden" name="start_date" value="{{ $start_date }}">
			<input type="hidden" name="end_date" value="{{ $end_date }}">
			<input type="text" class="form-control" name="dates" value="">
		</div>
	</div>
</div>