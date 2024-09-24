<div class="modal fade" id="modal-entity-{{$cat->id}}">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-dark text-white">
				<h4 class="modal-title" id="modal-title">Remitente</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">

				<div class="row">
					<div class="col-md-6">
						<div class="form-group mb-1">
							<label class="etiqueta">Nombres:</label>
							<input type="text" class="form-control" value="{{ $cat->name }}" disabled="disabled">
						</div>
					</div>
					<div class="col-6">
						<div class="form-group mb-1">
							<label class="etiqueta">Apellidos:</label>
							<input type="text" class="form-control" value="{{ $cat->paternal_surname }} {{ $cat->maternal_surname }}" disabled="disabled">
						</div>
					</div>
					<div class="col-6">
						<div class="form-group mb-1">
							<label class="etiqueta">Teléfono:</label>
							<input type="text" class="form-control" value="{{ $cat->cellphone }}" disabled="disabled">
						</div>
					</div>
					<div class="col-6">
						<div class="form-group mb-1">
							<label class="etiqueta">Correo:</label>
							<input type="text" class="form-control" value="{{ $cat->email }}" disabled="disabled">
						</div>
					</div>
				</div>

				<!-- <div class="row">
					<div class="col-md-6">
						<div class="form-group mb-1">
							<label class="etiqueta">Apellido Paterno:</label>
							<input type="text" class="form-control" value="{{ $cat->paternal_surname }}" disabled="disabled">
						</div>
						<div class="form-group mb-1">
							<label class="etiqueta">Apellido Materno:</label>
							<input type="text" class="form-control" value="{{ $cat->maternal_surname }}" disabled="disabled">
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group mb-1">
							<label class="etiqueta">Documento de Identidad:</label>
							<input type="text" class="form-control" value="" disabled="disabled">
						</div>
						<div class="form-group mb-1">
							<label class="etiqueta">Teléfono:</label>
							<input type="text" class="form-control" value="{{ $cat->cellphone }}" disabled="disabled">
						</div>
						<div class="form-group mb-1">
							<label class="etiqueta">Correo:</label>
							<input type="text" class="form-control" value="{{ $cat->email }}" disabled="disabled">
						</div>
					</div>
				</div>
				<div class="form-group mb-0">
					<label class="etiqueta">Dirección:</label>
					<input type="text" class="form-control" value="{{ $cat->address }}" disabled="disabled">
				</div> -->
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>
