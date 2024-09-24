document.querySelector('#solicitudes_report')
	.addEventListener('click', () => {
		window.open(`/admin/reports-debt-datatable-excel?year=${document.querySelector('select[name="year"]').value}&nivel=${document.querySelector('select[name="order_type_id"]').value}&grade=${document.querySelector('select[name="tupa_id"]').value}&section=${document.querySelector('select[name="subject"]').value}`);
	});

	$reportDatatable = $('#report-datatable').DataTable({
		searchDelay: 900,
		dom: '<"top"fl>rt<"bottom"ip><"clear">',
		processing: true,
		serverSide: true,
		bProcessing: true,
		destroy: true,
		bFilter: true,
		ajax: `/admin/reports-debt-datatable?year=${document.querySelector('select[name="year"]').value}&nivel=${document.querySelector('select[name="order_type_id"]').value}&grade=${document.querySelector('select[name="tupa_id"]').value}&section=${document.querySelector('select[name="subject"]').value}`,
		initComplete: function() {
			//Width of the search box
			$('.dataTables_filter input[type="search"]').css({
				'width': '400',
				'display': 'inline-block'
			});

			//position of div length
			$('.dataTables_length').css({
				'float': 'right'
			});
			//position of div filter
			$('.dataTables_filter').css({
				'float': 'left'
			});
		},
		columns: [{
			//0
			data: 'id',
			name: 'orders.id',
			searchable: false
		}, {
			//1
			data: 'year',
			name: 'orders.year',
			searchable: true
		}, {
			//2
			data: 'code',
			name: 'orders.code',
			searchable: true
		},  {
			//3
			data: 'created_at',
			name: 'orders.created_at',
			searchable: true
		}, {
			//4
			data: 'identity_document',
			name: 'entities.identity_document',
			searchable: true
		}, {
			//5
			data: 'name',
			name: 'entities.name',
			searchable: true
		}, {
			//6
			data: 'paternal_surname',
			name: 'entities.paternal_surname',
			searchable: true
		}, {
			//7
			data: 'maternal_surname',
			name: 'entities.maternal_surname',
			searchable: true
		}, {
			//8
			data: 'order_type_id',
			name: 'orders.order_type_id',
			searchable: true
		}, {
			//9
			data: 'tupa_id',
			name: 'orders.tupa_id',
			searchable: true
		}, {
			//10
			data: 'subject',
			name: 'orders.subject',
			searchable: true
		}, {
			//11
			data: 'payed',
			name: 'payed',
			searchable: false
		},  {
			//12
			data: 'debt',
			name: 'debt',
			searchable: false
		}],
		order: [
			[0, 'desc']
		],
		language: {
			"sProcessing": "Procesando...",
			"sLengthMenu": "Mostrar _MENU_ registros",
			"sZeroRecords": "No se encontraron resultados",
			"sEmptyTable": "No se encontraron resultados.",
			"sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
			"sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
			"sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
			"sInfoPostFix": "",
			"sSearch": "<label style='color: var(--main-bg-color-primario); font-weight:bold;'>Buscar:</label>",
			"searchPlaceholder": "Buscar por nombre, apellido o documento",
			"sUrl": "",
			"sInfoThousands": ",",
			"sLoadingRecords": "Cargando...",
			"oPaginate": {
				"sFirst": "Primero",
				"sLast": "Último",
				"sNext": "Siguiente",
				"sPrevious": "Anterior"
			},
			"oAria": {
				"sSortAscending": ": Activar para ordenar la columna de manera ascendente",
				"sSortDescending": ": Activar para ordenar la columna de manera descendente"
			}
		},
		"aoColumnDefs": [{
			"bVisible": false,
			"aTargets": [0, 2, 6, 7, 10],
		}, {
              "aTargets": [ 3 ],
              "mData": "created_at",
              "mRender": function ( data, type, full ) {
              	return moment(full['created_at'], 'YYYY-MM-DD H:mm').format('DD/MM/YYYY H:mm');
              }
        }, {
              "aTargets": [ 5 ],
              "mData": "name",
              "mRender": function ( data, type, full ) {
              	return `${full['name']} ${full['paternal_surname']} ${full['maternal_surname']}`;
              }
        }, {
              "aTargets": [ 8 ],
              "mData": "order_type_id",
              "mRender": function ( data, type, full ) {

              	if (full['order_type_id'] == 1) {
              		return `PRIMARIA`;
              	}

              	return `SECUNDARIA`;
              }
        }, {
              "aTargets": [ 9 ],
              "mData": "tupa_id",
              "mRender": function ( data, type, full ) {
              	console.log(full['tupa_id']);
              	switch (parseInt(full['tupa_id'])) {
				  case 1:
				  	return `<b>Primero ${full['subject']}</b>`;
				    break;
				  case 2:
				  	return `<b>Segundo ${full['subject']}</b>`;
				  	break;
				  case 3:
				  	return `<b>Tercero ${full['subject']}</b>`;
				    break;
				   case 4:
				  	return `<b>Cuarto ${full['subject']}</b>`;
				    break;
				   case 5:
				  	return `<b>Quinto ${full['subject']}</b>`;
				    break;
				   case 6:
				  	return `<b>Sexto ${full['subject']}</b>`;
				    break;
				}
	

             }
        },
         {
              "aTargets": [ 11 ],
              "mData": "payed",
              "mRender": function ( data, type, full ) {
              	return `<span class="text-success"><b>${full['payed']}</b></span>`;
              }
        }, {
              "aTargets": [ 12 ],
              "mData": "debt",
              "mRender": function ( data, type, full ) {
              	return `<span class="text-danger"><b>${full['debt']}</b></span>`;
              }
        }
		]
	});

document.querySelector('select[name="year"]')
	.addEventListener('change', () => {
		reloadDatatable();
	});

document.querySelector('select[name="order_type_id"]')
	.addEventListener('change', () => {
		reloadDatatable();
	});

document.querySelector('select[name="tupa_id"]')
	.addEventListener('change', () => {
		reloadDatatable();
	});

document.querySelector('select[name="subject"]')
	.addEventListener('change', () => {
		reloadDatatable();
	});


function reloadDatatable()
{
	$reportDatatable.ajax.url(`/admin/reports-debt-datatable?year=${document.querySelector('select[name="year"]').value}&nivel=${document.querySelector('select[name="order_type_id"]').value}&grade=${document.querySelector('select[name="tupa_id"]').value}&section=${document.querySelector('select[name="subject"]').value}`).load();
}


document.querySelector('select[name="order_type_id"]')
    .addEventListener('change', (e) => {

        document.querySelector('select[name="tupa_id"]').innerHTML = `<option value="">Seleccione</option>`;
        document.querySelector('select[name="subject"]').innerHTML = `<option value="">Seleccione</option>`;

        if (e.target.value == 1) {
            document.querySelector('select[name="tupa_id"]').innerHTML = `<option value="">Seleccione</option>
                                                                                                    <option value="1">PRIMERO</option>
                                                                                                    <option value="2">SEGUNDO</option>
                                                                                                    <option value="3">TERCERO</option>
                                                                                                    <option value="4">CUARTO</option>
                                                                                                    <option value="5">QUINTO</option>
                                                                                                    <option value="6">SEXTO</option>`;

            document.querySelector('select[name="subject"]').innerHTML = `<option value="">Seleccione</option>
                                                                                                    <option value="A">A</option>
                                                                                                    <option value="B">B</option>
                                                                                                    <option value="C">C</option>`;


        } else {
            document.querySelector('select[name="tupa_id"]').innerHTML = `<option value="">Seleccione</option>
                                                                                                    <option value="1">PRIMERO</option>
                                                                                                    <option value="2">SEGUNDO</option>
                                                                                                    <option value="3">TERCERO</option>
                                                                                                    <option value="4">CUARTO</option>
                                                                                                    <option value="5">QUINTO</option>`;

            document.querySelector('select[name="subject"]').innerHTML = `<option value="">Seleccione</option>
                                                                                                    <option value="A">A</option>
                                                                                                    <option value="B">B</option>
                                                                                                    <option value="C">C</option>
                                                                                                    <option value="D">D</option>`;
        }

        $('select[name="tupa_id"]').change();
        $('select[name="subject"]').change();

    });