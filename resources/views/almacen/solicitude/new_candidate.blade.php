<div class="modal fade modal-slide-in-right" id="modal-candidate">
	<form method="POST" enctype="multipart/form-data" files=true>
		{{ csrf_field() }}
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header bg-primary text-white">
					<h4 class="modal-title" id="modal-title">Cambiar de estado</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<input type="hidden" name="order_id" id="order_id">
					<input type="hidden" name="status" id="status_id">
		            <div class="form-group send-office">
		            	<label for="office_id">Cargo:</label>
		            	<select class="form-control" name="office_id">
		            		<option value="">Seleccione</option>
		            		<option>Alcalde</option>
		            	</select>
		            </div>

		            <div class="form-group send-office">
		            	<label for="office_id">Cédula:</label>
		            	<input type="text" class="form-control" name="" placeholder="Ingrese Cédula">
		            </div>

		            <div class="form-group send-office">
		            	<label for="office_id">Nombres:</label>
		            	<input type="text" class="form-control" name="" placeholder="Ingrese Nombres">
		            </div>

		            <div class="form-group send-office">
		            	<label for="office_id">Apellidos:</label>
		            	<input type="text" class="form-control" name="" placeholder="Ingrese Apellidos">
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
						<input type="file" name="attached_file" class="form-control">
					</div>
					<div class="form-group attach-file">
						<label for="sigla">Logo</label>
						<input type="file" name="attached_file" class="form-control">
					</div>

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary create" id="solicitude__update">Crear</button>
					<button type="button" class="btn btn-primary update" id="solicitude__update">Actualizar</button>

				</div>
			</div>
		</div>
	</form>
</div>
