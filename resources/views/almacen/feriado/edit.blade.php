@extends ('layouts.admin')
@section ('contenido')
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<h3>Editar Feriado: {{ $feriado->name}}</h3>
			@if (count($errors)>0)
			<div class="alert alert-danger">
				<ul>
				@foreach ($errors->all() as $error)
					<li>{{$error}}</li>
				@endforeach
				</ul>
			</div>
			@endif

			{!!Form::model($feriado,['method'=>'PATCH','action'=>['FeriadosController@update',$feriado->id]])!!}
                  {{Form::token()}}
                  <input type="hidden" name="id" value="{{ $feriado->id }}">

					<div class="form-group">
		            	<label for="name">Nombre</label>
		            	<textarea name="description" class="form-control" placeholder="Descripción">{{ $feriado->description }}</textarea>
		            </div>
		           	<div class="form-group">
		            	<label for="name">Anual</label>
		            	<select class="form-control" name="anual">
		            		<option value="">Seleccione</option>
		            		@if($feriado->anual)
		            			<option value="1" selected>Sí</option>
		            			<option value="0">No</option>
		            		@else
		            			<option value="1">Sí</option>
		            			<option value="0" selected>No</option>
		            		@endif
		            	</select>
		            </div>

		            <div class="form-group">
		              <label class="etiqueta">Fecha:</label>
		              <input type="text" name="fecha" class="form-control date-datepicker" autocomplete="off" placeholder="dd/mm/yyyy" value="{{ \Carbon\Carbon::parse($feriado->date)->format('d/m/Y') }}">
		            </div>
	            <div class="form-group">
	            	<button class="btn btn-primary" type="submit">Guardar</button>
	                  <a href="/admin/feriados" class="btn btn-danger">Cancelar</a>
	            </div>

			{!!Form::close()!!}
		</div>
	</div>
@push ('scripts')
<script>
$('#liAlmacen').addClass("treeview active");
$('#liOrderType').addClass("active");

</script>
@endpush
@endsection