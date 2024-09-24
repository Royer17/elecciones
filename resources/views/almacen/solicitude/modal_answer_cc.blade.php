<div class="modal fade modal-slide-in-right" id="modal-answer-cc">
	<form>
		{{ csrf_field() }}
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header bg-primary text-white">
					<h4 class="modal-title" id="modal-title">Responder CC</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<div class="modal-body">
					<input type="hidden" name="order_id">
					<!-- <input type="hidden" name="status" id="status_id"> -->
					<div class="form-group">
						<label for="sigla">Observación</label>
						<textarea class="form-control" name="observations" placeholder="Observación..."></textarea>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
					<button type="button" class="btn btn-primary" id="solicitude__answer">Responder</button>
				</div>
			</div>
		</div>
	</form>
</div>
