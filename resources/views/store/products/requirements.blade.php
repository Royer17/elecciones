@extends('store.layouts.index')
@section('content')
<div class="container py-4">
  <div class="card">
    <div class="card-body">
      <div class="px-md-5 py-4">
        
        <section class="d-flex justify-content-between mb-2">
          <figure>
            <img src="{{ $company->logo }}" width="50">
          </figure>
          <article>
            <h2 class="text-center text-uppercase font-bold">Alcaldes y regidores</h2>
            <h4 class="text-center text-uppercase">{{ $entity->paternal_surname }} {{ $entity->maternal_surname }}, {{ $entity->name }}</h4>
            <h5 class="text-center text-uppercase">DNI: <span class="font-bold">{{ $entity->identity_document }}</span>, Nivel: <span class="font-bold">{{ $nivel }}</span> </h5>    
          </article>
          
          <figure>
            <img src="{{ $company->logo }}" width="50">
          </figure>
        </section>
        @if($order->voted)
        <section>
          <p class="alert alert-success h3">Usted ya ha votado</p>
        </section>
        @endif
        <section>
          <p class="text-center text-uppercase text-white bg-dark h2 p-2 font-bold mb-0">Primera elección municipal escolar</p>
          <p class="text-center text-uppercase h4 text-dark p-1" style="background:#c1bdbd;">Presione el símbolo, número u opción de su preferencia</p>
        </section>

        <section>
          
          @foreach($candidates as $candidate)
          <article data-index="{{ $candidate->id }}" class="d-flex justify-content-between border align-items-center px-4 py-2 card-candidate mb-2" style="background: #d6e6f5;">
            <p class="text-uppercase" style="pointer-events: none;">{{ $candidate->lastname }} {{ $candidate->firstname }}</p>
            <div>
              <img src="{{ $candidate->photo }}" width="70" style="pointer-events: none;">
              <img src="{{ $candidate->logo }}" width="70" style="pointer-events: none;">

            </div>
          </article>
          @endforeach
          {{-- 
          <article class="d-flex justify-content-between border align-items-center px-4 py-2 card-candidate mb-2" style="background: #d6e6f5;">
            <p class="text-uppercase">Donald Trump</p>
            <div>
              <img src="/img/user_icon.png" width="70">
              <img src="/img/user_icon.png" width="70">

            </div>
          </article>
          <article class="d-flex justify-content-between border align-items-center px-4 py-2 card-candidate mb-2" style="background: #d6e6f5;">
            <p class="text-uppercase">Donald Trump</p>
            <div>
              <img src="/img/user_icon.png" width="70">
              <img src="/img/user_icon.png" width="70">

            </div>
          </article>
          --}}
        </section>
        
        <section>
          <form id="form-vote">
            {{ csrf_field() }}
            <input type="hidden" name="index" value="{{ $order->id }}">
          </form>
          @if($order->voted == false)
            <button type="button" class="btn btn-info btn-lg pull-right" id="send-vote-btn">Enviar</button>
          @endif
        </section>
      </div>
    </div>
  </div>
</div>
@stop

@section('plugins-css')
  <style type="text/css">
      .card-candidate:hover {
        background: #c83e3a !important;
        cursor: pointer;
        
      }

      .card-candidate:hover p {
        color: white !important;
      }
  </style>
@stop

@section('plugins-js')

<script src='https://www.google.com/recaptcha/api.js'></script>
<script type="text/javascript">

      let candidate_id = null;

      $('.card-candidate').on('click', function(e){
        document.querySelectorAll('.card-candidate').forEach(card => {
            card.style.background = '#d6e6f5';
            card.querySelector('p').style.color = '#6f6f6f';
        });

        candidate_id = $(e.target).data('index');
        console.log(candidate_id);
        $(e.target).css('background', '#c83e3a');
        $(e.target).find('p').css('color', 'white');
      });

      document.querySelector(`#send-vote-btn`)
        .addEventListener('click', () => {
            lockWindow();
            $(`.error-message`).empty();

            if (!candidate_id) {
                notice(`Advertencia`, `Seleccione un candidato.`, `warning`);
                unlockWindow();
                return;
            }

            let _formData = new FormData($(`#form-vote`)[0]);
            _formData.append('candidate_id', candidate_id);
            //_formData.append('recaptcha', grecaptcha.getResponse());

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('input[name=_token]').val()
                    }
                });
                $.ajax({
                    url : `/send-vote`,
                    type: 'POST',
                    data: _formData,
                    contentType: false,
                    processData: false,
                    success: function(e){
                        unlockWindow();
                        notice(`${e.title}`, `${e.message}`, `success`);
                        setTimeout(() => {
                            window.location.href = '/votacion';
                        }, 3000);

                    },
                    error:function(jqXHR, textStatus, errorThrown)
                    {
                        notice(`${jqXHR.responseJSON.title}`, `${jqXHR.responseJSON.message}`, `warning`);
                        unlockWindow();
                    }
                });

        });



</script>

@stop
