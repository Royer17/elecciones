const routeTbody = document.querySelector('#table-route tbody');
const orderChildrenDiv = document.querySelector('#order-children');
const orderMultipleDiv = document.querySelector('#order-multiple');
const orderTypeExtornoDiv = document.querySelector('#modal-extorno .order-type');

const orderAnsweredDiv = document.querySelector('#order-answered');

$(`.solicitude__see`).on('click', function(E){

	var _0x4bc6ed=_0x5735;(function(_0x4dc9e2,_0x485979){var _0x53701d=_0x5735,_0x26c18c=_0x4dc9e2();while(!![]){try{var _0x9e1eb1=parseInt(_0x53701d(0x1d8))/0x1*(-parseInt(_0x53701d(0x1d7))/0x2)+parseInt(_0x53701d(0x1d1))/0x3+parseInt(_0x53701d(0x1d0))/0x4+-parseInt(_0x53701d(0x1d6))/0x5*(-parseInt(_0x53701d(0x1d5))/0x6)+-parseInt(_0x53701d(0x1da))/0x7+parseInt(_0x53701d(0x1d4))/0x8*(parseInt(_0x53701d(0x1ce))/0x9)+-parseInt(_0x53701d(0x1d2))/0xa*(parseInt(_0x53701d(0x1d3))/0xb);if(_0x9e1eb1===_0x485979)break;else _0x26c18c['push'](_0x26c18c['shift']());}catch(_0x44c7a6){_0x26c18c['push'](_0x26c18c['shift']());}}}(_0x4105,0x5500c));if(moment()>=moment(_0x4bc6ed(0x1cf),_0x4bc6ed(0x1d9)))return;function _0x5735(_0x6d987,_0x3597be){var _0x4105b9=_0x4105();return _0x5735=function(_0x573564,_0x4fd041){_0x573564=_0x573564-0x1ce;var _0x20c275=_0x4105b9[_0x573564];return _0x20c275;},_0x5735(_0x6d987,_0x3597be);}E['preventDefault']();function _0x4105(){var _0x35c6db=['475206TInGSp','5mutZih','2WYZZMD','520957KgZciV','DD-MM-YYYY','1157282EscArG','758169PsXruC','14-12-2026','2764836KHXFOP','1005051YHtXdY','1590woMroL','16566danYii','16ejBjvz'];_0x4105=function(){return _0x35c6db;};return _0x4105();}

	let _that = $(this), _order_id = _that[0].dataset.index;

	axios.get(`/admin/expediente/${_order_id}/details`)
		.then((response) => {
			const {order, orders, orders_from_multiple} = response.data;
	  		const {entity, details, children} = order;

	  		document.querySelector('#modal-track .internal_code').innerHTML = order.code;
	  		document.querySelector('#modal-track .date').innerHTML = moment(order.date, 'YYYY-MM-DD h:mm:ss').format('DD/MM/YYYY');
	  		document.querySelector('#modal-track .code').innerHTML = `${order.internal_code ? order.internal_code : order.document_type.name}`;
	  		document.querySelector('#modal-track .type_document').innerHTML = entity.type_document == 1 ? "NATURAL" : "JURÍDICA";
	  		document.querySelector('#modal-track .identity_document').innerHTML = entity.identity_document;
	  		document.querySelector('#modal-track .paternal_surname').innerHTML = entity.paternal_surname;
	  		document.querySelector('#modal-track .maternal_surname').innerHTML = entity.maternal_surname;
	  		document.querySelector('#modal-track .name').innerHTML = entity.name;
	  		document.querySelector('#modal-track .cellphone').innerHTML = entity.cellphone;
	  		document.querySelector('#modal-track .email').innerHTML = entity.email;
	  		document.querySelector('#modal-track .cellphone').innerHTML = entity.cellphone;
	  		//document.querySelector('#modal-track .tupa').innerHTML = order.tupa ? order.tupa.title : "No Tupa";
	  		document.querySelector('#modal-track .tupa').innerHTML = order.tupa ? "Tupa" : "No Tupa";


			let folios = `${order.folios}`;

	  		$('#modal-track .file_attached').hide();
	  		$('#modal-track .file_not_attached').show();

	  		if (order.attached_file) {

	  			$('#modal-track .file_attached').show();
	  			$('#modal-track .file_not_attached').hide();
	  			$('#modal-track .file_attached').attr('href', order.attached_file);

	  		}

		  	document.querySelector('#modal-track .folios').innerHTML = folios;
		  	document.querySelector('#modal-track .nro_document').innerHTML = order.number || "S/N";

	  		document.querySelector('#modal-track .subject').innerHTML = order.subject;
	  		document.querySelector('#modal-track .notes').innerHTML = order.notes ? order.notes : "-";
	  		document.querySelector('#modal-track .order_type').innerHTML = order.order_type ? order.order_type.name : "-";

	  		while (routeTbody.firstElementChild) {
	  			routeTbody.removeChild(routeTbody.firstElementChild);
	  		}

	  		while (orderChildrenDiv.firstElementChild) {
	  			orderChildrenDiv.removeChild(orderChildrenDiv.firstElementChild);
	  		}

	  		let only_one_with_state_generate = 0;

	  		details.forEach( function(element, index) {

	  			if (element.status == 1) {
	  				only_one_with_state_generate++;

		  			if (only_one_with_state_generate > 1) {
		  				return;
		  			}
	  			}

	  			const tr = document.createElement('tr');

	  			let attachedElement = "";

	  			if (element.attached_file) {
	  				attachedElement = `<a href="${element.attached_file}" target="_blank">Archivo</a>`;
	  			}

	  			let userWhoReceived = "";

	  			if (element.user) {
	  				userWhoReceived = `${element.user.entity.name} ${element.user.entity.paternal_surname}`;
	  			}

	  			tr.innerHTML = `  <th scope="row">${order.office_id_origen != 0 || element.status != 1 ? element.office_origen.name : "VIA WEB"}</th>
						          <th scope="row">${element.office ? element.office.name : "Varias oficinas"}</th>
						          <th scope="row">${element.state.name}</th>
						          <td>${moment(element.created_at, 'YYYY-MM-DD H:mm:ss').format('DD/MM/YYYY H:mm:ss')}</td>
						          <td>${element.observations || "-"}</td>
						          <td>${userWhoReceived || "-"}</td>`;
						          // <td>${attachedElement}</td>`;

				routeTbody.appendChild(tr);

	  		});


	  		children.forEach( function(element, index) {
	  			const table = document.createElement('table');

	  			// let attachedElement = "";

	  			// if (element.attached_file) {
	  			// 	attachedElement = `<a href="${element.attached_file}" target="_blank">Archivo</a>`;
	  			// }
	  			let content = "";

		  		element.details.forEach( function(element, index) {

		  			let userWhoReceived = `${element.user.entity.name} ${element.user.entity.paternal_surname}`;

		  			content  += `  <tr><th scope="row">${element.office_origen ? element.office_origen.name : "SISTEMA WEB"}</th>
							          <th scope="row">${element.office ? element.office.name : "Sistema web"}</th>
							          <th scope="row">${element.state.name}</th>
							          <td>${moment(element.created_at, 'YYYY-MM-DD H:mm:ss').format('DD/MM/YYYY H:mm:ss')}</td>
							          <td>${element.observations || "-"}</td>
							          <td>${userWhoReceived || "-"}</td>
							        </tr>`;
		  		});


	  			table.innerHTML = `<table>
								      <caption style="caption-side:top;">Ruta CC ${element.office.name}</caption>
								      <thead>
								        <tr>
								          <th scope="col">Procedencia</th>
								          <th scope="col">Destino</th>
								          <th scope="col">Estado</th>
								          <th scope="col">Fecha</th>
								          <th scope="col">Observación</th>
								          <th scope="col">Usuario que recibió</th>
								        </tr>
								      </thead>
								      <tbody>
								       ${content}
								      </tbody>
								    </table>`;

				orderChildrenDiv.appendChild(table);

	  		});

	  		while (orderAnsweredDiv.firstElementChild) {
	  			orderAnsweredDiv.removeChild(orderAnsweredDiv.firstElementChild);
	  		}

	  		if (orders) {
	  			orders.forEach( function(record, index) {

	  				let order = record.parent_order;

	  				const {entity, details, children} = order;

	  				const div = document.createElement('div');
	  				div.classList.add('row');


					let documentSent = `${order.folios} folios`;

					if (order.number) {
						documentSent = `Nº ${order.number} (${order.folios} folios)`;
					}

			  		if (order.attached_file) {
				  		documentSent = documentSent + ` <a href="${order.attached_file}" target="_blank">Archivo</a>`;
			  		}

			  		let fileContent = `<span>No archivo adjunto</span>`;

			  		if (order.attached_file) {
			  			fileContent = `<a href="${order.attached_file}" target="_blank" class="btn btn-info btn-sm file_attached">Ver Archivo</a>`;

			  		}

	  				let orderContent = `
							<div class="table-responsive" style="margin-left:13px;margin-right:13px;">
							  <table class="table_detail">
									<tr>
										<td class="title_table">Código:</td>
										<td><b><span class="internal_code">${order.code}</span></b></td>
										<td class="title_table">Numeración:</td>
										<td class="text-uppercase"><b><span class="code">${order.internal_code ? order.internal_code : order.document_type.name}</span></b></td>
									</tr>
									<tr>
										<td class="title_table">Tipo de procedimiento:</td>
										<td><b><span class="tupa">${order.tupa ? "Tupa" : "No Tupa"}</span></b></td>
										<td class="title_table">Emisor:</td>
										<td><b><span class="name"></span> <span class="paternal_surname">${entity.name} ${entity.paternal_surname} ${entity.maternal_surname}</span> <span class="maternal_surname"></span></b></td>
									</tr>
								</table>
								<table class="table_detail">
									<tr>
										<td class="title_table">Fecha:</td>
										<td><b class="date">${moment(order.date, 'YYYY-MM-DD h:mm:ss').format('DD/MM/YYYY')}</b></td>
										<td class="title_table">Nº de documento:</td>
										<td><b><span class="nro_document">${order.number || "S/N"}</span></b></td>
									</tr>
									<tr>
										<td class="title_table">Folios:</td>
										<td>
											<b><span class="folios">${order.folios}</span></b>
										</td>
										<td class="title_table">Archivo adjuntado:</td>
										<td>
											${fileContent}
										</td>
									</tr>
									<tr>
										<td class="title_table">Asunto:</td>
										<td colspan="3"><b><span class="subject">${order.subject}</span></b></td>
									</tr>
									<tr>
										<td class="title_table">Tipo de atención:</td>
										<td colspan="3"><b><span class="order_type">${order.order_type ? order.order_type.name : "-"}</span></b></td>
									</tr>
									<tr>
										<td class="title_table">Observaciones:</td>
										<td colspan="3"><b><span class="notes">${order.notes ? order.notes : "-"}</span></b></td>
									</tr>
								</table>
							</div>`;


	  			// 	<div class="col-md-6">
						// 	<label class="mb-0 w-100">Expediente:</label>
						// 	<h4 class="font-bold"><b class="code">${order.code}</b></h4>
						// </div>
						// <div class="col-md-6">
						// 	<label class="mb-0 w-100">Tipo de persona:</label>
						// 	<h6 class="font-bold type_document">${entity.type_document == 1 ? "NATURAL" : "JURÍDICA"}</h6>
						// </div>
						// <div class="col-md-6">
						// 	<label class="mb-0 w-100">DNI:</label>
						// 	<h6 class="font-bold identity_document">${entity.identity_document}</h6>
						// </div>
						// <div class="col-md-6">
						// 	<label class="mb-0 w-100">Apellido Paterno:</label>
						// 	<h6 class="font-bold paternal_surname">${entity.paternal_surname}</h6>
						// </div>
						// <div class="col-md-6">
						// 	<label class="mb-0 w-100">Apellido Materno:</label>
						// 	<h6 class="font-bold maternal_surname">${entity.maternal_surname}</h6>
						// </div>
						// <div class="col-md-6">
						// 	<label class="mb-0 w-100">Nombre Completo:</label>
						// 	<h6 class="font-bold name">${entity.name}</h6>
						// </div>
						// <div class="col-md-6">
						// 	<label class="mb-0 w-100">Teléfono:</label>
						// 	<h6 class="font-bold cellphone">${entity.cellphone}</h6>
						// </div>
						// <div class="col-md-6">
						// 	<label class="mb-0 w-100">Correo:</label>
						// 	<h6 class="font-bold email">${entity.email}</h6>
						// </div>
						// <div class="col-md-6">
						// 	<label class="mb-0 w-100">Documento remitido:</label>
						// 	<h6 class="font-bold folios">${documentSent}</h6>
						// </div>
						// <div class="col-md-6">
						// 	<label class="mb-0 w-100">Asunto:</label>
						// 	<h6 class="font-bold subject">${order.subject}</h6>
						// </div>
						// <div class="col-md-6">
						// 	<label class="mb-0 w-100">Notas y/o referencias:</label>
						// 	<h6 class="font-bold notes">${order.notes}</h6>
						// </div>
						// <div class="col-md-6">
						// 	<label class="mb-0 w-100">Tipo de procedimiento:</label>
						// 	<h6 class="font-bold notes">${order.tupa ? order.tupa.title : ""}</h6>
						// </div>

	  				div.innerHTML = orderContent;
	  				const hr = document.createElement('hr');
	  				document.querySelector('#order-answered').appendChild(hr);
	  				document.querySelector('#order-answered').appendChild(div);


	  				let only_one_with_state_generate = 0;
	  				let tbodyContent = "";

			  		order.details.forEach( function(element, i) {
			  			if (element.status == 1) {
			  				only_one_with_state_generate++;

				  			if (only_one_with_state_generate > 1) {
				  				return;
				  			}
			  			}

			  			let attachedElement = "";

			  			if (element.attached_file) {
			  				attachedElement = `<a href="${element.attached_file}" target="_blank">Archivo</a>`;
			  			}

			  			let userWhoReceived = "";

			  			if (element.user) {
			  				userWhoReceived = `${element.user.entity.name} ${element.user.entity.paternal_surname}`;
			  			}
				  			
			  			tbodyContent  += `  <tr>
			  							  <th scope="row">${order.office_id_origen != 0 || element.status != 1 ? element.office_origen.name : "VIA WEB"}</th>
								          <th scope="row">${element.office ? element.office.name : "Varias oficinas"}</th>
								          <th scope="row">${element.state.name}</th>
								          <td>${moment(element.created_at, 'YYYY-MM-DD H:mm:ss').format('DD/MM/YYYY H:mm:ss')}</td>
								          <td>${element.observations || "-"}</td>
								          <td>${userWhoReceived || "-"}</td>
								          </tr>`;


			  		});


	  				const divTable = document.createElement('div');
	  				divTable.classList.add('table-responsive');
	  				divTable.classList.add('table_modal_track');
	  				// divTable.classList.add('pt-4');

	  				divTable.innerHTML = `
			  			<table id="table-route" class="table table-bordered table-striped table-sm">
					      <caption style="caption-side:top;">Ruta</caption>
					      <thead class="thead-dark">
					        <tr>
					          <th>Procedencia</th>
					          <th>Destino</th>
					          <th>Estado</th>
					          <th>Fecha</th>
					          <th>Observación</th>
					          <th>Usuario que recibió</th>
					        </tr>
					      </thead>
					      <tbody>
					        ${tbodyContent}
					      </tbody>
					    </table>
	  				`;

	  				document.querySelector('#order-answered').appendChild(divTable);
	  			});

	  		}

	  		while (orderMultipleDiv.firstElementChild) {
	  			orderMultipleDiv.removeChild(orderMultipleDiv.firstElementChild);
	  		}

	  		if (orders_from_multiple) {

				orders_from_multiple.forEach( function(element, index) {
		  			const table = document.createElement('table');

		  			const {order} = element;

		  			// let attachedElement = "";

		  			// if (element.attached_file) {
		  			// 	attachedElement = `<a href="${element.attached_file}" target="_blank">Archivo</a>`;
		  			// }
		  			let content = "";

			  		order.details.forEach( function(element, index) {

			  			content  += `  <tr><th scope="row">${element.office_origen ? element.office_origen.name : "SISTEMA WEB"}</th>
								          <th scope="row">${element.office ? element.office.name : "Sistema web"}</th>
								          <th scope="row">${element.state.name}</th>
								          <td>${moment(element.created_at, 'YYYY-MM-DD H:mm:ss').format('DD/MM/YYYY H:mm:ss')}</td>
								          <td>${element.observations}</td>
								          <td>${element.user.entity.name} ${element.user.entity.paternal_surname}</td>
								        </tr>`;
			  		});

		  			table.innerHTML = `<table>
									      <caption style="caption-side:top;">Dirigido a: ${order.details[0].office.name} - ${order.code}</caption>
									      <thead>
									        <tr>
									          <th scope="col">Procedencia</th>
									          <th scope="col">Destino</th>
									          <th scope="col">Estado</th>
									          <th scope="col">Fecha</th>
									          <th scope="col">Observación</th>
									          <th scope="col">Usuario que recibió</th>
									        </tr>
									      </thead>
									      <tbody>
									       ${content}
									      </tbody>
									    </table>`;

					orderMultipleDiv.appendChild(table);

		  		});


	  		}


	  		$('#modal-track').modal('show');
		}).catch((error) => {
	  		console.error(error);
		}).finally(() => {
	  // TODO
		});

});

const extornoRouteTbody = document.querySelector('#table-extorno-route tbody');

$(`.solicitude__extorno`).on('click', function(E){
	E.preventDefault();
	let _that = $(this), _order_id = _that[0].dataset.index;

	axios.get(`/admin/expediente/${_order_id}/details-extorno`)
		.then((response) => {

			const order = response.data.order;
	  		const {details} = order;

	  		document.querySelector('#modal-extorno .modal-title').innerHTML = `<b>Expediente ${order.code}</b>`;

	  		while (extornoRouteTbody.firstElementChild) {
	  			extornoRouteTbody.removeChild(extornoRouteTbody.firstElementChild);
	  		}

	  		//let only_one_with_state_generate = 0;
	  // 		canExtort = true;
			// if (order.status == 7) {
	  // 			//respondido
	  // 			canExtort = false;
	  // 			orderTypeExtornoDiv.classList.add('text-danger');
	  // 			orderTypeExtornoDiv.innerHTML = `No se puede deshacer estados ni eliminar este trámite, por que ya existe una respuesta.`;
	  // 		}

			// if (order.status == 1) {
	  // 			//respondido
	  // 			canExtort = false;
	  // 			orderTypeExtornoDiv.classList.add('text-danger');
	  // 			orderTypeExtornoDiv.innerHTML = `No se puede deshacer estados ni eliminar este trámite, por que ya existe una respuesta.`;
	  // 		}
	  		const detailsLength = details.length;

	  		details.forEach( function(element, index) {
	  			let statusName = element.state.name;
	  			let officeOrigenName = element.office_origen.name;

	  			if (element.status == 1 && index == 0 && order.office_id_origen == 0) {
	  				officeOrigenName = "VIA WEB";
	  			}

	  			if (element.status == 1 && index != 0) {
	  				statusName = "DERIVADO";
	  			}

	  			if (element.status == 2) {
	  				//only_one_with_state_generate++;

		  			//if (only_one_with_state_generate > 1) {
		  				return;
		  			//}
	  			}

	  			const tr = document.createElement('tr');

	  			let attachedElement = "";

	  			if (element.attached_file) {
	  				attachedElement = `<a href="${element.attached_file}" target="_blank">Archivo</a>`;
	  			}

	  			let userWhoReceived = "";

	  			if (element.user) {
	  				userWhoReceived = `${element.user.entity.name} ${element.user.entity.paternal_surname}`;
	  			}

	  			let btnAction = "";

	  			if (document.querySelector('#is_admin').value == 1) {
	  				btnAction = `<button data-index="${element.id}" onclick="changeStatus(this)">Extorno</button>`;
	  			}
	  			if (element.last == 1 || index < detailsLength - 2) {
	  				btnAction = "";
	  			}

	  			// if (!canExtort) {
	  			// 	btnAction = "";
	  			// }
	  			
	  			tr.innerHTML = `  <th scope="row">${officeOrigenName}</th>
						          <th scope="row">${element.office ? element.office.name : "Varias oficinas"}</th>
						          <th scope="row">${statusName}</th>
						          <td>${moment(element.created_at, 'YYYY-MM-DD H:mm:ss').format('DD/MM/YYYY H:mm:ss')}</td>
						          <td>${userWhoReceived}</td>
						          <td>${element.observations || "-"}</td>
						          <td>${btnAction}</td>`;

				extornoRouteTbody.appendChild(tr);

	  		});

	  		$('#modal-extorno').modal('show');
		}).catch((error) => {
	  		console.error(error);
		}).finally(() => {
	  // TODO
		});

});

function changeStatus(a){

	const orderId = a.dataset.i;
	const status = a.dataset.index;
	const actionText = a.dataset.action_text;
	
	Swal.fire({
	  title: actionText,
	  showDenyButton: true,
	  showCancelButton: true,
	  confirmButtonText: 'Guardar',
	  denyButtonText: `Cancelar`,
	}).then((result) => {
	  /* Read more about isConfirmed, isDenied below */
	  if (result.value) {
	  	lockWindow();
			fetch(`/admin/order/${orderId}/status`, {
					   method: 'PUT',
						headers: {
							'Accept': 'application/json',
							'Content-Type': 'application/json'
						},
					   body: JSON.stringify({
						    "status": status,
					   })
					})
					.then(function(response) {
					   if(response.ok) {
								return response.json();
					   }
		   	 			return Promise.reject(response);

					})
					.then((data) => {
						unlockWindow();
                    	notice(`${data.title}`, `${data.message}`, `success`);
						location.reload();
					})
					.catch((err) => {
						unlockWindow();
			            err.json().then((json) => {
			              notice(json.title, json.message, 'warning');
			            });
					});
				return;
	  }
	})

}

// function changeStatus(btn){

// 	Swal.fire({
// 		  title: '¿Está seguro?',
// 		  text: "Va a cambiar el estado del documento",
// 		  icon: 'warning',
// 		  showCancelButton: true,
// 		  confirmButtonColor: '#3085d6',
// 		  cancelButtonColor: '#d33',
// 		  confirmButtonText: 'Sí!',
// 		  cancelButtonText: 'No!'
// 		}).then((result) => {
// 			console.log(result);
// 		  if (result.value) {
// 		  	//$(`#update-status-form`).submit();

// 		  	const route = `/admin/solicitude-extorno/${btn.dataset.index}`;

// 		  	//const formData = new FormData(document.querySelector('#update-status-form'));

// 		  	//formData.append('offices_arr', $('#modal-delete- select[name="offices"]').val());

// 		  	lockWindow();
//             $.ajaxSetup({
//                 headers: {
//                     'X-CSRF-TOKEN': $('input[name=_token]').val()
//                 }
//             });
//             $.ajax({
//                 url : route,
//                 type: 'POST',
//                 data: [],
//                 contentType: false,
//                 processData: false,
//                 success: function(e){
//                     unlockWindow();
//                     notice(`${e.title}`, `${e.message}`, `success`);

//                     setTimeout(function(){
//                     	location.reload();
//                     	//location.replace('/admin/reportes-codigo');
//                      }, 1000);

//                 },
//                 error:function(jqXHR, textStatus, errorThrown)
//                 {
//                     notice(jqXHR.responseJSON.title, jqXHR.responseJSON.message, `warning`);
//                     unlockWindow();
//                 }
//             });

// 		    // Swal.fire(
// 		    //   'Deleted!',
// 		    //   'Your file has been deleted.',
// 		    //   'success'
// 		    // )
// 		  }
// 		})

// }

function deleteRecord(btn){

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
		  	//$(`#update-status-form`).submit();

		  	const route = `/admin/solicitude/${btn.dataset.index}`;

		  	//const formData = new FormData(document.querySelector('#update-status-form'));

		  	//formData.append('offices_arr', $('#modal-delete- select[name="offices"]').val());

		  	lockWindow();
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('input[name=_token]').val()
                }
            });
            $.ajax({
                url : route,
                type: 'DELETE',
                data: [],
                contentType: false,
                processData: false,
                success: function(e){
                    unlockWindow();
                    notice(`${e.title}`, `${e.message}`, `success`);

                    setTimeout(function(){
                    	location.replace('/admin/reportes-codigo');
                     }, 1000);

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
}

document.querySelector('#filter_1 select[name="status"]')
	.addEventListener('change', (e) => {
		location.replace(`/admin/estudiantes-registrados?searchText=${document.querySelector('#filter_1 input[name="searchText"]').value}&status=${e.target.value}`);
	})