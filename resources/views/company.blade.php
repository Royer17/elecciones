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


            <div class="form-group mb-1">
                <label for="nombre" class="etiqueta">Nombres y apellidos</label>
                <input type="text" name="video_url" class="form-control" value="{{$company->video_url}}" placeholder="Nombre completo">
            </div>


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
