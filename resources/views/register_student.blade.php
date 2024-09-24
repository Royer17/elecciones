@extends ('layouts.admin')
@section ('contenido')
<h3 class="font-bold">Crear Alumno
        <select id="enrollment-year" onchange="changeYearEnrollment(this);">
          <option value="2024">2024</option>
          <option value="2023">2023</option>
        </select>
</h3>
{!! Form::open(array('id' => 'internal-solicitude_form', 'role' => 'form', 'files' => true, 'enctype' => 'multipart/form-data')) !!}

<div class="row">
  <input type="hidden" name="parent_office_id" value="{{ $current_office_id }}">
  <input type="hidden" name="year" value="{{ $year }}">
  <div class="col-md-6">
    <div class="card">
      <div class="card-header text-white bg-dark"><h5>Datos del estudiante</h5>
      </div>
      <div class="card-body bg-light">
        <div class="form-group mb-2 d-none">
          <label class="etiqueta"><b>CUD: {{ $year }}{{ $code }}</b></label>
          <input type="text" class="form-control d-none" value="{{ $year }}{{ $code }}" disabled>
        </div>
        <div class="row d-none">
          <div class="col-sm-6">
            <div class="form-group mb-1">
              <label class="etiqueta">Fecha:</label>
              <input type="text" name="date" class="form-control date-datepicker" autocomplete="off" placeholder="dd/mm/yyyy" value="{{ $today_date }}" disabled>
            </div>
          </div>
          <div class="col-sm-6">
            <div class="form-group mb-1">
              <label class="etiqueta">Hora:</label>
              <input type="time" name="time" class="form-control" value="{{ $today_hour }}" disabled>
            </div>
          </div>
        </div>

        <div class="form-group mb-1">
          <label class="etiqueta">DNI: (*)
            <button type="button" onclick="searchStudent(this);">Buscar</button></label>
          <input type="text" name="identity_document" class="form-control">
          <div class="text-danger error-message" id="document-identity_document-error"></div>
          <span class="text-warning student-not-found">Alumno no encontrado.</span>
        </div>

        <div class="form-group mb-1">
          <label class="etiqueta">NOMBRE: (*)</label>
          <input type="text" name="name" class="form-control">
          <div class="text-danger error-message" id="document-name-error"></div>
        </div>

        <div class="form-group mb-1">
          <label class="etiqueta">APELLIDO PATERNO: (*)</label>
          <input type="text" name="paternal_surname" class="form-control">
          <div class="text-danger error-message" id="document-paternal_surname-error"></div>
        </div>

        <div class="form-group mb-1">
          <label class="etiqueta">APELLIDO MATERNO: (*)</label>
          <input type="text" name="maternal_surname" class="form-control">
          <div class="text-danger error-message" id="document-maternal_surname-error"></div>
        </div>

        <div class="form-group mb-1">
          <label class="etiqueta">Nivel: (*):</label>
          <select name="order_type_id" class="form-control">
            <option value="">Seleccione</option>
            <option value="1">PRIMARIA</option>
            <option value="2">SECUNDARIA</option>
          </select>
          <div class="error-message text-danger" id="document-order_type_id-error"></div>
        </div>

        <div class="form-group mb-1 d-none">
          <label class="etiqueta">Numeración:</label>
          <div class="text-uppercase" id="document_type_code"></div>
          <input type="hidden" name="internal_code">
        </div>

        <div class="form-group mb-1">
          <label class="etiqueta">Año:</label>
          <select class="form-control select_2" name="tupa_id">
            <option value="">Seleccione</option>
<!--             <option value="1">PRIMERO</option>
            <option value="2">SEGUNDO</option>
            <option value="3">TECERO</option>
            <option value="4">CUARTO</option>
            <option value="5">QUINTO</option> -->
          </select>
          <div class="text-danger error-message" id="document-tupa_id-error"></div>
        </div>

        <div class="form-group mb-1">
          <label class="etiqueta">Sección:</label>
          <select class="form-control select_2" name="subject">
            <option value="">Seleccione</option>
<!--             <option value="A">A</option>
            <option value="B">B</option> -->
          </select>
          <div class="text-danger error-message" id="document-subject-error"></div>
        </div>

      </div>

      <div class="card-header"><h6>Datos del apoderado</h6></div>
      <div class="card-body bg-light">
        <div class="form-group mb-2">
          <label class="etiqueta">DNI: (*)</label>
          <input type="text" name="identity_document_parent" class="form-control">
          <div class="text-danger error-message" id="document-identity_document_parent-error"></div>
        </div>

        <div class="form-group mb-2">
          <label class="etiqueta">NOMBRE: (*)</label>
          <input type="text" name="name_parent" class="form-control">
          <div class="text-danger error-message" id="document-name_parent-error"></div>
        </div>

        <div class="form-group mb-2">
          <label class="etiqueta">APELLIDO PATERNO: (*)</label>
          <input type="text" name="paternal_surname_parent" class="form-control">
          <div class="text-danger error-message" id="document-paternal_surname_parent-error"></div>
        </div>

        <div class="form-group mb-2">
          <label class="etiqueta">APELLIDO MATERNO: (*)</label>
          <input type="text" name="maternal_surname_parent" class="form-control">
          <div class="text-danger error-message" id="document-maternal_surname_parent-error"></div>
        </div>
      </div>

    </div>
  </div>
  <div class="col-md-6">
    <div class="card">
      <div class="card-header text-white bg-dark"><h5>Conceptos de pago {{ $year }}</h5></div>
      <div class="card-body bg-light">
  
        <div class="form-group mb-1">
          <table class="table">
            <thead>
              <tr>
                <th scope="col">#</th>
                <th scope="col">Mes</th>
                <th scope="col">Monto</th>
              </tr>
            </thead>
            <tbody>
              @foreach($payment_concepts as $fm => $concept)
              <tr>
                <th scope="row">
                    <input type="hidden" name="office_ids[]" value="{{ $concept->id }}">
                    <input type="hidden" name="payed[]" value="1">
                    <input type="checkbox" onclick="changeCheckboxValue(this);" checked>
                </th>
                <td><b>{{ $concept->name }}</b></td>
                <td><input type="number" name="amount[]" value="{{ $concept->sigla }}"></td>
              </tr>
              @endforeach
            </tbody>
          </table>

        </div>

      
      </div>
    </div>

  </div>
</div>
<label class="text-danger">(*) Son campos obligatorios</label>
{!! Form::close() !!}


<div class="text-center py-3"><button type="button" class="btn btn-success font-bold" id="send-document">ENVIAR</button></div>


@push ('scripts')
<script>
  $('#liGeneracionInterna').addClass("treeview active");
  $('#liRegisterStudent').addClass("active");

  $('select[name="offices"]').select2();
  $('select[name="offices_id"]').select2();


    $(`input[name="identity_document_parent"]`).on('keyup', function(e){
        e.preventDefault();

        if (e.target.value.length != 8) {
          return;
        }

        const identity_document_parent = e.target.value;

        document.querySelector(`input[name="name_parent"]`).value = "";
        document.querySelector(`input[name="paternal_surname_parent"]`).value = "";
        document.querySelector(`input[name="maternal_surname_parent"]`).value = "";
        // document.querySelector(`input[name="cellphone"]`).value = "";
        // document.querySelector(`input[name="email"]`).value = "";
        // document.querySelector(`#razon_social_field`).value = "";

            // if (!document.querySelector(`#dni_field`).value || document.querySelector(`#dni_field`).value.length != 8) {
            //     //alert(`Especifique un DNI válido.`);
            //     Swal.fire(
            //       '',
            //       'Especifíque un DNI válido.',
            //       'warning'
            //     )

            //     return;
            // }

            lockWindow();

            axios.get(`/admin/parent/${e.target.value}/search`)
            .then((response) => {
                if (response.data.success) {
                    document.querySelector(`input[name="name_parent"]`).value = response.data.parent.name;
                    document.querySelector(`input[name="paternal_surname_parent"]`).value = response.data.parent.sigla;
                    document.querySelector(`input[name="maternal_surname_parent"]`).value = response.data.parent.maternal_surname;
                    // document.querySelector(`input[name="cellphone"]`).value = response.data.entity.cellphone;
                    // document.querySelector(`input[name="email"]`).value = response.data.entity.email;
                    // document.querySelector(`input[name="address"]`).value = response.data.entity.address;
                    unlockWindow();
                    return;
                }

                axios.get(`https://dniruc.apisperu.com/api/v1/dni/${identity_document_parent}?token=eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJlbWFpbCI6InJveWVyLmpha0BnbWFpbC5jb20ifQ.OblQV2df3iMDJbRHx3o_342AKsP1Xp3vNoql3WK6jV4`, {timeout: 5000})
                .then((response) => {
                    document.querySelector(`input[name="name_parent"]`).value = response.data.nombres;
                    document.querySelector(`input[name="paternal_surname_parent"]`).value = response.data.apellidoPaterno;
                    document.querySelector(`input[name="maternal_surname_parent"]`).value = response.data.apellidoMaterno;
                    unlockWindow();
                })
                .catch((err) => {
                    unlockWindow();
                    // Swal.fire(
                    //   '',
                    //   `No se ha encontrado el DNI.`,
                    //   'warning'
                    // )
                    return;
                });
                //use the api
            })
            .catch((err) => {
                // unlockWindow();
                // Swal.fire(
                //   '',
                //   `Ha ocurrido un error.`,
                //   'warning'
                // )
                return;
            });


    });

</script>
<script type="text/javascript" src="/js/logged_solicitudes.js"></script>
@endpush
@endsection
@section('custom-css')
<style type="text/css">
  #document_type_code {
    font-size: 20px;
    font-weight: 700;
    margin-bottom: 8px;
    line-height: 1;
  }
</style>
@endsection
