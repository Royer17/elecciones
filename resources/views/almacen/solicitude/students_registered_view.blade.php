@extends ('layouts.admin')
@section ('contenido')
<div class="row">

	@if (session()->has('data'))
	<div class="col-md-12 text-danger">
		{{ session()->get('data')[0] }}
	</div>
	@endif

	<div class="col-md-12">
		<h3 class="font-bold">Estudiantes registrados <a href="/admin/registrar-estudiante" class="btn btn-primary btn-sm">Nuevo</a></h3>
		<div id="filters_accordion">
			<div class="card">
		    <div class="card-header py-1">
		      <a class="collapsed card-link" data-toggle="collapse" href="#filter_1"><div class="title_filters"><label>Filtros</label><i class="fa fa-filter"></i></div></a>
		    </div>
		    <div id="filter_1" class="collapse" data-parent="#filters_accordion">
		      <div class="card-body py-1">
						<div class="row align-items-end">
							<div class="col-md-4 mb-3">
								{!! Form::open(array('url'=>'admin/estudiantes-registrados','method'=>'GET','autocomplete'=>'off','role'=>'search')) !!}
								<div class="form-group mb-0">
							    <label class="etiqueta">Buscar Matrícula:</label>
									<div class="input-group">
									  <input type="text" class="form-control" name="searchText" placeholder="Buscar por DNI, Nombres..." value="{{$searchText}}">
									  <div class="input-group-append">
									    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
									  </div>
									</div>
							  </div>
								{{Form::close()}}
							</div>
							{{-- 
							<div class="col-sm-auto mb-3">
								<label class="etiqueta">Estado:</label><br>
								<div class="input-group">
									<select class="form-control" name="status">
									<option value="">Todos</option>

									@if($status_searched == "")
										<option value="activos">Activo</option>
										<option value="anulados">Anulado</option>
										<option value="retirados">Retirado</option>
									@endif

									@if($status_searched == "activos")
										<option value="activos" selected>Activo</option>
										<option value="anulados">Anulado</option>
										<option value="retirados">Retirado</option>
									@endif

									@if($status_searched == "anulados")
										<option value="activos">Activo</option>
										<option value="anulados" selected>Anulado</option>
										<option value="retirados">Retirado</option>
									@endif

									@if($status_searched == "retirados")
										<option value="activos">Activo</option>
										<option value="anulados">Anulado</option>
										<option value="retirados" selected>Retirado</option>
									@endif
									</select>

								</div>
							</div>

							<div class="col-md-3 mb-3">
								<div class="form-group daterangepicker-area mb-0">
							    <label class="etiqueta">Fecha de creación:</label>
									<input type="hidden" name="start_date" value="{{ $start_date }}">
									<input type="hidden" name="end_date" value="{{ $end_date }}">
									<input type="text" class="form-control" name="dates" value="" autocomplete="off">
							  </div>
							</div>
							--}}
							{{-- 
							<div class="col-sm-auto mb-3">
								<a href="/admin/estudiantes-registrados" class="btn btn-success">Reset</a>
							</div>
							
							<div class="col-sm-auto mb-3">
								<label class="etiqueta">Exportar en:</label><br>
								<!-- <button class="btn btn-primary" id="solicitudes_report">Excel</button> -->
								<a href="/admin/estudiantes-registrados-excel?inicio={{ $start_date }}&fin={{ $end_date }}&searchText={{ $searchText }}" class="btn btn-primary" target="_blank">Excel</a>
							</div>
							--}}

							<div class="col-sm-auto mb-3">
								<label class="etiqueta">Importar:</label><br>
								<div class="input-group">
								  <input type="file" class="form-control" id="file">
								  <div class="input-group-append">
								    <button type="button" id="student__import" class="btn btn-primary"><i class="fa fa-arrow-up"></i></button>
								  </div>
								</div>

							</div>

						</div>
					</div>
		    </div>
		  </div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive table_solicitud mt-4">
			<table class="table table-striped table-bordered table-condensed table-hover table-sm">
				<thead class="thead-dark">
					<th>#</th>
					<th>Código</th>
					<th>Fecha de creación</th>
					<th>DNI</th>
					<th>Nombre</th>
					<th>Apellido</th>
					<!-- <th>Más información</th> -->
					<th>Nivel</th>
					<!-- <th>Estado</th> -->
					<th>Opciones</th>
				</thead>
               @foreach ($orders as $key => $cat)
				<tr>
					<td>{{ $key+1}}</td>
					<td>{{ $cat->code }}</td>
					<td>{{ \Date::parse($cat->created_at)->format('d/F/Y H:i') }}</td>
					<td>{{ $cat->identity_document }}</td>
					<td>{{ $cat->name}}</td>
					<td>{{ $cat->paternal_surname }}</td>
					<!-- <td> <a href="" data-toggle="modal" data-target="#modal-entity-{{$cat->id}}"><i class="fa fa-eye mr-2"></i>Ver</a>
					</td> -->
					@if($cat->order_type_id == 1)
						<td>Primaria</td>
					@elseif($cat->order_type_id == 2)
						<td>Secundaria</td>
					@else
						<td class="text-danger">Error</td>
					@endif
					<td>
						{{-- 
						@if($admin)
						<a href="/admin/editar-registro-de-estudiante/{{ $cat->id }}" class="btn py-0 px-1" target="_blank" title="Editar" style="background-color: #87CEEB; border-color: #87CEEB;"><i class="fas fa-pencil-alt"></i></a>

						<a href="/admin/reporte-de-pagos/{{ $cat->id }}" class="btn py-0 px-1" target="_blank" title="Reporte de pago" style="background-color: #87CEEB; border-color: #87CEEB;"><i class="fas fa-print"></i></a>
						@endif
						--}}
                        <div class="dropdown export_matriculas">
                          <button type="button" class="btn btn_state dropdown-toggle" data-toggle="dropdown">Acción</button>
                          <div class="dropdown-menu">
							{{-- 
                            <a class="dropdown-item" data-i="{{ $cat->id }}" data-index="2" data-action_text="Anular" href="javascript:void(0);" onclick="changeStatus(this)">Anular</a>
							--}}
                            <a class="dropdown-item" data-i="{{ $cat->id }}" data-index="3" data-action_text="Retirar" href="javascript:void(0);" onclick="changeStatus(this)">Retirar</a>
                          </div>
                        </div>

					</td>
				</tr>
				@include('almacen.solicitude.entity')
				@endforeach
			</table>
		</div>
		{{$orders->appends(request()->input())->render()}}
	</div>
</div>
@include('almacen.solicitude.modal')

@push ('scripts')
<script>
  	$('#liGeneracionInterna').addClass("treeview active");
	$('#liEnrollment').addClass("active");

$(`#update-status-form`)[0].reset();

const modalTitle = document.querySelector('#update-status-form .modal-title');

$(document).on('click', '.action', function(e){
	e.preventDefault();
	let _that = $(this).parent(), _order_id = _that[0].dataset.index, _new_status = _that[0].dataset.new_status;

	$(`.send-office`).hide();
	$(`.attach-file`).hide();

	if (_new_status == 2) {
		$(`.send-office`).show();
	}

	if (_new_status == 4) {
		$(`.attach-file`).show();
	}

	axios.get(`/document-state/${_new_status}`)
		.then((response) => {
			$(`#order_id`).val(_order_id);
			$(`#status_id`).val(_new_status);
			modalTitle.innerHTML = `Se cambiará el estado a: ${response.data.name}`;
		});
});

document.querySelector(`#solicitude__update`)
	.addEventListener('click', () => {
		Swal.fire({
		  title: '¿Está seguro?',
		  text: "Va a cambiar el estado del documento",
		  icon: 'warning',
		  showCancelButton: true,
		  confirmButtonColor: '#3085d6',
		  cancelButtonColor: '#d33',
		  confirmButtonText: 'Sí!',
		  cancelButtonText: 'No!'
		}).then((result) => {
			console.log(result);
		  if (result.value) {
		  	//$(`#update-status-form`).submit();

		  	const route = `/admin/solicitude-status`;

		  	const formData = new FormData(document.querySelector('#update-status-form'));

		  	formData.append('offices_arr', $('#modal-delete- select[name="offices"]').val());

		  	lockWindow();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name=_token]').val()
                }
            });
            $.ajax({
                url : route,
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function(e){
                    unlockWindow();
                    notice(`${e.title}`, `${e.message}`, `success`);
                    //document.querySelector('#update-status-form').reset();
                    location.reload();
                    //$(`#order_id`).val(e.id);
                    //$(`#request-completed-form`).submit();
                    // setTimeout(function(){
                    //     location.replace(`/admin/solicitudes`);
                    //  }, 1000);

                },
                error:function(jqXHR, textStatus, errorThrown)
                {
                    notice(jqXHR.responseJSON.title, jqXHR.responseJSON.message, `warning`);
                    unlockWindow();
                }
            });

		    // Swal.fire(
		    //   'Deleted!',
		    //   'Your file has been deleted.',
		    //   'success'
		    // )
		  }
		})
	});


$(`.solicitude__delete`).on('click', function(E){
	E.preventDefault();
	let _that = $(this), _order_id = _that[0].dataset.index;

	Swal.fire({
	  title: '¿Está seguro?',
	  text: "Va a eliminar la solicitud",
	  icon: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: 'Sí!',
	  cancelButtonText: 'No!'
	}).then((result) => {
	  if (result.value) {
	  	lockWindow();
	  	axios.delete(`/admin/solicitude/${_order_id}`)
	  		.then((response) => {
	  			unlockWindow();

	  			setTimeout(() => {
					location.reload();
	  			}, 500);
	  		})
	  		.catch((err) => {

	  		})


	  }
	});

});

let startDate = document.querySelector('.daterangepicker-area input[name="start_date"]').value;
let endDate = document.querySelector('.daterangepicker-area input[name="end_date"]').value;

if (startDate) {
   $('input[name="dates"]').val(startDate + ' - ' + endDate);
}

$('input[name="dates"]').daterangepicker({
    locale: {
		format: 'DD/MM/YYYY',
		"applyLabel": "Aplicar",
		"cancelLabel": "Cancelar",
		"fromLabel": "De",
		"toLabel": "Hasta",
		"customRangeLabel": "Personalizado",
		"daysOfWeek": [
		    "Do",
		    "Lu",
		    "Ma",
		    "Mi",
		    "Ju",
		    "Vi",
		    "Sa"
		],
		"monthNames": [
		    "Enero",
		    "Febrero",
		    "Marzo",
		    "Abril",
		    "Mayo",
		    "Junio",
		    "Julio",
		    "Agusto",
		    "Septiembre",
		    "Octubre",
		    "Noviembre",
		    "Diciembre"
		],

    },
    autoUpdateInput: false,
    // startDate: document.querySelector('.daterangepicker-area input[name="start_date"]').value,
    // endDate: document.querySelector('.daterangepicker-area input[name="end_date"]').value,
 //    maxSpan: {
 //    	"months": 6
	// },
dateLimit: {
    'months': 6,
    'days': -1
}

}, function(start, end) {
	//startDate = start.format('YYYY-MM-DD');
	startDate = start.format('DD/MM/YYYY');
	//endDate = end.format('YYYY-MM-DD');
	endDate = end.format('DD/MM/YYYY');

	//location.replace(`/admin/registrados?inicio=${startDate}&fin=${endDate}`);
});

$('input[name="dates"]').on('apply.daterangepicker', function(ev, picker) {
    $(this).val(picker.startDate.format('DD/MM/YYYY') + ' - ' + picker.endDate.format('DD/MM/YYYY'));
	location.replace(`/admin/estudiantes-registrados?inicio=${startDate}&fin=${endDate}`);
});


$('#modal-delete- select[name="offices"]').select2();

// document.querySelector('#solicitudes_report')
// 	.addEventListener('click', () => {
// 		window.open(`/admin/solicitudes-report?inicio=${startDate}&fin=${endDate}&searchText=${document.querySelector('input[name="searchText"]').value}`);
// 	});

</script>
<script type="text/javascript" src="{{ URL::asset('plugins/jszip/jszip.js') }}"></script>
<script type="text/javascript" src="{{ URL::asset('plugins/xlsx/xlsx.js') }}"></script>
<script type="text/javascript" src="/js/solicitudes.crud.js"></script>
<script type="text/javascript">
	
  var xlf = document.getElementById('file');
  var X = XLSX;
  var index_imports = 0;
  var data_imports=null;
  var cant_imports=0;


xlf.addEventListener('change',handFile,false);

function handFile(e){
    var files = e.target.files;
    var f = files[0];
    {
        var reader = new FileReader();
        var name = f.name;

        reader.onload = function(e) {
            //if(typeof console !== 'undefined') console.log("onload", new Date(), rABS, use_worker);
            var data = e.target.result;
            //console.log("-----------------------------------------------------");
            //console.log(data);
            var arr = fixdata(data);
            //console.log("--------------------");
            //console.log(arr);
            var wb;
            wb = X.read(btoa(arr), {type: 'base64'});
            //console.log(wb);
            process_wb(wb);
        };
        reader.readAsArrayBuffer(f);
    }

    //var files = e.
}

function fixdata(data) {
    var o = "", l = 0, w = 10240;
    for(; l<data.byteLength/w; ++l) o+=String.fromCharCode.apply(null,new Uint8Array(data.slice(l*w,l*w+w)));
    o+=String.fromCharCode.apply(null, new Uint8Array(data.slice(l*w)));
    return o;
}

function to_csv(workbook) {
    var result = [];
    workbook.SheetNames.forEach(function(sheetName) {
        var csv = X.utils.sheet_to_csv(workbook.Sheets[sheetName], null, "=?");
        //console.log(csv);
        if(csv.length > 0){
            /*result.push("SHEET: " + sheetName);
            result.push("");*/
            result.push(csv);
        }
    });
    return result.join("\n");
}

var out;

function process_wb(wb){
    output = to_csv(wb);
    output = output.split('\n');
    out = output;
    var cant = calc_cant(output);
    data_imports=clear_data(output); //Crear otro registro pero sin filas en blanco
    console.log(data_imports);
}

function calc_cant(data){
    var cant=0;
    if(data!=""){ // los registros a partir de la fila 2
        for(var i=2;i<data.length;i++){
            if(data[i].split('=?')[0]!=""){
                cant++;
            }
        }
    }
    return cant;
}

function clear_data(data){
    var cant=0;
    var j=0;
    var aux = new Array();
    if(data!=""){ // los registros a partir de la fila 3
        for(var i=1;i<data.length;i++){
            if(data[i].split('=?')[0]!=""){
                aux[j]=data[i];
                cant++;
                j++;
            }
        }
    }
    return aux;
}

  $(`#student__import`).on('click', function(e){
    e.preventDefault();

	if (!data_imports) {
		notice("Advertencia", "No se han seleccionado datos para importar", `warning`);
		return;
	}

    lockWindow();
      let nivel = new Array();
      //let grade = new Array();
      //let section = new Array();
      //let identity_document_type = new Array();
      let identity_document = new Array();
      let surname = new Array();
      //let maternal_surname = new Array();
      let name = new Array();
      //let gender = new Array();
      //let birthday = new Array();
      //let age = new Array();

      data_imports.forEach((value, index) => {
        
        splitted = value.split(`=?`);
        identity_document[index] = splitted[1];
        name[index] = splitted[2];
		surname[index] = splitted[3];
        nivel[index] = splitted[4];
		// grade[index] = splitted[2];
        // section[index] = splitted[3];
        // identity_document_type[index] = splitted[4];
        // paternal_surname[index] = splitted[6];
        // maternal_surname[index] = splitted[7];
        // gender[index] = splitted[9];
        // birthday[index] = splitted[10];
        // age[index] = splitted[11];

      });

      axios.post(`/admin/students/importv2`, {
        nivel,
        identity_document,
        surname,
        name,
      })
      .then((response) => {
        unlockWindow();
		notice(response.data.title, response.data.message, `success`);
        location.reload();
      })
      .catch((error) => {
      	unlockWindow();
		notice(error.response.data.title, error.response.data.message, `warning`);
      });
  });


</script>
@endpush
@endsection
