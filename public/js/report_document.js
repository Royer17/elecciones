document.querySelector('#solicitudes_report')
	.addEventListener('click', () => {
		window.open(`/admin/reporte-por-documento?oficina=${document.querySelector('#report_office').value}&documento=${document.querySelector('#report_document').value}`);
	});