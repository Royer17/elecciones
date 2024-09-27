<div class="modal fade modal-slide-in-right" id="modal-candidate">
	<form method="POST" enctype="multipart/form-data" files=true>
		{{ csrf_field() }}
		<input type="hidden" name="id">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header bg-primary text-white">
					<h4 class="modal-title" id="modal-title">Cambiar de estado</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
		            <div class="form-group">
		            	<label for="office_id">Cargo:</label>
		            	<select class="form-control" name="position">
		            		<option value="">Seleccione</option>
		            		<option value="Alcalde">Alcalde</option>
		            	</select>
						<div class="text-danger error-message" id="candidate-position-error"></div>
		            </div>

		            <div class="form-group">
		            	<label for="nivel">Nivel:</label>
		            	<select class="form-control" name="nivel" id="nivel">
		            		<option value="">Seleccione</option>
		            		<option value="1">Primaria</option>
		            		<option value="2">Secundaria</option>
		            	</select>
		            	<div class="text-danger error-message" id="candidate-nivel-error"></div>
		            </div>

		            <div class="form-group">
		            	<label for="office_id">Cédula:</label>
		            	<input type="text" class="form-control" name="cedula" placeholder="Ingrese Cédula">
						<div class="text-danger error-message" id="candidate-cedula-error"></div>
		            </div>

		            <div class="form-group">
		            	<label for="office_id">Nombres:</label>
		            	<input type="text" class="form-control" name="firstname" placeholder="Ingrese Nombres">
						<div class="text-danger error-message" id="candidate-firstname-error"></div>

		            </div>

		            <div class="form-group">
		            	<label for="office_id">Apellidos:</label>
		            	<input type="text" class="form-control" name="lastname" placeholder="Ingrese Apellidos">
						<div class="text-danger error-message" id="candidate-lastname-error"></div>
		            </div>


		            <div class="form-group d-none">
		            	<label for="office_id">CC:</label>
		            	<select class="form-control" name="offices" multiple>
		            		@foreach($offices as $office)
		            		<option value="{{ $office['id'] }}">{{ $office['name'] }}</option>
		            		@endforeach
		            	</select>
		            </div>

					<div class="form-group attach-file">
						<label for="sigla">Foto</label>
						<input type="file" name="photo" class="form-control">
					</div>
					<div class="form-group attach-file">
						<label for="sigla">Logo</label>
						<input type="file" name="logo" class="form-control">
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary save"">Crear</button>
					<button type="button" class="btn btn-primary update">Actualizar</button>

				</div>
			</div>
		</div>
	</form>
</div>
