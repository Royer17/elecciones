@extends ('layouts.admin')
@section ('contenido')
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title font-bold">Editar Datos de la Institución</h3>
                </div>
                
                <div class="box-body">
                    @if (count($errors)>0)
                    <div class="alert alert-danger alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                        <h4><i class="icon fa fa-ban"></i> Errores de validación</h4>
                        <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{$error}}</li>
                        @endforeach
                        </ul>
                    </div>
                    @endif

                    {!!Form::model($company,['method'=>'POST','route'=>['company.update'], 'files'=>'true'])!!}
                    {{Form::token()}}

                    <div class="form-group">
                        <label for="name" class="control-label">Nombre de la Institución</label>
                        <input type="text" name="name" class="form-control" value="{{$company->name}}" placeholder="Ingrese el nombre de la institución">
                    </div>

                    <div class="form-group">
                        <label for="logo" class="control-label">Logo de la Institución</label>
                        <input type="file" name="logo" class="form-control">
                        @if (($company->logo)!="")
                            <div class="mt-2">
                                <p class="text-muted">Logo actual:</p>
                                <img src="{{asset($company->logo)}}" class="img-responsive img-thumbnail" style="max-height: 200px;">
                            </div>
                        @endif
                    </div>

                    <div class="panel panel-default mt-4">
                        <div class="panel-heading">
                            <h4 class="panel-title font-bold">Datos del Presidente de APAFA</h4>
                        </div>
                        <div class="panel-body">
                            <div class="form-group">
                                <label for="video_url" class="control-label">Nombres y apellidos</label>
                                <input type="text" name="video_url" class="form-control" value="{{$company->video_url}}" placeholder="Nombre completo del presidente">
                            </div>

                            <div class="form-group">
                                <label for="address" class="control-label">DNI</label>
                                <input type="text" name="address" class="form-control" value="{{$company->address}}" placeholder="Número de DNI">
                            </div>

                            <div class="form-group">
                                <label for="email" class="control-label">Correo electrónico</label>
                                <input type="email" name="email" class="form-control" value="{{$company->email}}" placeholder="correo@ejemplo.com">
                            </div>

                            <div class="form-group">
                                <label for="phone_1" class="control-label">Celular</label>
                                <input type="text" name="phone_1" class="form-control" value="{{$company->phone_1}}" placeholder="Número de celular">
                            </div>
                        </div>
                    </div>

                    <div class="panel panel-info mt-4">
                        <div class="panel-heading">
                            <h4 class="panel-title">Soporte y mantenimiento</h4>
                        </div>
                        <div class="panel-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <tbody>
                                        <tr>
                                            <td class="col-md-4"><strong>Nombre del técnico de soporte</strong></td>
                                            <td class="col-md-8">Luis Pérez</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Dirección del centro de soporte</strong></td>
                                            <td>Calana</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Celular</strong></td>
                                            <td>9459459459</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-4">
                        <button class="btn btn-primary" type="submit" title="Guardar">
                            <i class="fa fa-save"></i> Guardar
                        </button>
                        <button class="btn btn-danger" type="reset" title="Limpiar formulario">
                            <i class="fa fa-times"></i> Cancelar
                        </button>
                    </div>

                    {!!Form::close()!!}
                </div>
            </div>
        </div>
    </div>
@push ('scripts')
<script>
    $('#liAcceso').addClass("treeview active");
    $('#liEmpresa').addClass("active");
    
    // Validación de campos
    $(document).ready(function() {
        $('form').on('submit', function(e) {
            var name = $('input[name="name"]').val();
            if (!name.trim()) {
                e.preventDefault();
                alert('El nombre de la institución es obligatorio');
            }
        });
    });
</script>
@endpush
@endsection