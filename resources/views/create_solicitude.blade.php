@extends ('layouts.admin')
@section ('contenido')
<h3 class="font-bold">Crear Solicitud Interna</h3>
{!! Form::open(array('id' => 'internal-solicitude_form', 'role' => 'form', 'files' => true, 'enctype' => 'multipart/form-data')) !!}

<div class="row">

  <input type="hidden" name="parent_office_id" value="{{ $current_office_id }}">

  <div class="col-md-6">
    <div class="card">
      <div class="card-header text-white bg-dark"><h5>Datos Generales</h5></div>
      <div class="card-body bg-light">
        <div class="form-group mb-2">
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
          <label class="etiqueta">Tipo de trámite: (*)</label>
          <select class="form-control select_2" name="document_type_id">
            <option value="">Seleccione</option>
            @foreach($document_types as $document_type)
              <option value="{{ $document_type->id }}">{{ $document_type->name }}</option>
            @endforeach
          </select>
          <div class="text-danger error-message" id="document-document_type_id-error"></div>
        </div>

        <div class="form-group mb-1">
          <label class="etiqueta">Numeración:</label>
          <div class="text-uppercase" id="document_type_code"></div>
          <input type="hidden" name="internal_code">
        </div>

        <div class="form-group mb-1">
          <label class="etiqueta">Tipo de procedimiento:</label>
          <select class="form-control select_2" name="tupa_id">
            <option value="">No TUPA</option>
            <option value="1">TUPA</option>
          </select>
          <div class="text-danger error-message" id="document-tupa_id-error"></div>
        </div>

        <div class="form-group mb-1">
          <label class="etiqueta">Asunto: (*)</label>
          <input type="text" name="subject" class="form-control">
          <div class="text-danger error-message" id="document-subject-error"></div>
        </div>

        <div class="form-group mb-1 d-none">
          <label class="etiqueta">Referencia:</label>
          <input type="text" name="reference" class="form-control">
        </div>

        <div class="form-group mb-0">
          <label class="etiqueta">Observación:</label>
          <textarea name="observations" class="form-control" rows="3"></textarea>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="card">
      <div class="card-header text-white bg-dark"><h5>Sección derivación</h5></div>
      <div class="card-body bg-light">
      <div class="form-group mb-1 d-none">
        <label class="etiqueta">De:</label>
        <input type="text" class="form-control" value="{{ $current_office }}" disabled>
      </div>
        <div class="form-group mb-2 simple-document">
          <label class="etiqueta">Dirigido: (*)</label>
            <select name="office_id" class="form-control">
                <option value="">Seleccione</option>
                @foreach($offices as $office)
                  <option value="{{ $office['id'] }}">{{ $office['name'] }}</option>
                @endforeach
            </select>
            <div class="text-danger error-message" id="document-office_id-error"></div>
        </div>

        <div class="form-group mb-2 multiple-document">
          <label class="etiqueta">Dirigido: (*)</label>
            <select name="offices_id" class="form-control" multiple>
                <option value="">Seleccione</option>
                @foreach($offices as $office)
                  <option value="{{ $office['id'] }}">{{ $office['name'] }}</option>
                @endforeach
            </select>
            <div class="text-danger error-message" id="document-offices_id-error"></div>
        </div>

        <div class="mb-2 simple-document">
          <a href="#" class="text-dark" data-toggle="collapse" data-target="#cc_area">¿Desea enviar con copia?<i class="fas fa-angle-down ml-2"></i></a>
          <div id="cc_area" class="collapse">
            <div class="form-group">
              <label class="etiqueta">CC:</label>
              <select name="offices" class="form-control" multiple>
              </select>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-sm-6 simple-document">
            <div class="form-group mb-1">
              <label class="etiqueta">Plazo:</label>
              <div class="input-group desc_input">
                <input type="number" name="term" class="form-control">
                <div class="input-group-append"><span class="input-group-text">Días</span></div>
              </div>
              <div class="error-message text-danger" id="document-term-error"></div>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group mb-0">
              <label class="etiqueta">Folios:</label>
              <input type="number" name="folios" class="form-control" value="1">
            </div>
          </div>
        </div>

        <div class="form-group mb-1">
          <label class="etiqueta">Para: (*):</label>
          <select name="order_type_id" class="form-control">
            <option value="">Seleccione</option>
            @foreach($order_types as $type)
              <option value="{{ $type->id }}">{{ $type->name }}</option>
            @endforeach
          </select>
          <div class="error-message text-danger" id="document-order_type_id-error"></div>
        </div>

        <div class="form-group mb-1">
          <label class="etiqueta">Archivo:</label>
          <input type="file" class="form-control-file file_format text-uppercase" id="documento_pdf_field" name="attached_file" accept="application/pdf" aria-describedby="documento_pdf_helptext">
          <div id="documento_pdf_helptext" class="form-text text-muted" style="font-size: 13px;">
          Solo se admite el archivo en formato PDF, y con un tamaño maximo de 15MB.
          </div>
          <div class="error-message text-danger" id="document-attached_file-error"></div>
        </div>
      </div>
    </div>

  </div>
</div>
  {!! Form::close() !!}


<div class="text-center py-3"><button type="button" class="btn btn-success font-bold" id="send-document">ENVIAR</button></div>

<label class="text-danger">(*) Son campos obligatorios</label>

@push ('scripts')
<script>
  $('#liGeneracionInterna').addClass("treeview active");
  $('#liSolicitude').addClass("active");

  $('select[name="offices"]').select2();
  $('select[name="offices_id"]').select2();


</script>
<script type="text/javascript" src="/js/logged_solicitudes.js"></script>
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
