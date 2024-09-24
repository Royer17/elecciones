<div class="row align-items-end">
	<div class="col-auto mb-1">
		<a href="profesiones/create" class="btn btn-success">Nuevo</a>
	</div>
	<div class="col-md-5 mb-1 pl-md-0">
		{!! Form::open(array('url'=>'admin/profesiones','method'=>'GET','autocomplete'=>'off','role'=>'search')) !!}
		<div class="input-group">
		  <input type="text" class="form-control" name="searchText" placeholder="Buscar..." value="{{$searchText}}">
		  <div class="input-group-append">
		    <button type="submit" class="btn btn-primary">Buscar</button>
		  </div>
		</div>
		{{Form::close()}}
	</div>
</div>
