@extends('layouts.admin')
@section('contenido')
<div class="row">

	@if(session()->has('data'))
		<div class="col-md-12 text-danger">
			{{ session()->get('data')[0] }}
		</div>
	@endif

	<div class="col-md-12">
		<h3 class="font-bold">Candidatos <button type="button" class="btn btn-primary btn-sm"
				id="new_candidate">Nuevo</button></h3>
	</div>
</div>

<div class="row">
	<div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="table-responsive table_solicitud mt-4">
			<table class="table table-striped table-bordered table-condensed table-hover table-sm">
				<thead class="thead-dark">
					<th>#</th>
					<th>Fecha de creación</th>
					<th>Cédula</th>
					<th>Nombres</th>
					<th>Apellidos</th>
					<th>Cargo</th>
					<th>Foto</th>
					<th>Logo</th>
					<th>Opciones</th>
				</thead>
				@foreach($candidates as $key => $cat)
					<tr>
						<td>{{ $key+1 }}</td>
						<td>{{ \Date::parse($cat->created_at)->format('d/F/Y H:i') }}
						</td>
						<td>{{ $cat->cedula }}</td>
						<td>{{ $cat->firstname }}</td>
						<td>{{ $cat->lastname }}</td>
						<td><b>{{ $cat->position }}</b></td>
						<td class="text-center">
							<img src="{{ $cat->photo }}" width="100">
						</td>
						<td class="text-center">
							<img src="{{ $cat->logo }}" width="100">
						</td>

						<td class="text-center">
							@if($admin)
								<a href="javascript:void(0);" data-id="{{ $cat->id }}" class="btn py-0 px-1"
									title="Editar" style="background-color: #87CEEB; border-color: #87CEEB;"
									onclick="editCandidate(this)"><i class="fas fa-pencil-alt"></i></a>
							@endif
						</td>
					</tr>
				@endforeach
			</table>
		</div>
		{{ $candidates->appends(request()->input())->render() }}
	</div>
</div>
@include('almacen.solicitude.new_candidate')

@push('scripts')
	<script>
		$('#liGeneracionInterna').addClass("treeview active");
		$('#liCandidates').addClass("active");

		document.querySelector('#new_candidate')
			.addEventListener('click', () => {
				$('#modal-candidate').modal('show');
				$('#modal-candidate .update').hide();
				$('#modal-candidate .create').show();
			});
	</script>
	<script type="text/javascript" src="/js/candidates.js"></script>

@endpush
@endsection