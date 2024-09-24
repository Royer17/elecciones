@extends('store.layouts.index')
@section('content')

<div class="row justify-content-center mx-0 completed_file">
  <div class="py-4 col-xl-8 col-lg-9 col-md-10 col-sm-11">
    <div class="card">
      <div class="card-body">
        <div class="logo_doc mb-4">
          <img src="/img/logo.png" alt="">
          <p>
            <span class="logo_title">Municipalidad Distrital de Pachia</span><br>
            <span class="logo_subtitle">Mesa de partes</span>
          </p>
        </div>
        <div class="text-center title_file">SOLICITUD ENVIADA CORRECTAMENTE</div>
        <hr>
        <div class="text-center mb-4"><h6>Se ha procesado su solicitud. Su Código Solicitud es: <b>{{ $order->code }}</b></h6></div>
        <input type="hidden" name="" id="order_code" value="{{ $order->code }}">
        <div class="px-md-3">
          <div class="row mx-0">
            <div class="col-md-6 px-1 mb-3">
              <label class="mb-0 w-100">Solicitante:</label>
              <h6 class="text-uppercase"><b>{{ $order->entity->name }} {{ $order->entity->paternal_surname }} {{ $order->entity->maternal_surname }}</b></h6>
            </div>
            <div class="col-md-3 px-1 mb-3">
              <label class="mb-0 w-100">DNI/RUC:</label>
              <h6 class="text-uppercase"><b>{{ $order->entity->identity_document }}</b></h6>
            </div>
            <div class="col-md-3 px-1 mb-3">
              <label class="mb-0 w-100">Fecha y hora:</label>
              <h6 class="text-uppercase"><b>{{ \Carbon\Carbon::parse($order->date)->format('d/m/Y H:i:s') }}</b></h6>
            </div>
          </div>
          <div class="px-1 mb-3">
            <label class="mb-0 w-100">Asunto:</label>
            <h6 class="text-uppercase"><b>{{ $order->subject }}</b></h6>
          </div>
          <div class="px-1 mb-3">
            <div class="alert alert-primary" role="alert">Si desea, podemos enviarle la información generada a su correo electrónico:</div>
            <div class="col-md-5 px-0">
              <div class="input-group">
                <input type="email" name="" id="to_email" class="form-control" placeholder="Ingrese correo">
                <div class="input-group-append">
                  <button class="btn btn-success" id="send_email">Enviar</button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="text-center mb-2">
          <form method="POST" action="/constancia" id="constancia_form" target="_blank">
            {{ csrf_field() }}
            <input type="hidden" name="index" value="{{ $order->id }}">
          </form>

          <!-- <a href="/" class="btn btn-outline-dark mb-2 mr-sm-1">Regresar</a> -->
            <button type="button" class="btn btn-outline-dark mb-2 mr-sm-1" id="constancia_submit">Constancia</button>
            <a href="/" class="btn btn-outline-primary mb-2 ml-sm-1">Regresar</a>
        </div>
      </div>

    </div>
  </div>
</div>

{{--
    <!-- Product Section Begin -->
    <section class="product spad header-shadow">
        <div class="container">

            <div class="row">
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div id="form_container" class="container">
                        <div class="card-header">Se ha procesado su solicitud. Su Código Solicitud es: {{ $order->code }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Product Section End -->
--}}
@stop

@section('plugins-js')
  <script type="text/javascript">
    document.querySelector('#constancia_submit')
      .addEventListener('click', () => {
        document.querySelector('#constancia_form').submit();
      });

    document.querySelector('#send_email')
      .addEventListener('click', (e) => {
        e.preventDefault();
        if (/^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(document.querySelector('#to_email').value)) {

          lockWindow();
          axios.post(`/solicitud-enviada-email`, {
            email: document.querySelector('#to_email').value,
            order_code: document.querySelector('#order_code').value,
            order_id: document.querySelector('#constancia_form input[name="index"]').value,
          }).then((response) => {
            unlockWindow();
            Swal.fire(response.data.title, response.data.message, 'success');
          }).catch((error) => {
            //unlockWindow();
            console.error(error);
          });
          return;
        }
        Swal.fire('Error de email', 'No es un email válido', 'warning');


      });

  </script>

@stop
