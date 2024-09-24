@extends ('layouts.admin')
@section ('contenido')
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<h3>Editar Documento: {{ $document_type->name}}</h3>
			@if (count($errors)>0)
			<div class="alert alert-danger">
				<ul>
				@foreach ($errors->all() as $error)
					<li>{{$error}}</li>
				@endforeach
				</ul>
			</div>
			@endif

			{!!Form::model($document_type,['method'=>'PATCH','action'=>['DocumentTypeController@update',$document_type->id]])!!}
                  {{Form::token()}}
                  <input type="hidden" name="id" value="{{ $document_type->id }}">
            <div class="form-group">
            	<label for="name">Nombre</label>
            	<input type="text" name="name" class="form-control" value="{{$document_type->name}}" placeholder="Nombre...">
            </div>
            <div class="form-group">
            	<label for="code">Código</label>
            	<input type="text" name="code" class="form-control" value="{{$document_type->code}}" placeholder="Código...">
            </div>
            
            <div class="form-group">
                  <label for="sigla">SIGLA</label>
                  <input type="text" name="sigla" class="form-control" value="{{$document_type->sigla}}" placeholder="SIGLA...">
            </div>
            	
		<div class="form-group d-none">
                  <label for="sigla">Empezar con:</label>
                  <input type="text" name="start_with" class="form-control" value="{{$document_type->start_with}}" placeholder="Número de inicio...">
            </div>

		<div class="form-group">
                  <label for="sigla">Tipo:</label>
                  <select name="is_multiple" class="form-control">
                  	@if($document_type->is_multiple == 0)
                  		<option value="0" selected>Simple</option>
                  		<option value="1">Múltiple</option>
                  	@else
					<option value="0">Simple</option>
                  		<option value="1" selected>Múltiple</option>
                  	@endif
                  </select>
            </div>

            <div class="form-group">
            	<button class="btn btn-primary" type="submit">Guardar</button>
                  <a href="/admin/tipos-de-documento" class="btn btn-danger">Cancelar</a>
            </div>

			{!!Form::close()!!}

		</div>
	</div>
@push ('scripts')
<script>
$('#liAlmacen').addClass("treeview active");
$('#liDocumentType').addClass("active");
</script>
@endpush
@endsection