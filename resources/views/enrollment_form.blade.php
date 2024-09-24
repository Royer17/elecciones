@extends ('layouts.admin')
@section ('contenido')
<h3 class="font-bold">Ficha del Alumno
  <input type="hidden" id="year_default" value="{{ $year_default }}">
  <select id="enrollment-year">
    <option value="2024">2024</option>
    <option value="2023">2023</option>
  </select>
</h3>
{!! Form::open(array('id' => 'internal-solicitude_form', 'role' => 'form', 'files' => true, 'enctype' => 'multipart/form-data')) !!}

<div class="row">

  <input type="hidden" name="parent_office_id" value="{{ $current_office_id }}">
  <input type="hidden" name="order_id">
  <input type="hidden" name="entity_id">
  <div class="col-md-12">
    <div class="card">
      <div class="card-header text-white bg-dark"><h5>Datos del estudiante</h5></div>
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

        <div class="form-group mb-1 d-none">
          <label class="etiqueta">CÓDIGO FICHA:</label>
          <input type="text" name="code" class="form-control">
          <div class="text-danger error-message" id="document-code-error"></div>
        </div>

        <div class="form-group mb-1">
          <label class="etiqueta">BUSCAR ALUMNO:</label>
          <input type="text" name="identity_document" style="width:40%;" class="form-control">
          <!-- <div class="text-danger error-message" id="document-identity_document-error"></div> -->
        </div>

        <div class="row">
          <div class="col-md-6">
            <div class="form-group mb-1">
              <label class="etiqueta">NOMBRE: (*)</label>
              <!-- <input type="text" name="name" class="form-control"> -->
              <b><span id="name"></span></b>
              <div class="text-danger error-message" id="document-name-error"></div>
            </div>

            <div class="form-group mb-1">
              <label class="etiqueta">APELLIDO PATERNO:</label>
              <b><span id="maternal_surname"></span></b>
              <!-- <input type="text" name="paternal_surname" class="form-control"> -->
            </div>

            <div class="form-group mb-1">
              <label class="etiqueta">APELLIDO MATERNO:</label>
              <b><span id="paternal_surname"></span></b>
              <!-- <input type="text" name="maternal_surname" class="form-control"> -->
            </div>
          </div>

          <div class="col-md-6">
            <div class="form-group mb-1">
              <label class="etiqueta">Nivel: (*):</label>
              <b><span id="order_type_id"></span></b>
            </div>

            <div class="row">
              <div class="col-md-6">
                <div class="form-group mb-1">
                  <label class="etiqueta">Año:</label>
                  <b><span id="tupa_id"></span></b>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group mb-1">
                  <label class="etiqueta">Sección:</label>
                  <b><span id="subject"></span></b>
                </div>
              </div>

            </div>

          </div>

        </div>

      
      

      </div>
    </div>
  </div>

</div>

<div class="row pt-3">

  <div class="col-md-12">
    <div class="card">
      <div class="card-header text-white bg-dark"><h5>Cuota APAFA</h5></div>
      <div class="card-body bg-light">

        <button type="button" data-toggle="modal" data-target="#modal-concepts">Pagos</button>

        <div class="row">
          <div class="col-sm-12">
            <div class="form-group mb-1">
              <table class="table" id="concepts_to_pay">
                <thead>
                  <tr>
                    <th scope="col">#</th>
                    <th scope="col">Mes</th>
                    <th scope="col">Monto</th>
                  </tr>
                </thead>
                <tbody>
                 
                </tbody>
              </table>

            </div>
          </div>

        </div>
       
      </div>
    </div>

  </div>

</div>
  {!! Form::close() !!}


<div class="text-center py-3"><button type="button" class="btn btn-success font-bold" id="send-document">ENVIAR</button></div>


<div class="modal fade" id="modal-concepts">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-dark text-white">
        <h4 class="modal-title" id="modal-title">Conceptos de pago</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
      </div>
      <div class="modal-body">

        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-body bg-light">
                <div class="row">
                  <div class="col-sm-12">
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

                        </tbody>
                      </table>

                    </div>
                  </div>
                </div>
               
              </div>
            </div>

          </div>


        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-success save">Guardar</button>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
      </div>
    </div>
  </div>
</div>



@push ('scripts')
<script>

$('#enrollment-year').val($('#year_default').val());

function debounce(func, wait, immediate) {
  var timeout;
  return function() {
    var context = this, args = arguments;
    var later = function() {
      timeout = null;
      if (!immediate) func.apply(context, args);
    };
    var callNow = immediate && !timeout;
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
    if (callNow) func.apply(context, args);
  };
};
  
  $('#liGeneracionInterna').addClass("treeview active");
  $('#liRegisterFicha').addClass("active");

  $('select[name="offices"]').select2();
  $('select[name="offices_id"]').select2();

  let newArrOfConcepts = [];


  document.querySelector('#modal-concepts .save')
    .addEventListener('click', () => {
      let allTrFromTable = document.querySelectorAll('#modal-concepts tbody tr');

      newArrOfConcepts = [];

      allTrFromTable.forEach((tr, index) => {
        //console.log($(tr).find('.checkbox').is(':checked'));

        if ($(tr).find('.checkbox').is(':checked')) {

          newArrOfConcepts = [...newArrOfConcepts, {concept_id: $(tr).find('input[name="office_id"]').val(), name: $(tr).find('input[name="concept"]').val(), amount: $(tr).find('input[name="amount"]').val(), detail_id: $(tr).find('input[name="detail_id"]').val(), total_amount: $(tr).find('input[name="total_amount"]').val()}]
        }

      });
        
      document.querySelector('#concepts_to_pay tbody').innerHTML = ``;
      let newTbody = ``;

      newArrOfConcepts.forEach((concept, index) => {
        let debt = "";
        if(concept.amount != concept.total_amount){
          debt = `<span class='text-danger'>Total: S/.${parseFloat(concept.total_amount).toFixed(2)}</span>`;
        }

        newTbody += `
          <tr>
            <th scope="row">
                <input type="hidden" name="detail_ids[]" value="${ concept.detail_id }">
                <input type="hidden" name="office_ids[]" value="${ concept.concept_id }">
                <input type="hidden" name="payed[]" value="1">
                <input type="checkbox" class="checkbox" checked disabled>
            </th>
            <td><b>${ concept.name }</b></td>
            <td><input type="number" value="${ concept.amount }" disabled>
                <input type="hidden" name="amount[]" value="${ concept.amount }">
                ${debt}
            </td>
          </tr>`;
      });

      document.querySelector('#concepts_to_pay tbody').innerHTML = newTbody;

      $('#modal-concepts').modal('hide');

    })

    // $(document).on('keyup', '#internal-solicitude_form input[name="identity_document"]', function(){
    //     myEfficientFnQuantity($(this));
    // });
    $('input[name="identity_document"]').select2({
      // closeOnSelect:false,
      // ignore: ' ',
      ajax: {
        url: "/admin/search-all-students",
        dataType: 'json',
        quietMillis: 500,
        data: function(term) {
          return {
            nameProduct: term,
          };
        },
        results: function(data) {

          var myResults = [];
          $.each(data, function(index, item) {
            myResults.push({
              'id': item.id,
              'text': item.name
            });
          });
          return {
            results: myResults
          };
        }
      },
      //tags: true,
      // openOnEnter: false,
      minimumInputLength: 1,
      maximumSelectionSize: 5,
      formatSelectionTooBig: function(limit) {
        return 'Ingrese DNI o nombres';
      }
    });

    $('input[name="identity_document"]').on('change', function(){
      console.log("filling student info");
      myEfficientFnQuantity($('input[name="identity_document"]').val());
    })
    
    $('#enrollment-year').on('change', function(e){
      const year = e.target.value;
      window.location = location.protocol + '//' + location.host + location.pathname+`?year=${year}&eraseCache=true`;
    })

    var myEfficientFnQuantity = debounce(function(entityId) {
      lockWindow();
      axios.get(`/admin/search-student?dni=${entityId}&year=${document.querySelector('#enrollment-year').value}`)
        .then((response) => {

          document.querySelector('#internal-solicitude_form input[name="order_id"]').value = response.data.order.id;
          document.querySelector('#internal-solicitude_form input[name="entity_id"]').value = response.data.entity.id;

          document.querySelector('#name').innerHTML = response.data.entity.name;
          document.querySelector('#paternal_surname').innerHTML = response.data.entity.paternal_surname;
          document.querySelector('#maternal_surname').innerHTML = response.data.entity.maternal_surname;

          document.querySelector('#subject').innerHTML = response.data.order.subject;

          switch (response.data.order.order_type_id) {
            case 1:
              document.querySelector('#order_type_id').innerHTML = "PRIMARIA";
              break;
            case 2:
              document.querySelector('#order_type_id').innerHTML = "SECUNDARIA";
              break;
          }

          switch (response.data.order.tupa_id) {
            case 1:
              document.querySelector('#tupa_id').innerHTML = "1RO";
              break;
            case 2:
              document.querySelector('#tupa_id').innerHTML = "2DO";
              break;

            case 3:
              document.querySelector('#tupa_id').innerHTML = "3RO";
              break;
            case 4:
              document.querySelector('#tupa_id').innerHTML = "4TO";
              break;
            case 5:
              document.querySelector('#tupa_id').innerHTML = "5TO";
              break;
            case 6:
              document.querySelector('#tupa_id').innerHTML = "6TO";
              break;
          }
        
            document.querySelector('#modal-concepts tbody').innerHTML = ``;
            let newTbody = ``;


            response.data.order_details.forEach((concept, index) => {

              newTbody += `
                <tr>
                  <th scope="row">
                      <input type="hidden" name="detail_id" value="${concept.id}">
                      <input type="hidden" name="office_id" value="${concept.office_id}">
                      <input type="hidden" name="payed[]" value="0">
                      <input type="checkbox" class="checkbox">
                      <input type="hidden" name="concept" value="${concept.office_name}">
                  </th>
                  <td><b>${concept.office_name}</b></td>
                  <td><input type="number" name="amount" value="${concept.observations}">
                      <input type="hidden" name="total_amount" value="${concept.observations}">
                      <span>Total: S/.${parseFloat(concept.observations).toFixed(2)}</span>
                  </td>
                </tr>`;
            });

            // response.data.order.debt_details.forEach((concept, index) => {

            //   newTbody += `
            //     <tr>
            //       <th scope="row">
            //           <input type="hidden" name="detail_id" value="${concept.id}">
            //           <input type="hidden" name="office_id" value="${concept.office_id}">
            //           <input type="hidden" name="payed[]" value="0">
            //           <input type="checkbox" class="checkbox">
            //           <input type="hidden" name="concept" value="${concept.office.name}">
            //       </th>
            //       <td><b>${concept.office.name}</b></td>
            //       <td><input type="number" name="amount" value="${concept.observations}">
            //           <input type="hidden" name="total_amount" value="${concept.observations}">
            //           <span>Total: S/.${parseFloat(concept.observations).toFixed(2)}</span>
            //       </td>
            //     </tr>`;
            // });

            document.querySelector('#modal-concepts tbody').innerHTML = newTbody;
        })
        .catch((error) => {
          notice("Error", error.response.data.message, `warning`);
        })
        .finally(() => {
          unlockWindow();
        });


    }, 500);


document.querySelector(`#send-document`)
    .addEventListener('click', () => {
        
        lockWindow();
        $(`.error-message`).empty();
        let _formData = new FormData($(`#internal-solicitude_form`)[0]);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name=_token]').val()
                }
            });
            $.ajax({
                url : `/admin/payment`,
                type: 'POST',
                data: _formData,
                contentType: false,
                processData: false,
                success: function(e){
                    unlockWindow();
                    notice(`${e.title}`, `${e.message}`, `success`);
                    $(`#internal-solicitude_form`)[0].reset();
                    document.querySelector('#internal-solicitude_form input[name="order_id"]').value = "";
                    document.querySelector('#internal-solicitude_form input[name="entity_id"]').value = "";

                    document.querySelector('#concepts_to_pay tbody').innerHTML = ``;

                    document.querySelector('#name').innerHTML = "";
                    document.querySelector('#paternal_surname').innerHTML = "";
                    document.querySelector('#maternal_surname').innerHTML = "";

                    document.querySelector('#subject').innerHTML = "";
                    document.querySelector('#order_type_id').innerHTML = "";
                    document.querySelector('#tupa_id').innerHTML = "";

                    window.open(`/admin/reporte-de-pago/${e.id}`);
                    // setTimeout(function(){ 
                    //     location.replace(`/admin/mis-solicitudes-enviadas`);
                    //  }, 1000);
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

</script>
<!-- <script type="text/javascript" src="/js/logged_solicitudes.js"></script> -->
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
