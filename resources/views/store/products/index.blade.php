@extends('store.layouts.index')
@section('content')
<div class="container py-4">
  <div class="card">
    <div class="card-body">
      <form id="document-form" class="" method="post" action=" ">
        <div class="row">
          <div class="col-md-6">
            <div class="card mb-2">
              <div class="card-header text-white bg-dark"><h5>Datos del remitente</h5></div>
              <div class="card-body bg-light">
                <div class="form-group mb-1">
                  <label class="etiqueta">Tipo de persona:</label>
                  <select class="form-control form-control-sm" id="tipo_persona_field" name="type_document">
                    <option value="1">Natural</option>
                    <option value="2">Jurídica</option>
                  </select>
                </div>

                <div id="externo_1">
                  <div class="form-group">
                    <label for="dni_field" class="etiqueta">DNI:</label>
                    <div class="input-group input-group-sm">
                      <input type="text" class="form-control externo-1" id="dni_field" placeholder="" name="identity_document" aria-describedby="buscar_dni_bt" required="">
                      <div class="input-group-append">
                        <a href="" class="btn btn-info search_identity_document" title="Buscar"><i class="fa fa-search"></i></a>
                      </div>
                    </div>
                    <div class="error-message text-danger" id="document-identity_document-error"></div>
                  </div>
                  <div class="form-group mb-1">
                    <label for="nombre_field" class="etiqueta">Nombres:</label>
                    <input type="text" class="form-control form-control-sm externo-1 text-uppercase" id="nombre_field" placeholder="" name="name">
                    <div class="error-message text-danger" id="document-name-error"></div>
                  </div>
                  <div class="form-group mb-1">
                    <label for="apelpa_field" class="etiqueta">Apellido paterno:</label>
                    <input type="text" class="form-control form-control-sm externo-1 text-uppercase" id="apelpa_field" placeholder="" name="paternal_surname">
                    <div class="error-message text-danger" id="document-paternal_surname-error"></div>
                  </div>
                  <div class="form-group mb-1">
                    <label for="apelma_field" class="etiqueta">Apellido materno:</label>
                    <input type="text" class="form-control form-control-sm externo-1 text-uppercase" id="apelma_field" placeholder="" name="maternal_surname">
                    <div class="error-message text-danger" id="document-maternal_surname-error"></div>
                  </div>
                </div>

                <div id="externo_2" class="" style="display: none;">
                  <div class="form-group mb-1">
                    <label for="ruc_field" class="etiqueta">RUC:</label>
                    <div class="input-group input-group-sm">
                      <input type="text" class="form-control externo-2 text-uppercase" id="ruc_field" placeholder="" name="ruc">
                      <div class="input-group-append">
                        <a href="" class="btn btn-info search_identity_document" title="Buscar"><i class="fa fa-search"></i></a>
                      </div>
                    </div>
                    <div class="error-message text-danger" id="document-ruc-error"></div>
                  </div>
                  <div class="form-group mb-1">
                    <label for="razon_social_field" class="etiqueta">Razón Social:</label>
                    <input type="text" class="form-control form-control-sm externo-2 text-uppercase" id="razon_social_field" placeholder="" name="business_name">
                    <div class="error-message text-danger" id="document-business_name-error"></div>
                  </div>
                </div>

                <div class="form-group mb-1">
                  <label for="telefono_field" class="etiqueta">Teléfono:</label>
                  <input type="text" class="form-control form-control-sm text-uppercase" id="telefono_field" placeholder="" name="cellphone">
                  <div class="error-message text-danger" id="document-cellphone-error"></div>
                </div>
                <div class="form-group mb-0">
                  <label for="email_field" class="etiqueta">Correo electrónico:</label>
                  <input type="text" class="form-control form-control-sm text-uppercase" id="email_field" placeholder="" name="email">
                  <div class="error-message text-danger" id="document-email-error"></div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card mb-2">
              <div class="card-header text-white bg-dark"><h5>Datos del documento</h5></div>
              <div class="card-body bg-light">
                <div class="form-group mb-1">
                  <label for="tipodoc_field" class="etiqueta">Tipo de documento:</label>
                  <select class="form-control form-control-sm select_2" id="tipodoc_field" name="document_type_id" aria-describedby="tipodoc_helptext">
                    @foreach($document_types as $type)
                    <option value="{{ $type['id'] }}">{{ $type['name'] }}</option>
                    @endforeach
                  </select>
                  <div id="tipodoc_helptext" class="form-text text-muted"></div>
                  <input id="tipodoc_desc_field" type="hidden" name="tdoc_descripcion" value="OFICIO">
                </div>
                <div class="row">
                  <div class="col-md-6 pr-md-1">
                    <div class="form-group mb-1">
                      <label for="documento_nrodoc_field" class="etiqueta">Nº de documento:</label>
                      <input type="text" class="form-control form-control-sm text-uppercase" id="documento_nrodoc_field" placeholder="" name="number" value="S/N">
                    </div>
                  </div>
                  <div class="col-md-6 pl-md-1">
                    <div class="form-group mb-1">
                      <label for="documento_folios_field" class="etiqueta">Nº de folios:</label>
                      <input type="text" class="form-control form-control-sm" id="documento_folios_field" placeholder="" name="folios" value="1">
                    </div>
                  </div>
                </div>
                <div class="form-group mb-1">
                  <label class="etiqueta">Tipo de procedimiento:</label>
                  <div class="row">
                    <div class="col pr-0">
                      <select class="form-control form-control-sm select_2" id="tipo_persona_field" name="tupa_id">
                        <option value="">No TUPA</option>
                        <option value=1>TUPA</option>
                      </select>
                    </div>
                    <div class="col-auto tupa-requirements"><a href="/requisitos-formatos" class="btn btn-primary btn-sm" target="_blank">Requisitos y Formatos</a></div>
                  </div>
                    <div class="error-message text-danger" id="document-tupa_id-error"></div>
                </div>

                <div class="form-group mb-1">
                  <label for="documento_desc_field" class="etiqueta">Asunto:</label>
                  <textarea class="form-control form-control-sm text-uppercase" id="documento_desc_field" rows="2" name="subject" maxlength="500"></textarea>
                  <span class="text-muted" style="font-size: 11px;">Máximo 500 caracteres.</span>
                  <div class="error-message text-danger" id="document-subject-error"></div>
                </div>
                <div class="form-group mb-1">
                  <label for="documento_obsev_field" class="etiqueta">Observaciones:</label>
                  <textarea class="form-control form-control-sm" id="documento_obsev_field" rows="2" name="notes" maxlength="500"></textarea>
                  <span class="text-muted" style="font-size: 11px;">Máximo 500 caracteres.</span>
                </div>
                <div class="form-group mb-0">
                  <label for="documento_pdf_field" class="etiqueta">Archivo:</label>
                  <div class="">
                    <input type="file" class="form-control-file file_format text-uppercase" id="documento_pdf_field" name="attached_file" accept="application/pdf" aria-describedby="documento_pdf_helptext">
                    <!-- <label class="custom-file-label" for="documento_pdf_field" data-browse="Seleccionar Archivo"></label> -->
                  </div>
                  <span class="text-muted" style="font-size: 11px;">Sólo se admite el archivo en formato PDF, y con un tamaño máximo de 15MB.</span>
                  <div class="error-message text-danger" id="document-attached_file-error"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="text-center pt-4">
          <a href="#" id="send-document" class="btn btn-success font-bold" data-toggle="modal" data-target="#send_modal" data-backdrop="static">ENVIAR</a>
        </div>
      </form>
      <form id="request-completed-form" action="/solicitud-enviada" method="POST" style="display: none;">
          {{ csrf_field() }}
          <input type="hidden" id="order_id" name="order_id">
      </form>
    </div>
  </div>
</div>
@section('plugins-js')

<script type="text/javascript">

    function getExtension(filename) {
      var parts = filename.split('.');
      return parts[parts.length - 1];
    }

    document.querySelector(`#send-document`)
        .addEventListener('click', () => {

            if (document.querySelector('input[name="attached_file"]').value) {
                var ext = getExtension(document.querySelector('input[name="attached_file"]').value);

                if (ext != "pdf") {
                    notice(`Advertencia`, `Sólo están permitidos archivos PDF.`, `warning`);
                    return;
                }
            }

            lockWindow();

            $(`.error-message`).empty();
            let _formData = new FormData($(`#document-form`)[0]);
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('input[name=_token]').val()
                    }
                });
                $.ajax({
                    url : `/order`,
                    type: 'POST',
                    data: _formData,
                    contentType: false,
                    processData: false,
                    success: function(e){
                        unlockWindow();
                        //notice(`${e.title}`, `${e.message}`, `success`);
                        
                        $(`#document-form`)[0].reset();
                        $(`#order_id`).val(e.id);
                        $(`#request-completed-form`).submit();
                    },
                    error:function(jqXHR, textStatus, errorThrown)
                    {
                        unlockWindow();
                        if (jqXHR.responseJSON.hasOwnProperty('type')) {
                          notice(`${jqXHR.responseJSON.title}`, `${jqXHR.responseJSON.message}`, `warning`);
                          return;
                        }

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

    $(`.tupa-requirements`).hide();

    $(`select[name="tupa_id"]`).on('change', function(e){
        if (e.target.value == "") {
            $(`.tupa-requirements`).hide();
            return;
        }
        $(`.tupa-requirements`).show();

    });

    $(`#tipo_persona_field`).on('change', function(e){
        if (e.target.value == 1) {
            $(`#externo_1`).show();
            $(`#externo_2`).hide();
            document.querySelector(`input[name="address"]`).value = "";

        } else {
            $(`#externo_2`).show();
            $(`#externo_1`).hide();
        }
    });


    $(`.search_identity_document`).on('click', function(e){
        e.preventDefault();

        document.querySelector(`input[name="name"]`).value = "";
        document.querySelector(`input[name="paternal_surname"]`).value = "";
        document.querySelector(`input[name="maternal_surname"]`).value = "";
        document.querySelector(`input[name="cellphone"]`).value = "";
        document.querySelector(`input[name="email"]`).value = "";
        document.querySelector(`#razon_social_field`).value = "";

        if (document.querySelector(`#tipo_persona_field`).value == 1) {
            if (!document.querySelector(`#dni_field`).value || document.querySelector(`#dni_field`).value.length != 8) {
                //alert(`Especifique un DNI válido.`);
                Swal.fire(
                  '',
                  'Especifíque un DNI válido.',
                  'warning'
                )

                return;
            }

            lockWindow();

            axios.get(`/entity/${document.querySelector(`#dni_field`).value}/detail`)
            .then((response) => {
                if (response.data.success) {
                    document.querySelector(`input[name="name"]`).value = response.data.entity.name;
                    document.querySelector(`input[name="paternal_surname"]`).value = response.data.entity.paternal_surname;
                    document.querySelector(`input[name="maternal_surname"]`).value = response.data.entity.maternal_surname;
                    document.querySelector(`input[name="cellphone"]`).value = response.data.entity.cellphone;
                    document.querySelector(`input[name="email"]`).value = response.data.entity.email;
                    // document.querySelector(`input[name="address"]`).value = response.data.entity.address;
                    unlockWindow();
                    return;
                }

                axios.get(`https://dniruc.apisperu.com/api/v1/dni/${document.querySelector(`#dni_field`).value}?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6InJveWVyLmpha0BnbWFpbC5jb20ifQ.OblQV2df3iMDJbRHx3o_342AKsP1Xp3vNoql3WK6jV4`, {timeout: 5000})
                .then((response) => {
                    document.querySelector(`input[name="name"]`).value = response.data.nombres;
                    document.querySelector(`input[name="paternal_surname"]`).value = response.data.apellidoPaterno;
                    document.querySelector(`input[name="maternal_surname"]`).value = response.data.apellidoMaterno;
                    unlockWindow();
                })
                .catch((err) => {
                    unlockWindow();
                    Swal.fire(
                      '',
                      `No se ha encontrado el DNI.`,
                      'warning'
                    )
                    return;
                });
                //use the api
            })
            .catch((err) => {
                unlockWindow();
                Swal.fire(
                  '',
                  `Ha ocurrido un error.`,
                  'warning'
                )
                return;
            });


        } else {

            if (!document.querySelector(`#ruc_field`).value || document.querySelector(`#ruc_field`).value.length != 11) {
                //alert(`Especifique un DNI válido.`);
                Swal.fire(
                  '',
                  'Especifíque un RUC válido.',
                  'warning'
                )
                return;
            }

            lockWindow();

            axios.get(`/entity/${document.querySelector(`#ruc_field`).value}/detail`)
            .then((response) => {
                if (response.data.success) {
                    document.querySelector(`input[name="business_name"]`).value = response.data.entity.name;
                    document.querySelector(`input[name="cellphone"]`).value = response.data.entity.cellphone;
                    document.querySelector(`input[name="email"]`).value = response.data.entity.email;
                    // document.querySelector(`input[name="address"]`).value = response.data.entity.address;
                    unlockWindow();
                    return;
                }

                axios.get(`https://dniruc.apisperu.com/api/v1/ruc/${document.querySelector(`#ruc_field`).value}?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6InJveWVyLmpha0BnbWFpbC5jb20ifQ.OblQV2df3iMDJbRHx3o_342AKsP1Xp3vNoql3WK6jV4`, {timeout: 5000})
                    .then((response) => {
                        unlockWindow();
                        document.querySelector(`#razon_social_field`).value = response.data.razonSocial;
                        //document.querySelector(`#direccion_field`).value = response.data.direccion;

                    })
                    .catch((err) => {
                        unlockWindow();
                        Swal.fire(
                          '',
                          `No se ha encontrado el RUC.`,
                          'warning'
                        )
                        return;
                    });
                //use the api
            })
            .catch((err) => {

                unlockWindow();
                Swal.fire(
                  '',
                  `Ha ocurrido un error.`,
                  'warning'
                )
                return;
            });


        }

    });

    // document.querySelector(`.awdwad`).addEventListener(`click`, (event) => {
    //     event.preventDefault();


    //     return;

    //     lockWindow();
    //     let  url = `http://mesadepartes.regiontacna.gob.pe/mpv/consulta_reniec?numero=${document.querySelector(`#dni_field`).value}`;

    //     // fetch(proxyurl + url)
    //     // .then(response => response.text())
    //     // .then(contents => console.log(contents))
    //     // .catch(() => console.log("Can’t access " + url + " response. Blocked by browser?"))
    //     axios.get(proxyurl+url)
    //         .then((response) => {
    //             unlockWindow();
    //             if (response.data.success) {
    //                 document.querySelector(`input[name="name"]`).value = response.data.data.nombres;
    //                 document.querySelector(`input[name="paternal_surname"]`).value = response.data.data.paterno;
    //                 document.querySelector(`input[name="maternal_surname"]`).value = response.data.data.materno;
    //                 document.querySelector(`input[name="address"]`).value = response.data.data.direccion;
    //                 return;
    //             }
    //             Swal.fire(
    //               '',
    //               `El DNI no es válido.`,
    //               'warning'
    //             )
    //             return;
    //         });
    // });
</script>

@stop

@stop
