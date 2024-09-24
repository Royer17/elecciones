<div class="row align-items-end">
	<div class="col-auto mb-1">
		<a href="/admin/oficinas/create" class="btn btn-success">Nuevo</a>
	</div>
	<div class="col-md-5 mb-1 pl-md-0">
		{!! Form::open(array('url'=>'admin/oficinas','method'=>'GET','autocomplete'=>'off','role'=>'search')) !!}
		<div class="input-group">
		  <input type="text" class="form-control" name="searchText" placeholder="Buscar..." value="{{$searchText}}">
		  <div class="input-group-append">
		    <button type="submit" class="btn btn-primary">Buscar</button>
		  </div>
		</div>

		{{Form::close()}}
	</div>

	<div class="col-auto mb-1">
		<label class="etiqueta">Año:</label><br>

		<select class="form-control" name="year">
			<option value="">Todos</option>
			<option value="2023">2023</option>
			<option value="2024" selected>2024</option>
		</select>
	</div>

</div>
