@extends ('layouts.admin')
@section ('contenido')
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
			<h3>Editar Concepto de Pago: {{ $office->name}}</h3>
			@if (count($errors)>0)
			<div class="alert alert-danger">
				<ul>
				@foreach ($errors->all() as $error)
					<li>{{$error}}</li>
				@endforeach
				</ul>
			</div>
			@endif

			{!!Form::model($office,['method'=>'PATCH','action'=>['OfficeController@update',$office->id]])!!}
                  {{Form::token()}}
                  <input type="hidden" name="id" value="{{ $office->id }}">
            <div class="form-group">
            	<label for="name">Nombre</label>
            	<input type="text" name="name" class="form-control" value="{{$office->name}}" placeholder="Nombre...">
            </div>
            <div class="form-group d-none">
            	<label for="code">Código</label>
            	<input type="text" name="code" class="form-control" value="{{$office->code}}" placeholder="Código...">
            </div>

            <div class="form-group">
                  <label for="sigla">Monto</label>
                  <input type="number" name="sigla" class="form-control" value="{{$office->sigla}}" placeholder="Monto...">
            </div>

            @if($office->is_for_all_years)
                  <div class="form-group" style="display: none;">
            @else
                  <div class="form-group">
            @endif
                  <label for="outstanding">Año</label>
                  <input type="hidden" name="year_selected" value="{{$office->year}}">
                  <select class="form-control" name="year">
                        <option value="2023">2023</option>
                        <option value="2024" selected="selected">2024</option>
                  </select>
            </div>

            <div class="form-group">
                  <label for="code">Se considera para todos los años</label>
                  @if($office->is_for_all_years)
                        <input type="checkbox" name="is_for_all_years" onclick="showOrHideYearSelect(this);" checked>
                  @else
                        <input type="checkbox" name="is_for_all_years" onclick="showOrHideYearSelect(this);">
                  @endif
            </div>

            <div class="form-group d-none">
                  <label for="outstanding">Documentos</label>
                  <select class="form-control" name="document_types_id" multiple>
                        @foreach($document_types_selected as $document_type)
                              <option value="{{ $document_type->id }}" {{ $document_type->office_selected ? "selected" : "" }}>{{ $document_type->name }}</option>
                        @endforeach
                  </select>
                  <input type="hidden" name="document_types_id_selected">
                  <input type="hidden" name="document_type_changed" value="0">
            </div>

            <div class="form-group">
            	<button class="btn btn-primary" type="submit">Guardar</button>
                  <a href="/admin/oficinas" class="btn btn-danger">Cancelar</a>
            </div>

			{!!Form::close()!!}

                  <div class="d-none">
                        <label>Los documentos de la oficina empezarán en:</label>
                        <form action="/admin/document-type/office" method="POST">

                              {{ csrf_field() }}
                              <input type="hidden" name="office_id" value="{{ $office->id }}">

                              @foreach($document_types as $document_type)
                              <div class="row align-items-center mb-1">
                                    <label class="col-sm-auto mb-0">{{ $document_type->name }}</label>
						      <div class="col-sm">
																			<input type="hidden" name="document_type_id[]" value="{{ $document_type->id }}">
	                                    @if($document_type->office)
	                                          <input type="text" class="form-control" name="start_with[]" value="{{ $document_type->office->start_with }}">
	                                    @else
	                                          <input type="text" class="form-control" name="start_with[]" value="1">
	                                    @endif
						</div>
                                    <!-- <div class="col-auto">
                                    	<button class="btn btn-success">Actualizar</button>
                                    </div> -->
                              </div>
                              @endforeach

                              <button type="submit" class="btn btn-success">Actualizar</button>

                        </form>


                  </div>


		</div>
	</div>
@push ('scripts')
<script>
$('#liAlmacen').addClass("treeview active");
$('#liCategorias').addClass("active");

$('select[name="year"]').val($('input[name="year_selected"]').val());


function showOrHideYearSelect(checkbox)
{
      if (checkbox.checked) {
            $(`select[name="year"]`).parent().hide();
      } else {
            $(`select[name="year"]`).parent().show();
      }
}

// $('select[name="document_types_id"]').on('change', function(e){
//       document.querySelector('input[name="document_types_id_selected"]').value = $('select[name="document_types_id"]').val();
//       document.querySelector('input[name="document_type_changed"]').value = 1;
// });


</script>
@endpush
@endsection
