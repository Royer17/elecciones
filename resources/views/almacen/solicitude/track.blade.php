<div class="modal fade modal-slide-in-right" id="modal-track">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header bg-primary text-white">
				<h4 class="modal-title" id="modal-title">Historial</h4>
        <button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body hr_bold">

				<div class="table-responsive">

				  <table class="table_detail">
						<tr>
							<td class="title_table">Código:</td>
							<td><b><span class="internal_code"></span></b></td>
							<td class="title_table">Numeración:</td>
							<td class="text-uppercase"><b><span class="code"></span></b></td>
						</tr>
						<tr>
							<td class="title_table">Tipo de procedimiento:</td>
							<td><b><span class="tupa"></span></b></td>
							<td class="title_table">Emisor:</td>
							<td><b><span class="name"></span> <span class="paternal_surname"></span> <span class="maternal_surname"></span></b></td>
						</tr>
					</table>

					<table class="table_detail">
						<tr>
							<td class="title_table">Fecha:</td>
							<td><b class="date">19/7/2022</b></td>
							<td class="title_table">Nº de documento:</td>
							<td><b><span class="nro_document"></span></b></td>
						</tr>
						<tr>
							<td class="title_table">Folios:</td>
							<td><b><span class="folios"></span></b></td>
							<td class="title_table">Archivo adjuntado:</td>
							<td>
								<a href="#" target="_blank" class="btn btn-info btn-sm file_attached">Ver Archivo</a>
								<span class="file_not_attached">No archivo adjunto</span>
							</td>
						</tr>
						<tr>
							<td class="title_table">Asunto:</td>
							<td colspan="3"><b><span class="subject"></span></b></td>
						</tr>
						<tr>
							<td class="title_table">Tipo de atención:</td>
							<td colspan="3"><b><span class="order_type"></span></b></td>
						</tr>
						<tr>
							<td class="title_table">Observaciones:</td>
							<td colspan="3"><b><span class="notes"></span></b></td>
						</tr>
					</table>
				</div>

				<div class="row" style="display: none;">
					<div class="col-md-6">
						<label class="mb-0 w-100">Expediente:</label>
						<h4 class="font-bold"><b class="code"></b></h4>
					</div>
					<div class="col-md-6">
						<label class="mb-0 w-100">Tipo de persona:</label>
						<h6 class="font-bold type_document"></h6>
					</div>
					<div class="col-md-6">
						<label class="mb-0 w-100">DNI:</label>
						<h6 class="font-bold identity_document"></h6>
					</div>
					<div class="col-md-6">
						<label class="mb-0 w-100">Apellido Paterno:</label>
						<h6 class="font-bold paternal_surname"></h6>
					</div>
					<div class="col-md-6">
						<label class="mb-0 w-100">Apellido Materno:</label>
						<h6 class="font-bold maternal_surname"></h6>
					</div>
					<div class="col-md-6">
						<label class="mb-0 w-100">Nombre Completo:</label>
						<h6 class="font-bold name"></h6>
					</div>
					<div class="col-md-6">
						<label class="mb-0 w-100">Teléfono:</label>
						<h6 class="font-bold cellphone"></h6>
					</div>
					<div class="col-md-6">
						<label class="mb-0 w-100">Correo:</label>
						<h6 class="font-bold email"></h6>
					</div>
					<div class="col-md-6">
						<label class="mb-0 w-100">Documento remitido:</label>
						<h6 class="font-bold folios"></h6>
					</div>
					<div class="col-md-6">
						<label class="mb-0 w-100">Asunto:</label>
						<h6 class="font-bold subject"></h6>
					</div>
					<div class="col-md-6">
						<label class="mb-0 w-100">Notas y/o referencias:</label>
						<h6 class="font-bold notes"></h6>
					</div>
					<div class="col-md-6">
						<label class="mb-0 w-100">Tipo de procedimiento:</label>
						<h6 class="font-bold tupa"></h6>
					</div>
				</div>

				<div class="table-responsive table_modal_track pt-1">
				<table id="table-route" class="table table-bordered table-striped table-sm mb-0">
			      <caption style="caption-side:top;">Ruta</caption>
			      <thead class="thead-dark">
			        <tr>
			          <th>Procedencia</th>
			          <th>Destino</th>
			          <th>Estado</th>
			          <th>Fecha</th>
			          <th>Observación</th>
			          <th>Usuario que recibió</th>
			          <!-- <th>Archivo anexado</th> -->
			        </tr>
			      </thead>
			      <tbody>
			        <tr>
			          <th>SISTEMA WEB</th>
			          <th>MESA DE PARTES</th>
			          <td>11/06/2022</td>
			          <td>Ninguno</td>
			        </tr>
			        <tr>
			          <th>MESA DE PARTES</th>
			          <th>SISTEMA WEB</th>
			          <td>12/06/2022</td>
			          <td>Derivado</td>
			        </tr>
			      </tbody>
			    </table>
				</div>


			   <div id="order-children" class="table_modal_track table_dark"></div>

			   <div id="order-answered"></div>

			   <div id="order-multiple" class="table_modal_track table_dark"></div>


			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>
