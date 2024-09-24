@extends ('layouts.admin')
@section ('contenido')
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<h3>Editar Tipo de atención: {{ $order_type->name}}</h3>
			@if (count($errors)>0)
			<div class="alert alert-danger">
				<ul>
				@foreach ($errors->all() as $error)
					<li>{{$error}}</li>
				@endforeach
				</ul>
			</div>
			@endif

			{!!Form::model($order_type,['method'=>'PATCH','action'=>['OrderTypeController@update',$order_type->id]])!!}
                  {{Form::token()}}
                  <input type="hidden" name="id" value="{{ $order_type->id }}">
            	<div class="form-group">
            	<label for="name">Nombre</label>
            	<input type="text" name="name" class="form-control" value="{{$order_type->name}}" placeholder="Nombre...">
            	</div>

	            <div class="form-group">
	            	<button class="btn btn-primary" type="submit">Guardar</button>
	                  <a href="/admin/tipo-de-atencion" class="btn btn-danger">Cancelar</a>
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