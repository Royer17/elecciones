@extends('store.layouts.index')
@section('content')

  <body>

    <div style="height: 40px;"></div>

      <div class="container ">

            <div class="row">

                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                  <div class="card shadow-lg  bg-white ">
                    <div id="form_container" class="">

                        <form id="document-form" class="" method="post" action=" ">

                            <div class="card-header">Datos del remitente</div>
                            <div class="card-body">

                                <div class="form-group row">
                                    <label for="tipo_persona_field" class="col col-lg-2 col-md-6 col-sm-4 col-12 ">Tipo de persona</label>
                                    <select class="form-control form-control-sm form-control-xs col col-lg-4 col-md-6 col-sm-8 col-12" id="tipo_persona_field" name="type_document">
                                        <option value="1">Natural</option>
                                        <option value="2">Jurídica</option>
                                    </select>
                                </div>
                                <div id="externo_1">
                                    <div class="form-group row">
                                        <label for="dni_field" class="col col-lg-2 col-md-6 col-sm-4 col-12 ">DNI</label>
                                        <div class="col col-lg-4 col-md-6 col-sm-8 col-12" style="padding: 0px;">
                                        <input type="number" class="form-control form-control-sm externo-1" id="dni_field" placeholder="" name="identity_document" aria-describedby="buscar_dni_bt" required="">
                                        <div><a href="" class="search_identity_document">Buscar</a></div>
                                        <div class="error-message text-danger" id="document-identity_document-error"></div>
                                        </div>

                                    </div>
                                    <div class="form-group row">
                                        <label for="nombre_field" class="col col-lg-2 col-md-6 col-sm-4 col-12">Nombres</label>
                                        <div class="col col-lg-4 col-md-6 col-sm-8 col-12" style="padding: 0px;">
                                        <input type="text" class="form-control form-control-sm externo-1 text-uppercase" id="nombre_field" placeholder="" name="name">
                                        <div class="error-message text-danger" id="document-name-error"></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="apelpa_field" class="col col-lg-2 col-md-6 col-sm-4 col-12">Apellido paterno</label>
                                        <div class="col col-lg-4 col-md-6 col-sm-8 col-12" style="padding: 0px;">
                                        <input type="text" class="form-control form-control-sm externo-1 text-uppercase" id="apelpa_field" placeholder="" name="paternal_surname">
                                        <div class="error-message text-danger" id="document-paternal_surname-error"></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="apelma_field" class="col col-lg-2 col-md-6 col-sm-4 col-12">Apellido materno</label>
                                        <div class="col col-lg-4 col-md-6 col-sm-8 col-12" style="padding: 0px;">
                                        <input type="text" class="form-control form-control-sm externo-1 text-uppercase" id="apelma_field" placeholder="" name="maternal_surname">
                                        <div class="error-message text-danger" id="document-maternal_surname-error"></div>
                                        </div>
                                    </div>


                                </div>
                                <div id="externo_2" class="" style="display: none;">
                                    <div class="form-group row">
                                        <label for="ruc_field" class="col col-lg-2 col-md-6 col-sm-8 col-12">RUC</label>
                                        <div class="col col-lg-4 col-md-6 col-sm-8 col-12" style="padding: 0px;">
                                        <input type="number" class="form-control form-control-sm externo-2 text-uppercase" id="ruc_field" placeholder="" name="ruc">
                                        <div><a href="" class="search_identity_document">Buscar</a></div>
                                        <div class="error-message text-danger" id="document-ruc-error"></div>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label for="razon_social_field" class="col col-lg-2 col-md-6 col-sm-8 col-12">Razón Social</label>
                                        <div class="col col-lg-4 col-md-6 col-sm-8 col-12" style="padding: 0px;">
                                        <input type="text" class="form-control form-control-sm externo-2 text-uppercase" id="razon_social_field" placeholder="" name="business_name">
                                        <div class="error-message text-danger" id="document-business_name-error"></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="telefono_field" class="col col-lg-2 col-md-6 col-sm-4 col-12">Teléfono</label>
                                    <div class="col col-lg-4 col-md-6 col-sm-8 col-12" style="padding: 0px;">
                                    <input type="text" class="form-control form-control-sm text-uppercase" id="telefono_field" placeholder="" name="cellphone">
                                    <div class="error-message text-danger" id="document-cellphone-error"></div>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="email_field" class="col col-lg-2 col-md-6 col-sm-4 col-12">Correo electrónico</label>
                                    <div class="col col-lg-4 col-md-6 col-sm-8 col-12" style="padding: 0px;">
                                    <input type="email" class="form-control form-control-sm text-uppercase" id="email_field" placeholder="" name="email">
                                    <div class="error-message text-danger" id="document-email-error"></div>
                                    </div>
                                </div>
                                <!--
                                <div class="form-group row">
                                    <label for="direccion_field" class="col col-lg-2 col-md-6 col-sm-12 col-12">Dirección</label>
                                    <input type="text" class="form-control form-control-sm col col-lg-10 col-md-6 col-sm-12 col-12 text-uppercase" id="direccion_field" placeholder="" name="address">
                                </div> -->
                            </div>

                            <div class="card-header">Datos del documento</div>
                            <div class="card-body">
                                <div class="form-group row">
                                    <label for="tipodoc_field" class="col col-lg-2 col-md-6 col-sm-4 col-12">Tipo documento</label>
                                    <div class="col col-lg-6 col-md-6 col-sm-8 col-12 pl-0 pr-0">
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
                                    <input type="text" class="form-control form-control-sm col col-lg-4 col-md-6 col-sm-8 col-12 text-uppercase" id="documento_nrodoc_field" placeholder="" name="number" value="S/N">
                                </div>
                                <div class="form-group row">
                                    <label for="documento_folios_field" class="col col-lg-2 col-md-6 col-sm-4 col-12">Nº de folios</label>
                                    <input type="number" class="form-control form-control-sm col col-lg-4 col-md-6 col-sm-8 col-12" id="documento_folios_field" placeholder="" name="folios" value="1">
                                </div>

                               <div class="form-group row">
                                    <label for="documento_desc_field" class="col col-lg-2 col-md-6 col-sm-4 col-12">Asunto</label>
                                    <div class="col col-lg-10 col-md-6 col-sm-8 col-12 pl-0 pr-0">
                                        <textarea class="form-control form-control-sm text-uppercase" id="documento_desc_field" rows="3" name="subject" maxlength="500"></textarea>
                                        <div id="documento_pdf_helptext" class="form-text text-muted">
                                        Máximo 500 caracteres.
                                        </div>
                                        <div class="error-message text-danger" id="document-subject-error"></div>
                                    </div>
                               </div>

                               <div class="form-group row">
                                    <label for="documento_obsev_field" class="col col-lg-2 col-md-6 col-sm-4 col-12">Observaciones </label>
                                    <div class="col col-lg-10 col-md-6 col-sm-8 col-12 pl-0 pr-0">
                                        <textarea class="form-control form-control-sm" id="documento_obsev_field" rows="3" name="notes" maxlength="500"></textarea>
                                        <div id="documento_pdf_helptext" class="form-text text-muted">
                                            Máximo 500 caracteres.
                                        </div>
                                    </div>
                                </div>

                                <!--<div class="form-group row">
                                    <div class="col col-lg-2 col-md-6 col-sm-4 col-12"></div>
                                    <div class="col pl-0">
                                        <div class="alert alert-warning" role="alert">
                                            <b>Importante:</b>
                                            El documento PDF, tiene
                                        </div>
                                    </div>
                                </div>-->
                                <div class="form-group row">
                                    <label for="documento_pdf_field" class="col col-lg-2 col-md-6 col-sm-4 col-12">Archivo</label>
                                    <div class="col col-lg-10 col-md-6 col-sm-8 col-12 pl-0 pr-0">
                                        <input type="file" class="form-control-file text-uppercase" id="documento_pdf_field" name="attached_file" accept="application/pdf" aria-describedby="documento_pdf_helptext">
                                        <div id="documento_pdf_helptext" class="form-text text-muted">
                                        Sólo se admite el archivo en formato PDF, y con un tamaño máximo de 15MB.
                                        </div>
                                        <div class="error-message text-danger" id="document-attached_file-error"></div>
                                    </div>
                                </div>
                                <!--
                                <div class="form-group row">
                                    <div class="col col-lg-2 col-md-6 col-sm-4 col-12"></div>
                                    <div id="gr_container" class="col pl-0"><div style="width: 304px; height: 78px;"><div>
                                    <iframe src="https://www.google.com/recaptcha/api2/anchor?ar=1&amp;k=6LdrfPQUAAAAAOPd3Wz27cS_yGAjnwNdTrzUpCq0&amp;co=aHR0cDovL21lc2FkZXBhcnRlcy5yZWdpb250YWNuYS5nb2IucGU6ODA.&amp;hl=es-419&amp;v=6TWYOsKNtRFaLeFqv5xN42-l&amp;size=normal&amp;cb=krjjulqyhhh9" width="304" height="78" role="presentation" name="a-u5el37tn6h59" frameborder="0" scrolling="no" sandbox="allow-forms allow-popups allow-same-origin allow-scripts allow-top-navigation allow-modals allow-popups-to-escape-sandbox"></iframe></div><textarea id="g-recaptcha-response" name="g-recaptcha-response" class="g-recaptcha-response" style="width: 250px; height: 40px; border: 1px solid rgb(193, 193, 193); margin: 10px 25px; padding: 0px; resize: none; display: none;"></textarea></div><iframe style="display: none;"></iframe></div>
                                </div>
                                <div id="error_alert" class="alert alert-danger" role="alert" style="display: none;">
                                Error
                                </div>  --->
                                <div class="text-right">
                                    <!-- Button trigger modal -->
                                    <button id="send-document" type="button" class="btn btn-primary" data-toggle="modal" data-target="#send_modal" data-backdrop="static">
                                        Enviar
                                    </button>
                                </div>
                            </div>

                        </form>
                        <form id="request-completed-form" action="/solicitud-enviada" method="POST" style="display: none;">
                            {{ csrf_field() }}
                            <input type="hidden" id="order_id" name="order_id">
                        </form>
                    </div>
                  </div>

                </div>
            </div>
        </div>

  </body>
@section('plugins-js')

<script type="text/javascript">

    document.querySelector(`#send-document`)
        .addEventListener('click', () => {
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

        if (document.querySelector(`#tipo_persona_field`).value == 1) {
            if (!document.querySelector(`#dni_field`).value) {
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
                    document.querySelector(`input[name="address"]`).value = response.data.entity.address;
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
                      `No se ha encontado el DNI.`,
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

            if (!document.querySelector(`#ruc_field`).value) {
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
                    document.querySelector(`input[name="address"]`).value = response.data.entity.address;
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
                        console.log(err);
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
