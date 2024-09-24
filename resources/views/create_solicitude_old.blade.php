@extends ('layouts.admin')
@section ('contenido')
    <div class="row">
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <h3>Crear Solicitud Interna</h3>

        <div class="card-header">Datos del remitente</div>
        <br>
            <form id="document-form">
                {{ csrf_field() }}
            <div class="card-body">
                <div id="externo_1">
                    <div class="form-group row">
                        <label for="dni_field" class="col col-lg-2 col-md-6 col-sm-4 col-12 ">DNI</label>
                        <div class="col col-lg-4 col-md-6 col-sm-8 col-12" style="padding: 0px;">
                        <input type="text" name="identity_document" class="form-control form-control-sm externo-1" placeholder="" aria-describedby="buscar_dni_bt" required="" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="nombre_field" class="col col-lg-2 col-md-6 col-sm-4 col-12">Nombres</label>
                        <div class="col col-lg-4 col-md-6 col-sm-8 col-12" style="padding: 0px;">
                        <input type="text" name="name"  class="form-control form-control-sm externo-1 text-uppercase" placeholder="" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="apelpa_field" class="col col-lg-2 col-md-6 col-sm-4 col-12">Apellido paterno</label>
                        <div class="col col-lg-4 col-md-6 col-sm-8 col-12" style="padding: 0px;">
                        <input type="text" name="paternal_surname" class="form-control form-control-sm externo-1 text-uppercase" placeholder="" >
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="apelma_field" class="col col-lg-2 col-md-6 col-sm-4 col-12">Apellido materno</label>
                        <div class="col col-lg-4 col-md-6 col-sm-8 col-12" style="padding: 0px;">
                        <input type="text" name="maternal_surname"  class="form-control form-control-sm externo-1 text-uppercase" placeholder="" >
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="telefono_field" class="col col-lg-2 col-md-6 col-sm-4 col-12">Teléfono</label>
                    <div class="col col-lg-4 col-md-6 col-sm-8 col-12" style="padding: 0px;">
                        <input type="text" name="cellphone" class="form-control form-control-sm text-uppercase" placeholder="" >
                    </div>
                </div>

                <div class="form-group row">
                    <label for="email_field" class="col col-lg-2 col-md-6 col-sm-4 col-12">Correo electrónico</label>
                    <div class="col col-lg-4 col-md-6 col-sm-8 col-12" style="padding: 0px;">
                        <input type="email" name="email" value="{{ $user->entity->email }}" class="form-control form-control-sm text-uppercase" placeholder="" >
                    </div>
                </div>
                <!--
                <div class="form-group row">
                    <label for="direccion_field" class="col col-lg-2 col-md-6 col-sm-12 col-12">Dirección</label>
                    <div class="col col-lg-4 col-md-6 col-sm-8 col-12" style="padding: 0px;">
                        <input type="text" value="{{ $user->entity->address }}" class="form-control form-control-sm col col-lg-10 col-md-6 col-sm-12 col-12 text-uppercase" placeholder="" disabled>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="direccion_field" class="col col-lg-2 col-md-6 col-sm-12 col-12">Oficina</label>
                    <div class="col col-lg-4 col-md-6 col-sm-8 col-12" style="padding: 0px;">
                        <input type="text" value="{{ $user->entity->office->name }}" class="form-control form-control-sm col col-lg-10 col-md-6 col-sm-12 col-12 text-uppercase" placeholder="" disabled>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="direccion_field" class="col col-lg-2 col-md-6 col-sm-12 col-12">Encargado de oficina</label>
                    <div class="col col-lg-4 col-md-6 col-sm-8 col-12" style="padding: 0px;">
                        <input type="text" value="{{ $user->entity->office->entity->name }} {{ $user->entity->office->entity->paternal_surname }} {{ $user->entity->office->entity->maternal_surname }}" class="form-control form-control-sm col col-lg-10 col-md-6 col-sm-12 col-12 text-uppercase" placeholder="" disabled>
                    </div>
                </div>-->

                <div class="form-group row">
                    <label for="tipodoc_field" class="col col-lg-2 col-md-6 col-sm-4 col-12">Derivar a:</label>
                    <div class="col col-lg-4 col-md-6 col-sm-8 col-12" style="padding: 0px;">
                        <select class="form-control form-control-sm" name="office_id" aria-describedby="tipodoc_helptext">
                            <option value="">Seleccione oficina</option>
                            @foreach($offices as $office)
                            <option value="{{ $office['id'] }}">{{ $office['name'] }}</option>
                            @endforeach
                            </select>
                            <div id="tipodoc_helptext" class="form-text text-muted"></div>
                            <div class="error-message text-danger" id="document-office_id-error"></div>

                    </div>
                </div>
            </div>
            <hr>

            <div class="card-header">Datos Solicitud</div>
            <br>
            <div class="card-body">
                <div class="form-group row">
                    <label for="tipodoc_field" class="col col-lg-2 col-md-6 col-sm-4 col-12">Tipo de Documento</label>
                    <div class="col col-lg-4 col-md-6 col-sm-8 col-12" style="padding: 0px;">
                        <select class="form-control form-control-sm" id="tipodoc_field" name="document_type_id" aria-describedby="tipodoc_helptext">
                                        @foreach($document_types as $type)
                                        <option value="{{ $type['id'] }}">{{ $type['name'] }}</option>
                                        @endforeach

                                    </select>
                        <div id="tipodoc_helptext" class="form-text text-muted"></div>
                    </div>
                    <input id="tipodoc_desc_field" type="hidden" name="tdoc_descripcion" value="OFICIO">
                </div>

                <div class="form-group row">
                    <label for="documento_nrodoc_field" class="col col-lg-2 col-md-6 col-sm-4 col-12">Nº de documento</label>
                    <div class="col col-lg-4 col-md-6 col-sm-8 col-12" style="padding: 0px;">
                        <input type="text" class="form-control form-control-sm col col-lg-4 col-md-6 col-sm-8 col-12 text-uppercase" id="documento_nrodoc_field" placeholder="" name="number" value="S/N">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="documento_folios_field" class="col col-lg-2 col-md-6 col-sm-4 col-12">Nº de folios</label>
                    <div class="col col-lg-4 col-md-6 col-sm-8 col-12" style="padding: 0px;">
                        <input type="number" class="form-control form-control-sm col col-lg-4 col-md-6 col-sm-8 col-12" id="documento_folios_field" placeholder="" name="folios" value="1">
                    </div>


                </div>
                <div class="form-group row">
                    <label for="documento_desc_field" class="col col-lg-2 col-md-6 col-sm-4 col-12">Asunto</label>
                    <div class="col col-lg-10 col-md-6 col-sm-8 col-12 pl-0 pr-0" style="padding: 0px;">
                        <textarea class="form-control form-control-sm text-uppercase" id="documento_desc_field" rows="3" name="subject" maxlength="500"></textarea>
                        <div id="documento_pdf_helptext" class="form-text text-muted">
                        Maximo 500 caracteres.
                        </div>
                        <div class="error-message text-danger" id="document-subject-error"></div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="documento_obsev_field" class="col col-lg-2 col-md-6 col-sm-4 col-12">Notas y/o referencias</label>
                    <div class="col col-lg-10 col-md-6 col-sm-8 col-12 pl-0 pr-0" style="padding: 0px;">
                        <textarea class="form-control form-control-sm" id="documento_obsev_field" rows="3" name="notes" maxlength="500"></textarea>
                        <div id="documento_pdf_helptext" class="form-text text-muted">
                            Maximo 500 caracteres.
                        </div>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="documento_pdf_field" class="col col-lg-2 col-md-6 col-sm-4 col-12">Archivo</label>
                    <div class="col col-lg-10 col-md-6 col-sm-8 col-12 pl-0 pr-0" style="padding: 0px;">
                        <input type="file" class="form-control-file text-uppercase" id="documento_pdf_field" name="attached_file" accept="application/pdf" aria-describedby="documento_pdf_helptext">
                        <div id="documento_pdf_helptext" class="form-text text-muted">
                        Solo se admite el archivo en formato PDF, y con un tamaño maximo de 15MB.
                        </div>
                        <div class="error-message text-danger" id="document-attached_file-error"></div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="form-group row">
                        <label for="documento_nrodoc_field" class="col col-lg-2 col-md-6 col-sm-4 col-12">Días de prioridad</label>
                        <div class="col col-lg-4 col-md-6 col-sm-8 col-12" style="padding: 0px;">
                            <input type="text" class="form-control form-control-sm col col-lg-4 col-md-6 col-sm-8 col-12 text-uppercase" id="documento_priority_field" placeholder="" name="priority_days" value="">
                        </div>
                    </div>
                </div>

                <div class="text-right">
                    <!-- Button trigger modal -->
                    <button id="send-document" type="button" class="btn btn-primary" data-toggle="modal" data-target="#send_modal" data-backdrop="static">
                        Enviar
                    </button>
                </div>
            </div>

            </form>

        </div>
    </div>
@push ('scripts')
<script>
    $('#liSolicitude').addClass("treeview active");
</script>
<script type="text/javascript" src="/js/logged_solicitudes.js"></script>
@endpush
@endsection
