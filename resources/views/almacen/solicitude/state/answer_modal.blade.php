<div class="modal fade" id="answer-modal">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header bg-primary text-white">
        <h4 class="modal-title">Responder Solicitud</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>

      <!-- Modal body -->
      <div class="modal-body px-md-4">
        {!! Form::open(array('id' => 'answer_form', 'role' => 'form', 'files' => true, 'enctype' => 'multipart/form-data')) !!}

        <input type="hidden" name="parent_office_id" value="{{ $current_office_id }}">
        <input type="hidden" name="parent_order_id" value="">

        <div class="row">
          <div class="col-md-6">
            <h4>Datos Generales</h4>
            <div class="form-group mb-1 d-none">
              <label class="etiqueta">NIT:</label>
              <input type="text" class="form-control" value="">
            </div>
            <div class="form-group mb-1 d-none">
              <label class="etiqueta">Fecha:</label>
              <input type="text" name="date" class="form-control date-datepicker" autocomplete="off" placeholder="dd/mm/yyyy" value="">
            </div>
            <div class="form-group mb-1 d-none">
              <label class="etiqueta">Hora:</label>
              <input type="time" name="time" class="form-control" value="">
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

              <label class="etiqueta" id="document_type_code">informe1-tics-mp/TACNA-2022</label>
              <input type="hidden" name="internal_code">
            </div>
            <div class="form-group mb-1">
              <label class="etiqueta">Tipo de procedimiento:</label>
              <select class="form-control select_2" name="tupa_id">
                <option value="">No Tupa</option>
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
            <div class="form-group">
              <label class="etiqueta">Observación:</label>
              <textarea name="observations" class="form-control" rows="3"></textarea>
            </div>
          </div>
          <div class="col-md-6">
            <h4>Sección derivación</h4>
            <div class="form-group mb-1 d-none">
              <label class="etiqueta">De:</label>
              <input type="text" class="form-control" value="">
            </div>

            <div class="form-group mb-1 simple-document">
              <label class="etiqueta">Dirigido: (*)</label>
              <select name="office_id" class="form-control select_2">
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

            <div class="mb-1 simple-document">
              <a href="#" class="text-dark" data-toggle="collapse" data-target="#cc_area">¿Desea enviar con copia?<i class="fas fa-angle-down ml-2"></i></a>
              <div id="cc_area" class="collapse">
                <div class="form-group">
                  <label class="etiqueta">CC:</label>
                  <select name="offices_cc" class="form-control select_2" multiple="multiple">
                  </select>
                </div>
              </div>
            </div>

            <div class="form-group mb-1">
              <label class="etiqueta">Plazo:</label>
              <div class="input-group desc_input">
                <input type="number" name="term" class="form-control">
                <div class="input-group-append"><span class="input-group-text">Días</span></div>
              </div>
              <div class="error-message text-danger" id="document-term-error"></div>
            </div>
            <div class="form-group mb-1">
              <label class="etiqueta">Para: (*):</label>
              <select name="order_type_id" class="form-control select_2">
                <option value="">Seleccione</option>
                @foreach($order_types as $type)
                  <option value="{{ $type->id }}">{{ $type->name }}</option>
                @endforeach
              </select>
              <div class="error-message text-danger" id="document-order_type_id-error"></div>
            </div>
            <div class="form-group mb-1">
              <label class="etiqueta">Folios:</label>
              <input type="number" name="folios" class="form-control" value="1">
            </div>
            <div class="form-group">
              <label class="etiqueta">Archivo:</label>
              <input type="file" class="form-control-file file_format text-uppercase" id="documento_pdf_field" name="attached_file" accept="application/pdf" aria-describedby="documento_pdf_helptext">
              <div id="documento_pdf_helptext" class="form-text text-muted" style="font-size: 13px;">
              Solo se admite el archivo en formato PDF, y con un tamaño maximo de 15MB.
              </div>
              <div class="error-message text-danger" id="document-attached_file-error"></div>
            </div>
          </div>
        </div>
        {!! Form::close() !!}

      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
        <button type="button" class="btn btn-success" id="answer-sent">Enviar</button>
      </div>

    </div>
  </div>
</div>
