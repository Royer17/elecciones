@extends ('layouts.admin')
@section ('contenido')
    <div class="row">
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
            <h3 class="font-bold">Editar Datos de la Institución</h3>
            @if (count($errors)>0)
            <div class="alert alert-danger">
                <ul>
                @foreach ($errors->all() as $error)
                    <li>{{$error}}</li>
                @endforeach
                </ul>
            </div>
            @endif

            {!!Form::model($company,['method'=>'POST','route'=>['company.update'], 'files'=>'true'])!!}
            {{Form::token()}}

            <div class="form-group mb-1">
                <label for="nombre" class="etiqueta">Nombre de la Institución</label>
                <input type="text" name="name" class="form-control" value="{{$company->name}}" placeholder="Nombre">
            </div>

            <div class="form-group mb-1">
                <label for="nombre" class="etiqueta">Logo</label>
                <input type="file" name="logo" class="form-control" value="" placeholder="LOGOTYPE">
                <br>
                @if (($company->logo)!="")
                    <img src="{{asset($company->logo)}}" height="300px">
                @endif
            </div>

            <h4 class="font-bold mt-4">Editar Datos del Presidente de APAFA</h4>

            <div class="form-group mb-1 d-none">
                <label for="nombre" class="etiqueta">Descripción</label>
                <textarea name="description" class="form-control" placeholder="Acerca de nosotros">{{$company->description}}</textarea>
            </div>

            <div class="form-group mb-1">
                <label for="nombre" class="etiqueta">Nombres y apellidos</label>
                <input type="text" name="video_url" class="form-control" value="{{$company->video_url}}" placeholder="Nombre completo">
            </div>

            {{-- <div class="form-group mb-1">
                <label for="nombre" class="etiqueta">Logo</label>
                <input type="text" name="logo" class="form-control" value="{{$company->logo}}" placeholder="Logo">
            </div> --}}

            <div class="form-group mb-1">
                <label for="nombre" class="etiqueta">DNI</label>
                <input type="text" name="address" class="form-control" value="{{$company->address}}" placeholder="Documento de identidad">
            </div>

            <div class="form-group mb-1">
                <label for="nombre" class="etiqueta">Correo electrónico</label>
                <input type="text" name="email" class="form-control" value="{{$company->email}}" placeholder="Email">
            </div>

            <div class="form-group mb-1">
                <label for="nombre" class="etiqueta">Celular</label>
                <input type="text" name="phone_1" class="form-control" value="{{$company->phone_1}}" placeholder="Celular">
            </div>
            <hr>

            <span class="font-bold h6">Soporte y mantenimiento</span>
<table>
  <thead>

  </thead>
  <tbody>
    <tr>
      <td>
        <code>Nombre del técnico de soporte</code>
      </td>
      <td><span class="h4">Luis Pérez</span></td>
    </tr>
    <tr>
      <td>
        <code>Dirección del centro de soporte</code>
      </td>
      <td><span class="h4">Calana</span></td>
    </tr>
    <tr>
      <td>
        <code>Celular</code>
      </td>
      <td><span class="h4">9459459459</span></td>
    </tr>
  </tbody>
</table>

            <!-- <div class="form-group mb-1">
                <label for="nombre" class="etiqueta">Código de formato de trámite</label>

                <div class="row align-items-center">
                    <div class="col-md-auto pr-md-1">
                        <span>informe1-TICS-</span>
                    </div>
                    <div class="col-md-3 px-md-0">
                        <input type="text" name="first_part_code" class="form-control" value="{{ $company->first_part_code }}" placeholder="">
                    </div>
                    <div class="col-md-auto px-md-1">
                        <span>/</span>
                    </div>
                    <div class="col-md-2 px-md-0">
                        <input type="text" name="second_part_code" class="form-control" value="{{ $company->second_part_code }}" placeholder="">
                    </div>
                    <div class="col-md-auto pl-md-1">
                        <span>-2022</span>
                    </div>

                </div>

            </div> -->

            <div class="form-group mb-1 d-none">
                <label for="nombre" class="etiqueta">Teléfono 2</label>
                <input type="text" name="phone_2" class="form-control" value="{{$company->phone_2}}" placeholder="Teléfono 2">
            </div>

            <div class="form-group mb-1 d-none">
                <label for="nombre" class="etiqueta">Celular 1</label>
                <input type="text" name="cellphone_1" class="form-control" value="{{$company->cellphone_1}}" placeholder="Celular 1">
            </div>

            <div class="form-group mb-1 d-none">
                <label for="nombre" class="etiqueta">Celular 2</label>
                <input type="text" name="cellphone_2" class="form-control" value="{{$company->cellphone_2}}" placeholder="Celular 2">
            </div>

            <div class="form-group mb-1 d-none">
                <label for="nombre" class="etiqueta">Whatsapp</label>
                <input type="text" name="whatsapp" class="form-control" value="{{$company->whatsapp}}" placeholder="Whatsapp">
            </div>
            {{--
            <div class="form-group mb-1">
                <label for="nombre" class="etiqueta">YAPE QR</label>
                <input type="file" name="yape_qr" class="form-control" value="" placeholder="YAPE QR">
                <br>
                @if (($company->yape_qr)!="")
                    <img src="{{asset($company->yape_qr)}}" height="300px" width="300px">
                @endif
            </div>
            --}}
            <div class="form-group mb-1 d-none">
                <label for="nombre" class="etiqueta">Imágen(870x430)</label>
                <input type="file" name="image" class="form-control" value="" placeholder="Imágen">
                <br>
                @if (($company->image)!="")
                    <img src="{{asset($company->image)}}" width="100%">
                @endif
            </div>

            <div class="form-group mt-4">
                <button class="btn btn-primary" type="submit" title="Guardar">Guardar</button>
                <button class="btn btn-danger" type="reset" title="Remover todo lo escrito">Cancelar</button>
            </div>

            {!!Form::close()!!}

        </div>
    </div>
@push ('scripts')
<script>
    $('#liAcceso').addClass("treeview active");
    $('#liEmpresa').addClass("active");
</script>
@endpush
@endsection
