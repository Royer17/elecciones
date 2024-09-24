@extends ('layouts.admin')
@section ('contenido')
<div class="row">

	@if (session()->has('data'))
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12 text-danger">
		{{ session()->get('data')[0] }}
	</div>
	@endif
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h3>Nuevos trámites generados desde la WEB</h3>
		@include('almacen.solicitude.new_search')
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive">
			<table class="table table-striped table-bordered table-condensed table-hover">
				<thead>
					<th>#</th>
					<th>Código</th>
					<th>Fecha Ingreso</th>
					<th>Tipo de documento</th>
					<th>Número</th>
					<th>Asunto</th>
					<th>De:</th>
					<th>Oficina remitente</th>
					<th>Estado</th>
					<th>Doc. Adjunto</th>
					<th>Opciones</th>
				</thead>
               @foreach ($orders as $key => $cat)
				<tr>
					<td>{{ $key+1}}</td>
					<td>{{ $cat->code}}</td>
					<td>{{ \Date::parse($cat->created_at)->format('d/F/Y H:i') }}</td>
					<td>{{ $cat->document_type_name}}</td>
					<td>{{ $cat->number}}</td>
					<td>{{ substr($cat->subject, 0, 50)}}...</td>
					<td> <a href="" data-toggle="modal" data-target="#modal-entity-{{$cat->id}}"> {{ $cat->name }} {{ $cat->paternal_surname }} {{ $cat->maternal_surname }}</a>
					</td>
					<td>{{ $cat->office_parent_name ? $cat->office_parent_name : "WEB" }}</td>
					<td>{{ $cat->status_name}}</td>
					<td><a href="{{ $cat->attached_file}}" target="_blank">Ver</a></td>
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

						@if(!$flag)
							@if($cat->status == 2 && $cat->parent_order_id != 0)
							{{-- 
							<a href="" data-index="{{ $cat->id }}" data-new_status="4" data-target="#modal-delete-" data-toggle="modal"><button class="btn btn-success action" style="margin: 2px;padding: 4px;">FINALIZAR TRÁMITE</button></a>
							--}}
							@endif
						@endif
						<form action="/admin/ruta-de-solicitud" method="POST" target="_blank">
							{{ csrf_field() }}
							<input type="hidden" name="solicitude_id" value="{{ $cat->id }}">
							<button type="submit" class="btn btn-success" style="margin: 2px;padding: 4px;">Ver Ruta</button>
						</form>
						@if(!$flag || $cat->status != 2)
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
$('#liNewSolicitudes').addClass("treeview active");

$(`#update-status-form`)[0].reset();

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

			$(`#modal-title`).html(`Se va a cambiar el estado a: ${response.data.name}`);

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
                    notice(`Advertencia`, `Hay errores en uno o más campos.`, `warning`);
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

let startDate;
let endDate;

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

	location.replace(`/admin/nuevas-solicitudes?inicio=${startDate}&fin=${endDate}`);
});

$('#modal-delete- select[name="offices"]').select2();

</script>
@endpush
@endsection