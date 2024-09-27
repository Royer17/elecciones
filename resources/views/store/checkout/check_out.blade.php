@extends('store.layouts.index')
@section('content')

<body>

    <div class="container cont_pad">
      <a href="{{ url('/') }}">
          <h3 class="text-uppercase font-bold">Primera elección municipal escolar</h3 class="text-uppercase font-bold">
      </a>

        <div class="card shadow-lg bg-white mt-4">
            <div class="form-container">
                <div class="card-header">Sistema de votación</div>
                <div class="card-body">
                     <div id="form_ct" class="container small mt-3" style="" onkeydown="return event.key != 'Enter';">
                        <form id="search-form" class="needs-validation">
                            {{ csrf_field() }}
                            <!-- <div class="form-group row">
                                <label for="anio_field" class=" text-right col col-lg-2 col-md-4 col-sm-6 col-6">Año:</label>
                                <select class="form-control form-control-sm col col-lg-4 col-md-8 col-sm-6 col-6" id="anio_field" name="year">

                                </select>
                            </div> -->
                            {{--
                            <div class="form-group row">
                                <label for="cud_user_field" class="text-right col col-lg-3 col-md-4 col-sm-6 col-6">Por DNI , RUC o Código Registro</label>
                                <input type="text" class="form-control form-control-sm col col-lg-4 col-md-8 col-sm-6 col-6 text-uppercase" id="cud_user_field" placeholder="" name="identity_document" value="" min="0">
                            </div>

                            <div class="row">
                                <div class="col-md-2"></div>
                                <div class="form-group col-md-4">
                                    <label>Nro. de Hoja de Ruta</label>
                                    <input type="text" class="form-control" name="identity_document">
                                </div>
                                <div class="form-group col-md-4">
                                    <label>Nro. de Hoja de Ruta</label>
                                    <select class="form-control" name="year">
                                        @foreach($years as $year)
                                            <option value="{{ $year->year }}">{{ $year->year }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2"></div>
                            </div>
                            --}}

                            <div class="form-group row align-items-center d-none">
                                <label for="cud_user_field" class="text_input col-lg-3 col-md-4 col-sm-6 col-12 mb-1">Ingresa tu código:</label>
                                <input type="text" class="form-control form-control-sm col-lg-4 col-md-8 col-sm-6 col-12 text-uppercase mb-1" id="cud_user_field" placeholder="Ej. 20220000010" name="code" value="">
                            </div>
                            <div class="form-group row align-items-center">
                                <label for="cud_user_field" class="text_input col-lg-3 col-md-4 col-sm-6 col-12 mb-1">DNI:</label>
                                <input type="text" class="form-control form-control-sm col-lg-4 col-md-8 col-sm-6 col-12 text-uppercase mb-1" placeholder="########" name="identity_document">
                                {{--
                                <select class="form-control form-control-sm col col-lg-4 col-md-8 col-sm-6 col-6" name="year">
                                </select>
                                --}}
                            </div>
                            <div class="form-group row">
                                <div class="col-md-3"></div>
                                <!-- <div class="g-recaptcha" data-sitekey="6LfyY0ggAAAAAJ8vf-9u61Fok643berXn-8qUjEY" data-callback="verifyRecaptchaCallback" data-expired-callback="expiredRecaptchaCallback"></div> -->

                                <!-- <div class="g-recaptcha" data-sitekey="6LedaUggAAAAAGXusqkKB3sicj_y0oc2pElwEWWH" data-callback="verifyRecaptchaCallback" data-expired-callback="expiredRecaptchaCallback"></div> -->

                                <!-- <input class="form-control d-none" data-recaptcha="true" required data-error="Please complete the Captcha"> -->
                                <div class="help-block with-errors"></div>
                                <div class="col-md-2"></div>
                            </div>

<!--                             <div class="form-group row">
                                <label for="cud_user_field" class="text-right col col-lg-2 col-md-4 col-sm-6 col-6">Por Nombre y Apellido</label>
                                <input type="text" class="form-control form-control-sm col col-lg-4 col-md-8 col-sm-6 col-6 text-uppercase" id="cud_user_field" placeholder="" name="name" value="" min="0">
                            </div> -->

                            <div class="h4 row">

                            </div>
                            <input id="cud_field" type="hidden" name="cud" value="">

                            <div id="error_alert" class="alert alert-danger" role="alert" style="display: none;">
                                Error
                            </div>
                            <div class="row">
                                <div class="col-3 text-right">

                                </div>

                                <div class="col-6">
                                        <button id="consultar_bt" type="button" class="btn btn-primary">Ingresar</button>
                                </div>

                            </div>
                        </form>
                        <form action="/cartilla" method="POST" id="details-form" style="display: none;">
                            {{ csrf_field() }}
                            <input type="hidden" name="order_id" value="">
                            <input type="hidden" name="entity_id" value="">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="result_ct" class="container mt-1 small" style="display: none; margin-bottom: 200px;"></div>
    <br><br>

@stop

@section('plugins-css')
<style type="text/css">
  .cont_pad {
    padding-top: 40px;
    padding-bottom: 40px;
  }
  .body_2022 .footer {
    position: absolute;
    width: 100%;
    bottom: 0;
  }
  .text_input {text-align: right;}
  @media (max-width: 576px) {
    .text_input {
      text-align: left;
      padding-left: 0px;
    }
  }
</style>
@stop

@section('plugins-js')

<script src='https://www.google.com/recaptcha/api.js'></script>
<script type="text/javascript">

    document.querySelector(`#consultar_bt`)
        .addEventListener('click', () => {
            lockWindow();
            $(`.error-message`).empty();

            if (!$(`input[name="identity_document"]`).val()) {
                notice(`Advertencia`, `Escriba su DNI.`, `warning`);
                unlockWindow();
                return;
            }

            // if (!$(`input[name="code"]`).val()) {
            //     notice(`Advertencia`, `Escriba su código.`, `warning`);
            //     unlockWindow();
            //     return;
            // }


            // if (!grecaptcha.getResponse()) {
            //     notice(`Advertencia`, `Valide el recaptcha`, `warning`);
            //     unlockWindow();
            //     return;
            // }

            let _formData = new FormData($(`#search-form`)[0]);
            //_formData.append('recaptcha', grecaptcha.getResponse());
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('input[name=_token]').val()
                    }
                });
                $.ajax({
                    url : `/search`,
                    type: 'POST',
                    data: _formData,
                    contentType: false,
                    processData: false,
                    success: function(e){
                        unlockWindow();
                        document.querySelector(`#details-form input[name="order_id"]`).value = e.order_id;
                        document.querySelector(`#details-form input[name="entity_id"]`).value = e.entity_id;
                        $(`#details-form`).submit();

                    },
                    error:function(jqXHR, textStatus, errorThrown)
                    {
                        notice(`${jqXHR.responseJSON.title}`, `${jqXHR.responseJSON.message}`, `warning`);
                        unlockWindow();
                    }
                });

        });

</script>

<script type="text/javascript">
	$(document).ready(function(){
			$("#barra_detalle").addClass("py-2 justify-content-end");
			$("#barra_detalle").html("<div class='col-auto'><a href='/votacion' class='btn btn-warning'><b>Inicio</b></a></div>");
	});
</script>
@stop
