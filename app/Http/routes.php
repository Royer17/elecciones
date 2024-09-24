<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
 */

Route::post('/login', 'Auth\AuthController@authenticate');
Route::get('/login', 'Auth\AuthController@get_view');
Route::get('/logout', 'Auth\AuthController@logout')->name('logout');

Route::group(['namespace' => 'Landing'], function () {
	//Route::get('/', 'HomeController@index');
	//Route::get('/', 'HomeController@index');
	//Route::get('/testingqr', 'HomeController@qr_view');

	Route::get('/productos', 'HomeController@view_products');
	Route::get('/producto/{slug}', 'HomeController@view_product_profile');

	Route::get('/detalles-documento', function () {
		return redirect('/busqueda-documento');
	});

	Route::get('/email-view', 'HomeController@email_view');

	Route::post('/detalles-documento', 'OrderController@details_document_view');
	Route::get('/nosotros', 'HomeController@view_about_us');
	Route::post('/search', 'OrderController@search');
	Route::get('/admin/search-student', 'OrderController@search_student');
	Route::get('/admin/search-student-by-identity-document', 'OrderController@search_student_by_identity_document');

	Route::get('/admin/search-all-students', 'OrderController@search_all_students');
	Route::get('/admin/parent/{dni}/search', 'OrderController@search_parent');

	Route::get('/busqueda-documento', 'HomeController@view_order');
	Route::post('/solicitud-enviada', 'OrderController@request_completed');
	Route::post('/solicitud-enviada-email', 'OrderController@request_completed_email');
	Route::get('/solicitud-enviada', 'OrderController@get_request_completed');
	Route::post('/constancia', 'OrderController@request_constancia');

	Route::get('/requisitos-formatos', 'HomeController@view_requirements');

	Route::get('/resumen-de-la-orden/{id}', 'OrderController@view_order');
	Route::post('/order', 'OrderController@store');
	Route::put('/admin/order/{id}/status', 'OrderController@update_status');
	Route::post('/logged-solicitude', 'OrderController@store_logged_solicitude');
	Route::post('/admin/payment', 'OrderController@store_payment');

	Route::post('/admin/students/import', 'OrderController@import_students');

	Route::put('/logged-solicitude/{id}', 'OrderController@update_logged_solicitude');

	Route::post('/answer-solicitude', 'OrderController@store_response_solicitude');

	Route::put('/order-confirm/{id}', 'OrderController@confirm');


	Route::get('/cart-detail', 'ProductController@cart_detail');
	Route::get('/cart-total', 'ProductController@cart_total');
	Route::get('/cart-summary', 'ProductController@cart_summary');
	Route::get('/document-state/{id}', 'HomeController@get_document_state');

	Route::get('/product/search', 'ProductController@search');

	Route::get('/fix-product', 'FixerController@fix_product');
	Route::get('/fix-category', 'FixerController@fix_category');
	Route::get('/products/paginated', 'ProductController@all_paginated');
	Route::get('/fixer/delete-old-records', 'FixerController@delete_old_records');

	Route::get('/entity/{document}/detail', 'HomeController@detail_entity');
});

Route::get('/', function () {
	return view('auth/login');
});

Route::get('/acerca', function () {
	return view('acerca');
});

Route::resource('almacen/categoria', 'CategoriaController');
Route::resource('/admin/profesiones', 'ProfessionController');
Route::resource('/admin/oficinas', 'OfficeController');
Route::delete('/admin/office/{id}', 'OfficeController@delete');
Route::put('/admin/office/order', 'OfficeController@update_order');
Route::resource('/admin/payment-concepts', 'OfficeController@datatable');
Route::resource('/admin/tipos-de-documento', 'DocumentTypeController');
Route::get('/admin/document-type-code', 'DocumentTypeController@get_document_type_code');
Route::post('/admin/document-type/office', 'OfficeController@update_document_type_office');


Route::resource('/admin/tupa', 'TupaController');
Route::resource('/admin/tipo-de-atencion', 'OrderTypeController');
Route::resource('/admin/feriados', 'FeriadosController');

Route::post('/admin/tupa-requirement', 'TupaRequirementController@store');
Route::put('/admin/tupa-requirement/{id}', 'TupaRequirementController@update');
Route::delete('/admin/tupa-requirement/{id}', 'TupaRequirementController@destroy');

Route::put('/admin/user/{id}/password', 'UsuarioController@update_password');


Route::resource('/admin/solicitudes', 'SolicitudeController');
Route::get('/admin/nuevas-solicitudes', 'SolicitudeController@get_view_new_solicitudes');
Route::get('/admin/solicitudes-report', 'SolicitudeController@solicitude_report');
Route::get('/admin/reporte-por-codigo', 'SolicitudeController@report_by_code');
Route::get('/admin/reporte-por-documento', 'SolicitudeController@report_by_document');


Route::get('/admin/reporte-por-documento', 'SolicitudeController@report_by_document');


Route::get('/admin/reporte', 'SolicitudeController@simple_report');

Route::get('/admin/my-solicitudes-report-pdf', 'SolicitudeController@my_solicitude_report_pdf');
Route::get('/admin/lista-de-fichas-excel', 'SolicitudeController@payment_list_excel');
Route::get('/admin/estudiantes-registrados-excel', 'SolicitudeController@students_registered_pdf');
Route::get('/admin/solicitudes-report-pdf', 'SolicitudeController@solicitude_report_pdf');
Route::get('/admin/solicitudes-report-code-pdf', 'SolicitudeController@solicitude_report_code_pdf');
Route::get('/admin/solicitudes-report-fecha-pdf', 'SolicitudeController@solicitude_report_fecha_pdf');

Route::get('/admin/solicitudes-report-pdf-enviado', 'SolicitudeController@solicitude_report_pdf_sent');
Route::post('/admin/solicitude-extorno/{id}', 'SolicitudeController@update_status_extorno');


Route::delete('/admin/solicitude/{id}', 'SolicitudeController@delete_solicitude');
Route::put('/admin/solicitude/{id}', 'SolicitudeController@update');

Route::resource('/admin/lista-de-fichas', 'SolicitudeController@my_solicitude_sent_view');
Route::get('/admin/estudiantes-registrados', 'SolicitudeController@students_registered_view');
Route::get('/admin/crear-solicitud', 'SolicitudeController@create_solicitude');
Route::get('/admin/registrar-estudiante', 'SolicitudeController@register_student_view');
Route::get('/admin/ficha-de-matricula', 'SolicitudeController@enrollment_data_view');

Route::get('/admin/editar-registro-de-estudiante/{id}', 'SolicitudeController@edit_student_record_view');
Route::get('/admin/reporte-de-pagos/{id}', 'SolicitudeController@report_payments');
Route::get('/admin/reporte-de-pago/{id}', 'SolicitudeController@report_payments_done');


Route::get('/admin/notificaciones', 'HomeController@notifications');

Route::get('/admin/editar-solicitud-interna/{id}', 'SolicitudeController@edit_solicitude_view');

Route::delete('/admin/solicitude/{id}', 'SolicitudeController@delete_solicitude');

Route::put('/admin/user/{id}/suspend', 'UsuarioController@suspend');
Route::put('/admin/user/{id}/active', 'UsuarioController@active');


Route::get('/admin/registrados', 'SolicitudeController@get_view_registrated');
Route::get('/admin/recibidos', 'SolicitudeController@get_view_received');
Route::get('/admin/derivados', 'SolicitudeController@get_view_derivated');
Route::get('/admin/finalizados', 'SolicitudeController@get_view_finished');
Route::get('/admin/de-conocimiento', 'SolicitudeController@get_view_cc');
Route::get('/admin/enviados', 'SolicitudeController@get_view_sent');

Route::get('/admin/reportes', 'SolicitudeController@get_view_report');
Route::get('/admin/reportes-codigo', 'SolicitudeController@get_view_report_code');
Route::get('/admin/reports-debt-datatable', 'SolicitudeController@report_debt_datatable');
Route::get('/admin/reports-debt-datatable-excel', 'SolicitudeController@report_debt_datatable_excel');

Route::get('/admin/reportes-documento', 'SolicitudeController@get_view_report_document');

Route::get('/admin/expediente/{id}/details', 'SolicitudeController@get_detail');
Route::get('/admin/expediente/{id}/details-extorno', 'SolicitudeController@get_detail_extorno');

// Route::post('/admin/ruta-de-solicitud', 'SolicitudeController@report');
Route::post('/admin/ruta-de-solicitud', 'SolicitudeController@details_document_view');

// Route::post('/admin/detalles-documento', 'SolicitudeController@details_document_view');

Route::resource('/admin/personal', 'EntityController');
Route::post('/admin/solicitude-status', 'SolicitudeController@update_status');
Route::post('/admin/solicitude-answer-cc', 'SolicitudeController@answer_cc');

Route::resource('almacen/articulo', 'ArticuloController');
Route::resource('ventas/cliente', 'ClienteController');
Route::resource('compras/proveedor', 'ProveedorController');
Route::resource('compras/ingreso', 'IngresoController');
Route::resource('ventas/venta', 'VentaController');
Route::resource('seguridad/usuario', 'UsuarioController');

Route::post('/products-import', 'ArticuloController@import');

//Route::auth();

// Route::get('/home', 'HomeController@index');
Route::get('/empresa', 'HomeController@showCompany')->name('company.show');
Route::post('/empresa', 'HomeController@updateCompany')->name('company.update');

// //Excel
// Route::get('excelventas', 'VentaController@reporteExcel');
// Route::get('excelingresos', 'IngresoController@reporteExcel');

// //Reportes
// Route::get('reportecategorias', 'CategoriaController@reporte');
// Route::get('reportearticulos', 'ArticuloController@reporte');
// Route::get('reporteclientes', 'ClienteController@reporte');
// Route::get('reporteproveedores', 'ProveedorController@reporte');
// Route::get('reporteventas', 'VentaController@reporte');
// Route::get('reporteventa/{id}', 'VentaController@reportec');
// Route::get('reporteingresos', 'IngresoController@reporte');
// Route::get('reporteingreso/{id}', 'IngresoController@reportec');
// Route::get('/{slug?}', 'HomeController@index');

Route::get('email-example', function(){
	return view('emails.order');
});

