@extends ('layouts.admin')
@section ('contenido')
<h3 class="font-bold">Editar registro del estudiante</h3>
{!! Form::open(array('id' => 'internal-solicitude_form', 'role' => 'form', 'files' => true, 'enctype' => 'multipart/form-data')) !!}
  
<input type="hidden" name="_method" value="PUT" />
<div class="row">
  <input type="hidden" name="order_id" value="{{ $order->id }}">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header text-white bg-dark"><h5>Datos del estudiante</h5></div>
      <div class="card-body bg-light">
        <div class="form-group mb-2 d-none">
          <label class="etiqueta"><b>CUD: {{ $year }}{{ $code }}</b></label>
          <input type="text" class="form-control d-none" value="{{ $year }}{{ $code }}" disabled>
        </div>
        <div class="row d-none">
          <div class="col-sm-6">
            <div class="form-group mb-1">
              <label class="etiqueta">Fecha:</label>
              <input type="text" name="date" class="form-control date-datepicker" autocomplete="off" placeholder="dd/mm/yyyy" value="{{ $today_date }}" disabled>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group mb-1">
              <label class="etiqueta">Hora:</label>
              <input type="time" name="time" class="form-control" value="{{ $today_hour }}" disabled>
            </div>
          </div>
        </div>

        <div class="form-group mb-1">
          <label class="etiqueta">CÓDIGO: (*)</label>
          <input type="text" name="code" class="form-control" value="{{ $order->code }}" disabled>
          <div class="text-danger error-message" id="document-code-error"></div>
        </div>

        <div class="form-group mb-1">
          <label class="etiqueta">DNI: (*)</label>
          <input type="text" name="identity_document" class="form-control" value="{{ $order->entity->identity_document }}">
          <div class="text-danger error-message" id="document-identity_document-error"></div>
        </div>

        <div class="form-group mb-1">
          <label class="etiqueta">NOMBRE: (*)</label>
          <input type="text" name="name" class="form-control" value="{{ $order->entity->name }}">
          <div class="text-danger error-message" id="document-name-error"></div>
        </div>

        <div class="form-group mb-1">
          <label class="etiqueta">APELLIDO PATERNO: (*)</label>
          <input type="text" name="paternal_surname" class="form-control" value="{{ $order->entity->paternal_surname }}">
          <div class="text-danger error-message" id="document-paternal_surname-error"></div>
        </div>

        <div class="form-group mb-1">
          <label class="etiqueta">APELLIDO MATERNO: (*)</label>
          <input type="text" name="maternal_surname" class="form-control" value="{{ $order->entity->maternal_surname }}">
          <div class="text-danger error-message" id="document-maternal_surname-error"></div>
        </div>

        <div class="form-group mb-1">
          <label class="etiqueta">Nivel: (*):</label>
          <select name="order_type_id" class="form-control">
              <option value="">Seleccione</option>
            @if($order->order_type_id == 1)
              <option value="1" selected>PRIMARIA</option>
              <option value="2">SECUNDARIA</option>
            @else
              <option value="1">PRIMARIA</option>
              <option value="2" selected>SECUNDARIA</option>
            @endif
          </select>
          <div class="error-message text-danger" id="document-order_type_id-error"></div>
        </div>

        <div class="form-group mb-1 d-none">
          <label class="etiqueta">Numeración:</label>
          <div class="text-uppercase" id="document_type_code"></div>
          <input type="hidden" name="internal_code">
        </div>

        <div class="form-group mb-1">
          <label class="etiqueta">Año:</label>
          <input type="hidden" id="tupa_id_value" value="{{ $order->tupa_id }}">
          <select class="form-control select_2" name="tupa_id">
            <option value="">Seleccione</option>
            @if($order->order_type_id == 1)
              <option value="1">PRIMERO</option>
              <option value="2">SEGUNDO</option>
              <option value="3">TECERO</option>
              <option value="4">CUARTO</option>
              <option value="5">QUINTO</option>
              <option value="6">SEXTO</option>
            @else
              <option value="1">PRIMERO</option>
              <option value="2">SEGUNDO</option>
              <option value="3">TECERO</option>
              <option value="4">CUARTO</option>
              <option value="5">QUINTO</option> 
            @endif
          </select>
          <div class="text-danger error-message" id="document-tupa_id-error"></div>
        </div>

        <div class="form-group mb-1">
          <label class="etiqueta">Sección:</label>
          <input type="hidden" id="subject_value" value="{{ $order->subject }}">
          <select class="form-control select_2" name="subject">
            <option value="">Seleccione</option>
            @if($order->order_type_id == 1)
              <option value="A">A</option>
              <option value="B">B</option>
              <option value="C">C</option>
            @else
              <option value="A">A</option>
              <option value="B">B</option>
              <option value="C">C</option>
              <option value="D">D</option>
            @endif
          </select>
          <div class="text-danger error-message" id="document-subject-error"></div>
        </div>

      </div>

      <div class="card-header"><h6>Datos del apoderado</h6></div>
      <div class="card-body bg-light">
        @if($order->entity->profession)
          <div class="form-group mb-2">
            <label class="etiqueta">DNI: (*)</label>
            <input type="text" name="identity_document_parent" class="form-control" value="{{ $order->entity->profession->code }}">
            <div class="text-danger error-message" id="document-identity_document_parent-error"></div>
          </div>

          <div class="form-group mb-2">
            <label class="etiqueta">NOMBRE: (*)</label>
            <input type="text" name="name_parent" class="form-control" value="{{ $order->entity->profession->name }}">
            <div class="text-danger error-message" id="document-name_parent-error"></div>
          </div>

          <div class="form-group mb-2">
            <label class="etiqueta">APELLIDO PATERNO: (*)</label>
            <input type="text" name="paternal_surname_parent" class="form-control" value="{{ $order->entity->profession->sigla }}">
            <div class="text-danger error-message" id="document-paternal_surname_parent-error"></div>
          </div>

          <div class="form-group mb-2">
            <label class="etiqueta">APELLIDO MATERNO: (*)</label>
            <input type="text" name="maternal_surname_parent" class="form-control" value="{{ $order->entity->profession->maternal_surname }}">
            <div class="text-danger error-message" id="document-maternal_surname_parent-error"></div>
          </div>
        @else
          <div class="form-group mb-2">
            <label class="etiqueta">DNI: (*)</label>
            <input type="text" name="identity_document_parent" class="form-control">
            <div class="text-danger error-message" id="document-identity_document_parent-error"></div>
          </div>

          <div class="form-group mb-2">
            <label class="etiqueta">NOMBRE: (*)</label>
            <input type="text" name="name_parent" class="form-control">
            <div class="text-danger error-message" id="document-name_parent-error"></div>
          </div>

          <div class="form-group mb-2">
            <label class="etiqueta">APELLIDO PATERNO: (*)</label>
            <input type="text" name="paternal_surname_parent" class="form-control">
            <div class="text-danger error-message" id="document-paternal_surname_parent-error"></div>
          </div>

          <div class="form-group mb-2">
            <label class="etiqueta">APELLIDO MATERNO: (*)</label>
            <input type="text" name="maternal_surname_parent" class="form-control">
            <div class="text-danger error-message" id="document-maternal_surname_parent-error"></div>
          </div>
        @endif

      </div>


    </div>
  </div>
  <div class="col-md-6">
    <div class="card">
      <div class="card-header text-white bg-dark"><h5>Conceptos de pago {{ $year }}</h5></div>
      <div class="card-body bg-light">
      <div class="form-group mb-1 d-none">
        <label class="etiqueta">De:</label>
        <input type="text" class="form-control" value="{{ $current_office }}" disabled>
      </div>
       
      <div class="form-group mb-1">
        <table class="table">
          <thead>
            <tr>
              <th scope="col">#</th>
              <th scope="col">Mes</th>
              <th scope="col">Monto</th>
            </tr>
          </thead>
          <tbody>
            @foreach($order->details as $fm => $detail) 
              @if($detail->status)
                <tr>
                  <th scope="row">
                        <input type="checkbox" checked disabled>

                  </th>
                  <td><b>{{ $detail->office->name }}</b> <span class="text-success">(Pagado)<a href=""></a></span></td>
                  <td>
                    <input type="number" value="{{ $detail->observations }}" disabled>
                  </td>
                </tr>
              @else
                <tr>
                  <th scope="row">
                      <input type="hidden" name="office_ids[]" value="{{ $detail->office_id }}">
                      <input type="hidden" name="detail_indexes[]" value="{{ $detail->id }}">
                       <input type="hidden" name="payed[]" value="1">
                      <input type="checkbox" onclick="changeCheckboxValue(this);" checked>
                  </th>
                  <td><b>{{ $detail->office->name }}</b></td>
                  <td>
                    <input type="number" name="amount[]" value="{{ $detail->observations }}">
                  </td>
                </tr>
              @endif

            @endforeach

            @foreach($payment_concepts as $fm => $concept)
            <tr>
              <th scope="row">
                  <input type="hidden" name="office_ids[]" value="{{ $concept->id }}">
                  <input type="hidden" name="detail_indexes[]" value="0">

                   <input type="hidden" name="payed[]" value="0">
                    <input type="checkbox" onclick="changeCheckboxValue(this);">
              </th>
              <td><b>{{ $concept->name }}</b></td>
              <td>
                <input type="number" name="amount[]" value="{{ $concept->sigla }}">
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>

      </div>

      </div>
    </div>
    <div>
      <label class="text-danger">Para eliminar un concepto pagado, primero se debe eliminar el pago en lista de fichas.</label>
      <span><a href="/admin/lista-de-fichas?searchText={{ $order->entity->identity_document }}" target="_blank">Lista de pagos del estudiante</a></span>
    </div>

  </div>
</div>
<label class="text-danger">(*) Son campos obligatorios</label>

  {!! Form::close() !!}


<div class="text-center py-3"><button type="button" class="btn btn-success font-bold" id="send-document">ENVIAR</button></div>


@push ('scripts')
<script>
  $('#liGeneracionInterna').addClass("treeview active");
  $('#liRegisterStudent').addClass("active");

  $('select[name="offices"]').select2();
  $('select[name="offices_id"]').select2();


</script>
<script type="text/javascript" src="/js/edit_logged_solicitudes.js"></script>
@endpush
@endsection
@section('custom-css')
<style type="text/css">
  #document_type_code {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 8px;
    line-height: 1;
  }
</style>
@endsection
