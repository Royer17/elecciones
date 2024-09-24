<div class="modal fade modal-slide-in-right" aria-hidden="true"
role="dialog" tabindex="-1" id="modal-password">
	<form id="change-password-form" method="POST" action="/admin/user/{{ \Auth::user()->id }}/password">
	{{ csrf_field() }}
	<input type="hidden" name="_method" value="PUT">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h4 class="modal-title" id="modal-title">Actualizar contraseña</h4>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body">
				<div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" class="form-control password" name="password">
                </div>
				<div class="form-group">
                    <label for="password_confirmation">Confirmar Password</label>
                    <input type="password" class="form-control confirm-password" name="password_confirmation">
                </div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
				<button type="button" class="btn btn-primary update">Confirmar</button>
			</div>
		</div>
	</div>
	</form>

</div>
