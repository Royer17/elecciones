@extends('store.layouts.index')
@section('content')
<div class="container py-4">
  <div class="card">
    <div class="card-body">
      <div class="px-md-5 py-4">
        
        <section class="d-flex justify-content-between mb-2">
          <figure>
            <img src="/img/logo.png" width="50">
          </figure>
          <article>
            <h2 class="text-center text-uppercase font-bold">Alcaldes y regidores</h2>
            <h4 class="text-center text-uppercase">Cabrera Gonzales, Jhonatan Nataniel</h4>
            <h5 class="text-center text-uppercase">Cédula Nro: 994594595942, Grado: 1ro D</h5>    
          </article>
          
          <figure>
            <img src="/img/logo.png" width="50">
          </figure>
        </section>

        <section>
          <p class="text-center text-uppercase text-white bg-dark h2 p-2 font-bold mb-0">Primera elección municipal escolar</p>
          <p class="text-center text-uppercase h4 text-dark p-1" style="background:#c1bdbd;">Presione el símbolo, número u opción de su preferencia</p>
        </section>

        <section>
          
          @foreach($candidates as $candidate)
          <article class="d-flex justify-content-between border align-items-center px-4 py-2 card-candidate mb-2" style="background: #d6e6f5;">
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
          <button class="btn btn-info btn-lg pull-right">Enviar</button>
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

    $(`#tupa_list`)
        .on('change', function(e){
            //lockWindow();
            location.replace(`/requisitos-formatos?identificador=${e.target.value}`);

        });


      $('.card-candidate').on('click', function(e){
        document.querySelectorAll('.card-candidate').forEach(card => {
            card.style.background = '#d6e6f5';
            card.querySelector('p').style.color = '#6f6f6f';
        });
        $(e.target).css('background', '#c83e3a');
        $(e.target).find('p').css('color', 'white');
      });



</script>

@stop
