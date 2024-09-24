@extends ('layouts.admin')
@section ('contenido')
<div class="row">

	@if (session()->has('data'))
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-danger">
		{{ session()->get('data')[0] }}
	</div>
	@endif

	<div class="col-md-12">
		<h3 class="font-bold">Listado de Trámites recibidos</h3>
		<div id="filters_accordion">
			<div class="card">
		    <div class="card-header py-1">
		      <a class="collapsed card-link" data-toggle="collapse" href="#filter_1"><div class="title_filters"><label>Filtros</label><i class="fa fa-filter"></i></div></a>
		    </div>
		    <div id="filter_1" class="collapse" data-parent="#filters_accordion">
		      <div class="card-body py-1">
						<div class="row align-items-end">
							<div class="col-md-4 mb-3">
								{!! Form::open(array('url'=>'admin/recibidos','method'=>'GET','autocomplete'=>'off','role'=>'search')) !!}
								<div class="form-group mb-0">
							    <label class="etiqueta">Buscar Trámite:</label>
									<div class="input-group">
									  <input type="text" class="form-control" name="searchText" placeholder="Buscar por código, DNI o RUC..." value="{{$searchText}}">
									  <div class="input-group-append">
									    <button type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button>
									  </div>
									</div>
							  </div>
								{{Form::close()}}
							</div>
							<div class="col-md-3 mb-3">
								<div class="form-group daterangepicker-area mb-0">
							    <label class="etiqueta">Rango de fecha:</label>
									<input type="hidden" name="start_date" value="{{ $start_date }}">
									<input type="hidden" name="end_date" value="{{ $end_date }}">
									<input type="text" class="form-control" name="dates" value="" autocomplete="off">
							  </div>
							</div>

							<div class="col-sm-auto mb-3">
								<a href="/admin/recibidos" class="btn btn-success">Reset</a>
							</div>

							<div class="col-sm-auto mb-3 d-none">
								<label class="etiqueta">Exportar en:</label><br>
								<button class="btn btn-primary" id="solicitudes_report">Excel</button>
								<a href="/admin/solicitudes-report-pdf" class="btn btn-primary" target="_blank">PDF</a>
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
					<th>Fecha Ingreso</th>
					<th>Numeración</th>
					<th>Número</th>
					<th>Asunto</th>
					<th>Procedencia:</th>
					<!-- <th>Oficina remitente</th> -->
					<th>Estado</th>
					<th>Doc. Adjunto</th>
					<th>Plazo</th>
					<th>Opciones</th>
				</thead>
        @foreach ($orders as $key => $cat)
					<tr>
						<td>{{ $key+1}}</td>
						<td>{{ $cat->code}}</td>
						<td>{{ \Date::parse($cat->created_at)->format('d/F/Y H:i') }}</td>
						<td class="font-bold">{{ $cat->internal_code ? strtoupper($cat->internal_code) : $cat->document_type_name }}</td>
						<td>{{ $cat->number}}</td>
						<td>{{ substr($cat->subject, 0, 50)}}...</td>
						<td><a href="" data-toggle="modal" data-target="#modal-entity-{{$cat->id}}"> {{ $cat->name }} {{ $cat->paternal_surname }} {{ $cat->maternal_surname }}</a>({{ $cat->entity_office_name ? $cat->entity_office_name : "WEB"  }})</td>
						{{--<td>{{ $cat->office_name ? $cat->office_name : "WEB" }}</td>--}}
						<td>{{ $cat->status_name}}</td>
						<td>
							@if($cat->attached_file)
							<a href="{{ $cat->attached_file}}" class="btn btn-success py-0 px-1" target="_blank"><i class="fa fa-eye mr-2 notPointerEvent"></i>Ver</a>
							@else
								No archivo adjunto
							@endif
						</td>
						<td>
							@if($cat->term_end)
								@php
									$start_from = \Carbon\Carbon::now();
									$end_to = \Carbon\Carbon::parse($cat->term_end);
									$diff_in_days = $start_from->diff($end_to)->days;
								@endphp
								@if($diff_in_days >= 7)
									<span style="color:green;">{{ $diff_in_days }} días ({{ $cat->term }})</span>
								@elseif($diff_in_days >= 4)
									<span style="color:orange;">{{ $diff_in_days }} días ({{ $cat->term }})</span>
								@else
									@if($diff_in_days)
										<span style="color:red;">{{ $diff_in_days }} días ({{ $cat->term }})</span>
									@else
										<span style="color:red;">{{ $end_to->diffInHours($start_from, true) }} horas ({{ $cat->term }})</span>
									@endif
								@endif
							@endif
						</td>

						<td>
							<a href="#" data-index="{{ $cat->id }}" class="btn btn-secondary text_option py-0 px-1 answer-solicitude" data-target="#answer-modal" data-toggle="modal" title="Responder"><i class="fa-solid fa-file-pen notPointerEvent"></i></a>
							@if($cat->status == 1)
							<a href="" data-index="{{ $cat->id }}" data-new_status="3" data-target="#modal-delete-" data-toggle="modal" class="btn btn-info action py-0 px-1" title="RECIBIR"><i class="fa-regular fa-envelope-open"></i></a>
							@endif

							@if($cat->status == 3)
							<a href="" data-index="{{ $cat->id }}" data-new_status="2" data-target="#modal-delete-" data-toggle="modal" class="btn btn-info action py-0 px-1" title="DERIVAR"><i class="fa fa-paper-plane notPointerEvent"></i></a>
							<a href="" data-index="{{ $cat->id }}" data-new_status="4" data-target="#modal-delete-" data-toggle="modal" class="btn btn-success action py-0 px-1" title="FINALIZAR TRÁMITE/ADJUNTAR"><i class="fa fa-check-square-o notPointerEvent"></i></a>
							@endif

							<a href="" data-index="{{ $cat->id }}" class="btn btn-primary solicitude__see py-0 px-1" title="Seguimiento"><i class="fa fa-search-plus notPointerEvent"></i></a>

							@if($admin)
							<a href="" data-index="{{ $cat->id }}" class="btn btn-danger solicitude__delete py-0 px-1 d-none" title="Eliminar"><i class="fa fa-trash notPointerEvent"></i></a>
							@endif
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
@include('almacen.solicitude.state.answer_modal')

@push ('scripts')
<script>
const labelDocumentTypeCode = document.querySelector('#document_type_code');
const inputCurrentOffice = document.querySelector('#answer_form input[name="parent_office_id"]');
const selectDocumentType = document.querySelector('#answer_form select[name="document_type_id"]');

	$('.simple-document').show();
	$('.multiple-document').hide();

labelDocumentTypeCode.innerHTML = "";

$('#liSolicitudes').addClass("treeview active");
$('#liReceived').addClass("active");

$(`#update-status-form`)[0].reset();

const modalTitle = document.querySelector('#update-status-form .modal-title');

$(document).on('click', '.action', function(e){
	e.preventDefault();

	let _that = $(this).parent(), _order_id = e.target.dataset.index, _new_status = e.target.dataset.new_status;

	$(`.send-office`).hide();
	$(`.attach-file`).hide();

	if (_new_status == 2) {
		$(`.send-office`).show();
		//$(`.attach-file`).show();
	}

	if (_new_status == 4) {
		//$(`.attach-file`).show();
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
		if ($(`#status_id`).val() == 2) {
			if ($('#modal-delete- select[name="office_id"]').val() == "") {
                notice(`Advertencia`, `Elija una oficina.`, `warning`);
				return;
			}
		}

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
                    //location.reload();

                    if ($(`#status_id`).val() == 2) {
	                    setTimeout(function(){
	                    	location.replace('/admin/enviados');
	                     }, 1000);
                    	return;
                    }

					if ($(`#status_id`).val() == 4) {
	                    setTimeout(function(){
	                    	location.replace('/admin/finalizados');
	                     }, 1000);
                    	return;
                    }

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
	location.replace(`/admin/recibidos?inicio=${startDate}&fin=${endDate}`);
});

$('#modal-delete- select[name="offices"]').select2();
$('#modal-delete- select[name="office_id"]').select2();
$('#answer-modal select[name="offices_id"]').select2();

document.querySelector('#solicitudes_report')
	.addEventListener('click', () => {
		window.open(`/admin/solicitudes-report?incio=${document.querySelector('.daterangepicker-area input[name="start_date"]').value}&fin=${document.querySelector('.daterangepicker-area input[name="end_date"]').value}&document_status=${document.querySelector('select[name="document_status"]').value}`);
	});

function getExtension(filename) {
  var parts = filename.split('.');
  return parts[parts.length - 1];
}

document.querySelector(`#answer-sent`)
    .addEventListener('click', () => {

        if (document.querySelector('#answer-modal input[name="attached_file"]').value) {
            var ext = getExtension(document.querySelector('#answer-modal input[name="attached_file"]').value);

            if (ext != "pdf") {
                notice(`Advertencia`, `Sólo están permitidos archivos PDF.`, `warning`);
                return;
            }
        }

        lockWindow();

        $(`.error-message`).empty();

        let _formData = new FormData($(`#answer_form`)[0]);
        _formData.append('offices_cc_arr', $(`select[name="offices_cc"]`).val());
        _formData.append('multiple_offices_id', $(`select[name="offices_id"]`).val());

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('input[name=_token]').val()
            }
        });
        $.ajax({
            url : `/answer-solicitude`,
            type: 'POST',
            data: _formData,
            contentType: false,
            processData: false,
            success: function(e){
                unlockWindow();
                notice(`${e.title}`, `${e.message}`, `success`);
                document.querySelector('#answer_form').reset();
               	$('#answer-modal').modal('hide');
               	//location.reload();
                setTimeout(function(){
                    location.replace(`/admin/mis-solicitudes-enviadas`);
                 }, 1000);

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


$('.answer-solicitude').on('click', function(e){
	document.querySelector('#answer_form input[name="parent_order_id"]').value = e.target.dataset.index;
});

let changeCCSelect = false;

$('#answer_form select[name="document_type_id"]')
    .on('change', function(){
        getDocumentTypeCode();
        changeCCSelect = false;
    });

$('#answer_form select[name="office_id"]')
    .on('change', function(){
        getDocumentTypeCode();
        changeCCSelect = true;
    });

function getDocumentTypeCode(){

    // if (!$('#answer_form select[name="document_type_id"]').val()) {
    //     return;
    // }

    // if (!$('#answer_form select[name="office_id"]').val()) {
    //     return;
    // }

    labelDocumentTypeCode.innerHTML = "";

    $('.simple-document').show();
    $('.multiple-document').hide();

    // if (selectDocumentType.value == "") {
    //     return;
    // }

    axios.get(`/admin/document-type-code?office_id=${inputCurrentOffice.value}&document_type_id=${selectDocumentType.value}&destination_office_id=${$('#answer_form select[name="office_id"]').val()}`)
    .then((response) => {
        labelDocumentTypeCode.innerHTML = response.data.code;
        const offices = response.data.offices;
        document.querySelector('#answer_form input[name="internal_code"]').value = response.data.code;
        
        if (response.data.multiple == 1) {
            $('select[name="offices_cc"]').html("").trigger('change');
            $('.simple-document').hide();
            $('.multiple-document').show();
            return;
        }

        if (changeCCSelect) {
            $('select[name="offices_cc"]').html("").trigger('change');

            offices.forEach( function(element, index) {
                $('select[name="offices_cc"]').append(`<option value='${element.id}'>${element.name}</option>`).trigger('change');
            });
        }
        

    }).catch((error) => {
      console.error(error);
    }).finally(() => {
      // TODO
    });

}

</script>
<script type="text/javascript" src="/js/solicitudes.crud.js"></script>
@endpush
@endsection
