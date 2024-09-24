@extends ('layouts.admin')
@section ('contenido')
<h3 class="font-bold">Editar Solicitud Interna</h3>

{!! Form::open(array('id' => 'internal-solicitude_form', 'role' => 'form', 'files' => true, 'enctype' => 'multipart/form-data')) !!}
  <input type="hidden" name="_method" value="PUT">
  <input type="hidden" name="" id="order_id" value="{{ $order->id }}">
  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header text-white bg-dark"><h5>Datos Generales</h5></div>
        <div class="card-body bg-light">
          <div class="form-group mb-1">
            <label class="etiqueta">CUD: {{ $order->code }}</label>
          </div>
          <div class="row d-none">
            <div class="col-sm-6">
              <div class="form-group mb-1">
                <label class="etiqueta">Fecha:</label>
                <input type="text" name="date" class="form-control date-datepicker" autocomplete="off" placeholder="dd/mm/yyyy" value="">
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group mb-1">
                <label class="etiqueta">Hora:</label>
                <input type="time" name="time" class="form-control" value="">
              </div>
            </div>
          </div>
          <div class="form-group mb-1">
            <label class="etiqueta">Tipo de trámite: (*)</label>
            <input type="hidden" name="document_type_id" value="{{ $order->document_type_id }}">
            <select class="form-control select_2" disabled>
              <option value="">Seleccione</option>
              @foreach($document_types as $document_type)
                @if($document_type->id == $order->document_type_id)
                  <option value="{{ $document_type->id }}" selected>{{ $document_type->name }}</option>
                @else
                  <option value="{{ $document_type->id }}">{{ $document_type->name }}</option>
                @endif
              @endforeach
            </select>
            <div class="text-danger error-message" id="document-document_type_id-error"></div>
          </div>

          <div class="form-group mb-1">
            <label class="etiqueta" id="document_type_code">{{ $order->internal_code }}</label>
            <input type="text" class="form-control" name="internal_code" value="{{ $order->internal_code }}" disabled>
            <a href="#" class="internal-code-edit">Editar bajo su propio riesgo</a>
          </div>

          <div class="form-group mb-1">
            <label class="etiqueta">Tipo de procedimiento:</label>
            <select class="form-control select_2" name="tupa_id">
              <option value="">No TUPA</option>
              @foreach($tupa as $t)
                @if($t->id == $order->tupa_id)
                <option value="{{ $t->id }}" selected>{{ $t->title }}</option>
                @else
                <option value="{{ $t->id }}">{{ $t->title }}</option>
                @endif
              @endforeach
            </select>
            <div class="text-danger error-message" id="document-tupa_id-error"></div>
          </div>

          <div class="form-group mb-1">
            <label class="etiqueta">Asunto: (*)</label>
            <input type="text" name="subject" class="form-control" value="{{ $order->subject }}">
            <div class="text-danger error-message" id="document-subject-error"></div>
          </div>

          <div class="form-group mb-1 d-none">
            <label class="etiqueta">Referencia:</label>
            <input type="text" name="reference" class="form-control" value="{{ $order->reference }}">
          </div>

          <div class="form-group mb-0">
            <label class="etiqueta">Observación:</label>
            <textarea name="observations" class="form-control" rows="3">{{ $order->notes }}</textarea>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card">
        <div class="card-header text-white bg-dark"><h5>Sección derivación</h5></div>
        <div class="card-body bg-light">
          <div class="form-group mb-1 d-none">
            <label class="etiqueta">Dirigido: (*)</label>
              <select name="" class="form-control">
                  <option value="">Seleccione</option>
                  <option value="">Oficina 1</option>
              </select>
              <div class="text-danger error-message" id="document-office_id-error"></div>
          </div>
          <div class="row">
            <div class="col-sm-6 d-none">
              <div class="form-group mb-1">
                <label class="etiqueta">CC:</label>
                <select name="offices" class="form-control" multiple>
                  <option value="">Seleccione</option>
                  <option value="">Oficina 1</option>
                </select>
              </div>
            </div>
            <div class="col-sm-12">
              <div class="form-group mb-1">
                <label class="etiqueta">Plazo:</label>
                <div class="input-group desc_input">
                  <input type="number" name="term" class="form-control" value="{{ $order->term }}">
                  <div class="input-group-append"><span class="input-group-text">Días</span></div>
                </div>
                <div class="error-message text-danger" id="document-term-error"></div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-6">
              <div class="form-group mb-sm-0 mb-1">
                <label class="etiqueta">Para: (*):</label>
                <select name="order_type_id" class="form-control">
                  <option value="">Seleccione</option>
                  @foreach($order_types as $type)
                    @if($type->id == $order->order_type_id)
                      <option value="{{ $type->id }}" selected>{{ $type->name }}</option>
                    @else
                      <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endif
                  @endforeach
                </select>
                <div class="error-message text-danger" id="document-order_type_id-error"></div>
              </div>
            </div>
            <div class="col-sm-6">
              <div class="form-group mb-0">
                <label class="etiqueta">Folios:</label>
                <input type="number" name="folios" class="form-control" value="{{ $order->folios }}">
              </div>
            </div>
          </div>
          <div class="form-group mb-1">
            @if($order->attached_file)
              <a href="{{ $order->attached_file }}">Archivo adjunto</a>
            @endif
            <br>
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


<div class="text-center py-3"><button type="button" class="btn btn-success font-bold" id="send-document">ACTUALIZAR</button></div>

{!! Form::close() !!}

<label class="text-danger">(*) Son campos obligatorios</label>

@push ('scripts')
<script>
  //$('select[name="office_id"]').select2();
  //$('select[name="offices"]').select2();
document.querySelector('.internal-code-edit')
  .addEventListener('click', (e) => {
    e.preventDefault();

    document.querySelector('input[name="internal_code"]').disabled = false;
  });

document.querySelector(`#send-document`)
    .addEventListener('click', () => {
        lockWindow();
        $(`.error-message`).empty();
        let _formData = new FormData($(`#internal-solicitude_form`)[0]);

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name=_token]').val()
                }
            });
            $.ajax({
                url : `/admin/solicitude/${document.querySelector('#order_id').value}`,
                type: 'POST',
                data: _formData,
                contentType: false,
                processData: false,
                success: function(e){
                    unlockWindow();
                    notice(`${e.title}`, `${e.message}`, `success`);
                    //$(`#internal-solicitude_form`)[0].reset();
                    // setTimeout(function(){ 
                    //     location.replace(`/admin/mis-solicitudes-enviadas`);
                    //  }, 1000);

                },
                error:function(jqXHR, textStatus, errorThrown)
                {
                    notice(`Advertencia`, `Hay errores en uno o más campos.`, `warning`);
                    unlockWindow();
                    $.each(jqXHR.responseJSON, function (key, value) {
                          $.each(value, function (errores, eror) {
                            $(`#document-${key}-error`).append("<li class='error-block'>" + eror + "</li>");
                          });
                    });
                }
            });
    });

</script>
@endpush
@endsection
@section('custom-css')
<style type="text/css">
  #document_type_code {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 8px;
  }
</style>
@endsection
