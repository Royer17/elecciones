@extends ('layouts.admin')
@section ('contenido')
<div class="row">

	@if (session()->has('data'))
	<div class="col-md-12 text-danger">
		{{ session()->get('data')[0] }}
	</div>
	@endif

	<div class="col-md-12">
		<h3 class="font-bold">Listado de Trámites finalizados</h3>
		<div class="row align-items-end">
			<div class="col-md-4 mb-3">
				{!! Form::open(array('url'=>'admin/finalizados','method'=>'GET','autocomplete'=>'off','role'=>'search')) !!}
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
					<input type="text" class="form-control" name="dates" value="">
			  </div>
			</div>
			<div class="col-sm-auto mb-3">
				<label class="etiqueta">Exportar en:</label><br>
				<button class="btn btn-primary" id="solicitudes_report">Excel</button>
				<a href="/admin/solicitudes-report-pdf" class="btn btn-primary" target="_blank">PDF</a>
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
					<th>Fecha de finalización</th>
					<th>Tipo de documento</th>
					<th>Número</th>
					<th>Asunto</th>
					<th>De:</th>
					<th>Estado</th>
					<th>Doc. Adjunto</th>
					<th>Opciones</th>
				</thead>
               @foreach ($orders as $key => $cat)
				<tr>
					<td>{{ $key+1}}</td>
					<td>{{ $cat->code}}</td>
					<td>{{ \Date::parse($cat->created_at)->format('d/F/Y H:i') }}</td>
					<td>{{ \Date::parse($cat->finished_at)->format('d/F/Y H:i') }}</td>
					<td>{{ $cat->document_type_name}}</td>
					<td>{{ $cat->number}}</td>
					<td>{{ substr($cat->subject, 0, 50)}}...</td>
					<td><a href="" data-toggle="modal" data-target="#modal-entity-{{$cat->id}}"> {{ $cat->name }} {{ $cat->paternal_surname }} {{ $cat->maternal_surname }}</a></td>
					<td>{{ $cat->status_name}}</td>
					<td><a href="{{ $cat->attached_file}}" class="btn btn-success action py-0 px-1" target="_blank"><i class="fa fa-eye mr-2"></i>Ver</a></td>
					<td>
						{{--
						<a href="{{URL::action('OfficeController@edit',$cat->id)}}"><button class="btn btn-info">Enviar</button></a>
						<a href="{{URL::action('OfficeController@edit',$cat->id)}}"><button class="btn btn-success">Archivar y Adjuntar</button></a>
						--}}
						@if($cat->status == 1)
						<a href="" data-index="{{ $cat->id }}" data-new_status="3" data-target="#modal-delete-" data-toggle="modal"><button class="btn btn-info action" style="margin: 2px;padding: 4px;">RECIBIR</button></a>
						@endif

						@if($cat->status == 3)
						<a href="" data-index="{{ $cat->id }}" data-new_status="2" data-target="#modal-delete-" data-toggle="modal"><button class="btn btn-info action" style="margin: 2px;padding: 4px;">DERIVAR</button></a>
						<a href="" data-index="{{ $cat->id }}" data-new_status="4" data-target="#modal-delete-" data-toggle="modal"><button class="btn btn-success action" style="margin: 2px;padding: 4px;">FINALIZAR TRÁMITE/ADJUNTAR</button></a>
						@endif

						@if(!$admin)
							@if($cat->status == 2 && $cat->parent_order_id != 0)
							{{--
							<a href="" data-index="{{ $cat->id }}" data-new_status="4" data-target="#modal-delete-" data-toggle="modal"><button class="btn btn-success action" style="margin: 2px;padding: 4px;">FINALIZAR TRÁMITE</button></a>
							--}}
							@endif
						@endif
						{{--
						<form action="/admin/ruta-de-solicitud" method="POST" target="_blank">
							{{ csrf_field() }}
							<input type="hidden" name="solicitude_id" value="{{ $cat->id }}">
							<button type="submit" class="btn btn-success" style="margin: 2px;padding: 4px;">Ver Ruta</button>
						</form>
						--}}
						<a href="" data-index="{{ $cat->id }}" class="btn btn-primary solicitude__see" style="margin: 2px;padding: 4px;">Seguimiento</a>
						@if($admin)
						<a href="" data-index="{{ $cat->id }}" class="btn btn-danger solicitude__delete" style="margin: 2px;padding: 4px;">Eliminar</a>
						@endif
					</td>
				</tr>
				@include('almacen.solicitude.entity')
				@endforeach
			</table>
		</div>
		{{$orders->render()}}
	</div>
</div>
@include('almacen.solicitude.modal')

@push ('scripts')
<script>
$('#liSolicitudes').addClass("treeview active");
$('#liFinished').addClass("active");

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

let startDate = "";
let endDate = "";

$('input[name="dates"]').daterangepicker({
    locale: {
      format: 'DD/MM/YYYY',
    },
    startDate: document.querySelector('.daterangepicker-area input[name="start_date"]').value,
    endDate: document.querySelector('.daterangepicker-area input[name="end_date"]').value,
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

	location.replace(`/admin/finalizados?inicio=${startDate}&fin=${endDate}`);
});

$('#modal-delete- select[name="offices"]').select2();

document.querySelector('#solicitudes_report')
	.addEventListener('click', () => {
		window.open(`/admin/solicitudes-report?incio=${document.querySelector('.daterangepicker-area input[name="start_date"]').value}&fin=${document.querySelector('.daterangepicker-area input[name="end_date"]').value}&document_status=${document.querySelector('select[name="document_status"]').value}`);
	});

</script>
<script type="text/javascript" src="/js/solicitudes.crud.js"></script>
@endpush
@endsection
