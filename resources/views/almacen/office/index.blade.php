@extends ('layouts.admin')
@section ('contenido')
<div class="row">
	<div class="col-lg-8 col-md-8 col-sm-8 col-xs-12">
		<h3 class="font-bold">Listado de Conceptos de Pago {{-- <a href="/admin/oficinas/create"><button class="btn btn-success">Nuevo</button></a> --}}</h3>
		@include('almacen.office.search')
	</div>
</div>

<div class="row">
	  @if (session()->has('data'))
	  	<p class="login-box-msg text-danger text-center">{{ session()->get('data')[0] }}</p>
	  @endif
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">

                <div class="table-responsive">
                  <table class="table" id="question-list">
                    <thead>
                      <tr>
                        <th>Nombre</th>
                        <th>Monto</th>
                        <th>Año</th>
                        <th>Acciones</th>
                      </tr>
                    </thead>
                    <tbody id="payment-concepts-list">
                      <tr>
                        <td>¿Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua?</td>
                        <td>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</td>
                        <td></td>
                        <td>
                          <a href="javascript:void(0);" class="text-info px-1" title="Editar" data-toggle="modal" data-target="#new-question"><i class="fas fa-pencil-alt"></i></a>
                          <a href="javascript:void(0);" class="text-danger px-1" title="Eliminar"><i class="fas fa-trash-alt"></i></a>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>

		<div class="table-responsive table_solicitud mt-4 d-none">
			<table class="table table-striped table-bordered table-condensed table-hover" id="payment-concepts-datatable" width="100%">
				<thead class="thead-dark">
					<th>#</th>
					<th>Código</th>
					<th>Nombre</th>
					<th>Fecha de creación</th>
					<th>Monto</th>
					<th>Orden</th>
					<th>Año</th>
					<th>¿Es para todos los años?</th>
					<th>Opciones</th>
				</thead>
				<tr>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</table>
		</div>
	</div>
</div>
@push ('scripts')
<script>
$('#liAcceso').addClass("treeview active");
$('#liOffice').addClass("active");
</script>
<script src="/js/payment_concepts.js"></script>
@endpush
@endsection
