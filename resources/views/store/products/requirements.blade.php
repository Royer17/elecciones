@extends('store.layouts.index')
@section('content')
<div class="container py-4">
  <div class="card">
    <div class="card-body">
      <div class="px-md-5 py-4">
        
        <h2 class="text-center font-bold mb-4">Requisitos y Formatos</h2>
        <div class="alert alert-primary text-center">
          <i class="fa fa-exclamation-circle mr-2"></i>A continuación se listan todos los procesos disponibles en la mesa de partes virtual y se detallan sus requisitos y formatos de formularios para la entrega de documentos.
        </div>
        <div class="row align-items-center pt-4">
          <div class="col-auto pr-0 font-bold">Buscar Procedimiento:</div>
          <div class="col">
            <select class="form-control select_2" id="tupa_list">
              <option value="">Seleccione</option>
              @foreach($all_tupa as $t)
                @if($t->id == $identifier)
                  <option value="{{ $t->id }}" selected>{{ $t->title }}</option>
                @else
                  <option value="{{ $t->id }}">{{ $t->title }}</option>
                @endif
              @endforeach
            </select>
          </div>
        </div>

        @foreach($tupa as $key => $t)
        <div class="table-responsive contact_info pt-4">
          <h4 class="font-bold mb-2">{{ $t->id }}. {{ $t->title }}</h4>
          <table class="table-bordered table-striped mb-2">
            @foreach($t->requirements as $requirement)
            <tr class="fila_archivo">
              <td>{{ $requirement->name }}</td>
              <td>
                @if($requirement->link)
                <a href="{{ $requirement->link }}" target="_blank" class="btn btn-info btn-sm">Descargar</a>
                @endif
              </td>
            </tr>
            @endforeach
            {{-- 
            <tr><td colspan="2">Procedimientos administrativos 1-5 de acuerdo a TUPA vigente</td></tr>
            <tr><td colspan="2">Anexo I: Solicitud declaración jurada para licencia de funcionamiento</td></tr>
            <tr class="fila_archivo">
              <td>Requisitos para la solicitud</td>
              <td><a href="https://repositorio.ingemmet.gob.pe/bitstream/20.500.12544/2046/1/Memoria_explicativa_Pachia_36-v_Palca_36-x.pdf" target="_blank" class="btn btn-info btn-sm">Descargar</a></td>
            </tr>
            --}}
          </table>
          <div class="contact_area py-2 px-4">
            <b>Contacto</b>
            <ul class="pl-4">
              @if($t->email)
              <li>Email: {{ $t->email }}</li>
              @endif

              @if($t->cellphone)
              <li>Celular: {{ $t->cellphone }}</li>
              @endif

              @if($t->phone)
              <li>Teléfono: {{ $t->phone }}</li>
              @endif
            </ul>
          </div>
        </div>
        @endforeach

        <div>
          {{$tupa->render()}}
        </div>
        {{--
        <div class="table-responsive contact_info pt-4">
          <h4 class="font-bold mb-2">2. Anuncio publicitario simple</h4>
          <table class="table-bordered table-striped mb-2">
            <tr><td colspan="2">Prodecimiento administrativo 18, de acuerdo a TUPA vigente</td></tr>
            <tr><td colspan="2">Formulario N° 7: Solicitud de anuncio publicitario simple</td></tr>
            <tr class="fila_archivo">
              <td>Requisitos para la solicitud</td>
              <td><a href="https://repositorio.ingemmet.gob.pe/bitstream/20.500.12544/2046/1/Memoria_explicativa_Pachia_36-v_Palca_36-x.pdf" target="_blank" class="btn btn-info btn-sm">Descargar</a></td>
            </tr>
            <tr><td colspan="2">Modelo de anuncio publicitario</td></tr>
            <tr><td colspan="2">Modelo de llenado de anuncio publicitario</td></tr>
          </table>
          <div class="contact_area py-2 px-4">
            <b>Contacto</b>
            <ul class="pl-4">
              <li>Email: administrador@sistema.pe</li>
              <li>Celular: 922 727 092</li>
              <li>Teléfono: 200 2536 Anexo 1451 (Solo para consultar el estado del expediente)</li>
            </ul>
          </div>
        </div>
        --}}

      </div>
    </div>
  </div>
</div>
@stop

@section('plugins-js')

<script src='https://www.google.com/recaptcha/api.js'></script>
<script type="text/javascript">

    $(`#tupa_list`)
        .on('change', function(e){
            //lockWindow();
            location.replace(`/requisitos-formatos?identificador=${e.target.value}`);

        });

</script>

@stop
