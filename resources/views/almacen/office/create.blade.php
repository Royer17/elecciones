@extends ('layouts.admin')
@section ('contenido')
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<h3><b>Nuevo Concepto de Pago</b></h3>
			@if (count($errors)>0)
			<div class="alert alert-danger">
				<ul>
				@foreach ($errors->all() as $error)
					<li>{{$error}}</li>
				@endforeach
				</ul>
			</div>
			@endif

			{!!Form::open(array('url'=>'admin/oficinas','method'=>'POST','autocomplete'=>'off'))!!}
            {{Form::token()}}
            <div class="form-group">
            	<label for="name">Nombre</label>
            	<input type="text" name="name" class="form-control" placeholder="Nombre...">
            </div>

            <div class="form-group d-none">
            	<label for="code">Código</label>
            	<input type="text" name="code" class="form-control" placeholder="Código...">
            </div>
            <div class="form-group">
                  <label for="sigla">Monto</label>
                  <input type="number" name="sigla" class="form-control" placeholder="Monto...">
            </div>

            <div class="form-group">
                  <label for="outstanding">Año</label>
                  <select class="form-control" name="year">
                        <option value="2023">2023</option>
                        <option value="2024" selected="selected">2024</option>
                  </select>
            </div>

            <div class="form-group">
                  <label for="code">Se considera para todos los años</label>
                  <input type="checkbox" name="is_for_all_years" onclick="showOrHideYearSelect(this);">
            </div>

            <div class="form-group d-none">
            	<label for="outstanding">Responsable/Jefe</label>
            	<select class="form-control" name="entity_id">
                        @foreach($entities as $entity)
                              <option value="{{ $entity['id'] }}">{{ $entity['name'] }} {{ $entity['paternal_surname'] }}</option>
                        @endforeach
            	</select>
            </div>

            <div class="form-group d-none">
                  <label for="outstanding">Oficina Superior</label>
                  <select class="form-control" name="upper_office_id">
                        <option value="0">No tiene</option>
                        @foreach($offices as $office)
                              <option value="{{ $office['id'] }}">{{ $office['name'] }}</option>
                        @endforeach
                  </select>
            </div>

            <div class="form-group">
            	<button class="btn btn-primary" type="submit">Guardar</button>
                  <a href="/admin/oficinas" class="btn btn-danger">Cancelar</a>
            </div>

			{!!Form::close()!!}

		</div>
	</div>
@push ('scripts')
<script>
$('#liAlmacen').addClass("treeview active");
$('#liCategorias').addClass("active");

$('select[name="entity_id"]').select2();
$('select[name="upper_office_id"]').select2();


function showOrHideYearSelect(checkbox)
{
      if (checkbox.checked) {
            $(`select[name="year"]`).parent().hide();
      } else {
            $(`select[name="year"]`).parent().show();
      }
}

</script>
@endpush
@endsection
