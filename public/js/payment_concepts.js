const paymentConceptsDiv = document.querySelector('#payment-concepts-list');

reloadTableConcepts();

function conceptDelete(btn)
{
	let _that = $(btn), paymentConceptId = _that[0].dataset.index;

	Swal.fire({
	  title: '¿Está seguro?',
	  text: "Va a eliminar el concepto",
	  icon: 'warning',
	  showCancelButton: true,
	  confirmButtonColor: '#3085d6',
	  cancelButtonColor: '#d33',
	  confirmButtonText: 'Sí!',
	  cancelButtonText: 'No!'
	}).then((result) => {
	  if (result.value) {
	  	lockWindow();
	  	axios.delete(`/admin/office/${paymentConceptId}`)
	  		.then((response) => {
	  			unlockWindow();
	  			Swal.fire(response.data.title, response.data.message, 'success');
					$reportDatatable.ajax.url(`/admin/payment-concepts?year=${document.querySelector('select[name="year"]').value}`).load();
	  		})
	  		.catch((err) => {
	  			Swal.fire(err.response.data.title, err.response.data.message, 'warning');
	  			unlockWindow();
	  		})


	  }
	});

}	


function reloadTableConcepts() {
  cleanAllChildren(paymentConceptsDiv);

  axios.get(`/admin/payment-concepts?year=${document.querySelector('select[name="year"]').value}`)
    .then((response) => {

      response.data.forEach((element, index) => {
        const tr = document.createElement('tr');
        tr.classList.add('item-one');
        tr.dataset.index = element.id;
        tr.dataset.order = element.order;


        let year = "No definido";

        if (element.year != 0) {
        	year = element.year;
        }

        if (element.is_for_all_years == true) {
        	year = "Todos";
        }

        tr.innerHTML = `
                        <td>${element.name}</td>
                        <td><b>S/.${parseFloat(element.sigla).toFixed(2)}</b></td>
                        <td>
                          <label class="mb-1" for="">${year}</label>
                        </td>
                        <td>
                          <a href="/admin/oficinas/${element.id}/edit" class="text-info px-1" title="Editar" target="_blank"><i class="fas fa-pencil-alt"></i></a>
                          <a href="javascript:void(0);" data-index="${element.id}" class="btn btn-danger py-0 px-1" title="Eliminar" onclick="conceptDelete(this);"><i class="fa fa-trash notPointerEvent"></i></a>
                        </td>
                      `;

        paymentConceptsDiv.appendChild(tr);
      });
    })
    .then(() => {
      setTimeout(() => {

        $('#payment-concepts-list').sortable();
      }, 400);
    });
}


$('#payment-concepts-list').sortable().bind('sortupdate', function(e, ui) {

  lockWindow();
  let order = [];

  document.querySelectorAll('#payment-concepts-list .item-one').forEach((element) => {
    order = [...order, element.dataset.index];
  });

  axios.put(`/admin/office/order`, {
      order,
    })
    .then((response) => {
      Swal.fire(response.data.title, response.data.message, `success`);
    })
    .catch((error) => {
      Swal.fire(`Error`, error.response.data.message, 'warning');
    })
    .finally(() => {
      unlockWindow();
    });
});

// $(`.payment-concept__delete`).on('click', function(E){
// 	E.preventDefault();

// });
	// $reportDatatable = $('#payment-concepts-datatable').DataTable({
	// 	searchDelay: 900,
	// 	dom: '<"top"fl>rt<"bottom"ip><"clear">',
	// 	processing: true,
	// 	serverSide: true,
	// 	bProcessing: true,
	// 	destroy: true,
	// 	bFilter: true,
	// 	ajax: `/admin/payment-concepts?year=${document.querySelector('select[name="year"]').value}`,
	// 	initComplete: function() {
	// 		//Width of the search box
	// 		$('.dataTables_filter input[type="search"]').css({
	// 			'width': '400',
	// 			'display': 'inline-block'
	// 		});

	// 		//position of div length
	// 		$('.dataTables_length').css({
	// 			'float': 'right'
	// 		});
	// 		//position of div filter
	// 		$('.dataTables_filter').css({
	// 			'float': 'left'
	// 		});
	// 	},
	// 	columns: [{
	// 		//0
	// 		data: 'id',
	// 		name: 'offices.id',
	// 		searchable: false
	// 	}, {
	// 		//1
	// 		data: 'code',
	// 		name: 'offices.code',
	// 		searchable: true
	// 	}, {
	// 		//2
	// 		data: 'name',
	// 		name: 'offices.name',
	// 		searchable: true
	// 	},  {
	// 		//3
	// 		data: 'created_at',
	// 		name: 'offices.created_at',
	// 		searchable: true
	// 	}, {
	// 		//4
	// 		data: 'sigla',
	// 		name: 'offices.sigla',
	// 		searchable: true
	// 	}, {
	// 		//5
	// 		data: 'upper_office_id',
	// 		name: 'offices.upper_office_id',
	// 		searchable: true
	// 	}, {
	// 		//6
	// 		data: 'year',
	// 		name: 'offices.year',
	// 		searchable: true
	// 	}, {
	// 		//7
	// 		data: 'is_for_all_years',
	// 		name: 'offices.is_for_all_years',
	// 		searchable: true
	// 	},{
	// 		//8
	// 		data: 'Actions',
	// 		name: 'Actions',
	// 		searchable: false
	// 	}],
	// 	order: [
	// 		[0, 'desc']
	// 	],
	// 	language: {
	// 		"sProcessing": "Procesando...",
	// 		"sLengthMenu": "Mostrar _MENU_ registros",
	// 		"sZeroRecords": "No se encontraron resultados",
	// 		"sEmptyTable": "No se encontraron resultados.",
	// 		"sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
	// 		"sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
	// 		"sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
	// 		"sInfoPostFix": "",
	// 		"sSearch": "<label style='color: var(--main-bg-color-primario); font-weight:bold;'>Buscar:</label>",
	// 		"searchPlaceholder": "Buscar por nombre",
	// 		"sUrl": "",
	// 		"sInfoThousands": ",",
	// 		"sLoadingRecords": "Cargando...",
	// 		"oPaginate": {
	// 			"sFirst": "Primero",
	// 			"sLast": "Último",
	// 			"sNext": "Siguiente",
	// 			"sPrevious": "Anterior"
	// 		},
	// 		"oAria": {
	// 			"sSortAscending": ": Activar para ordenar la columna de manera ascendente",
	// 			"sSortDescending": ": Activar para ordenar la columna de manera descendente"
	// 		}
	// 	},
	// 	"aoColumnDefs": [{
	// 		"bVisible": false,
	// 		"aTargets": [0, 1, 3, 7],
	// 	}, {
  //             "aTargets": [ 2 ],
  //             "mData": "name",
  //             "mRender": function ( data, type, full ) {
  //             	return `<b>${full['name']}</b>`;

  //             }
  //       }, {
  //             "aTargets": [ 4 ],
  //             "mData": "sigla",
  //             "mRender": function ( data, type, full ) {
  //             	return `S/.${parseFloat(full['sigla']).toFixed(2)}`;

  //             }
  //       },  {
  //             "aTargets": [ 6 ],
  //             "mData": "year",
  //             "mRender": function ( data, type, full ) {

  //             	if (full['is_for_all_years'] == true) {
  //             		return "<b>Todos</b>";
  //             	}

  //             	return `<b>${full['year']}</b>`;


  //             }
  //       },
  //       {
  //             "aTargets": [ 8 ],
  //             "mData": "Actions",
  //             "mRender": function ( data, type, full ) {

  //             	return `<a href="/admin/oficinas/${full['id']}/edit" class="btn py-0 px-1" target="_blank" title="Editar" style="background-color: #87CEEB; border-color: #87CEEB;"><i class="fas fa-pencil-alt"></i></a>
  //             	<a href="javascript:void(0);" data-index="${full['id']}" class="btn btn-danger py-0 px-1" title="Eliminar" onclick="conceptDelete(this);"><i class="fa fa-trash notPointerEvent"></i></a>`;

  //             }
  //       }
	// 	]
	// });

document.querySelector('select[name="year"]')
	.addEventListener('change', () => {
		reloadTableConcepts();
	});