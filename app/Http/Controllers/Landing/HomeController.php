<?php

namespace sisVentas\Http\Controllers\Landing;

use DB;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use sisVentas\Articulo;
use sisVentas\Candidate;
use sisVentas\Categoria;
use sisVentas\Company;
use sisVentas\DocumentState;
use sisVentas\DocumentType;
use sisVentas\Entity;
use sisVentas\Order;
use sisVentas\Tupa;
use sisVentas\Vote;

class HomeController extends Controller {

	public function view_votacion()
	{
		
	}

	public function index() {
		/*$categories = Categoria::with(['products_actived' => function ($query) {
			 		$query->select(['idarticulo as id', 'nombre as name', 'slug', 'price', 'idcategoria', 'imagen']);
			 	}])
			 	->whereCondicion(1)
			 	->select(['idcategoria', 'nombre as name', 'slug'])
			 	->get();

		*/

		// $categories_outstanding = Categoria::whereCondicion(1)
		// 	->whereOutstanding(1)
		// 	->select(['idcategoria', 'nombre as name', 'slug'])
		// 	->get();

		// $categories = Categoria::whereCondicion(1)
		// 	->whereOutstanding(0)
		// 	->select(['idcategoria', 'nombre as name', 'slug'])
		// 	->get();

		// $products = Articulo::whereEstado('Activo')
		// 	->paginate(10);

		// $last_products = Articulo::whereEstado('Activo')
		// 	->orderBy('idarticulo', 'DESC')
		// 	->take(4)
		// 	->get();

		//$carousel_quantity = ceil(count($last_products) / 2);

		$company = Company::first();

		##NEW---
		$document_types = DocumentType::where('is_multiple', 0)
			->get();

		$search_button = true;
		
		$tupa = DB::table('tupa')
			->where('deleted_at', NULL)
			->get();

		return view('store.products.index', compact('company', 'document_types', 'search_button', 'tupa'));
	}

	public function view_products() {

		$categories_outstanding = Categoria::whereCondicion(1)
			->whereOutstanding(1)
			->select(['idcategoria', 'nombre as name', 'slug'])
			->get();

		$categories = Categoria::whereCondicion(1)
			->whereOutstanding(0)
			->select(['idcategoria', 'nombre as name', 'slug'])
			->get();

		$products = Articulo::whereEstado('Activo')
			->paginate(10);

		$last_products = Articulo::whereEstado('Activo')
			->orderBy('idarticulo', 'DESC')
			->take(4)
			->get();
		$carousel_quantity = ceil(count($last_products) / 2);

		return view('store.products.index', compact('categories', 'products', 'last_products', 'carousel_quantity', 'categories_outstanding'));
	}

	public function view_product_profile($slug) {

		// $categories = Categoria::whereCondicion(1)
		// 	->select(['idcategoria', 'nombre as name', 'slug'])
		// 	->get();
		$categories = [];

		// $product = Articulo::with('category')
		// 	->whereSlug($slug)
		// 	->first();

		$products_related = Articulo::where('idcategoria', $product->idcategoria)
			->where('idarticulo', '!=', $product->idarticulo)
			->get();

		return view('store.products.profile.index', compact('categories', 'products_related'));
	}

	public function view_cart() {
		$products = [];
		$total = 0;

		// $categories = Categoria::whereCondicion(1)
		// 	->select(['idcategoria', 'nombre as name', 'slug'])
		// 	->get();
		$categories = [];


		return view('store.checkout.shopping_cart', compact('products', 'total', 'categories'));
	}

	public function view_order() {
		// $categories = Categoria::whereCondicion(1)
		// 	->select(['idcategoria', 'nombre as name', 'slug'])
		// 	->get();
		$categories = [];
		// $years = Order::select(['year'])
		// 	->groupBy('year')
		// 	->orderBy('year', 'DESC')
		// 	->get();

		$years = DB::table('orders')
			->groupBy('year')
			->orderBy('year', 'DESC')
			->select(['year'])
			->where('deleted_at', NULL)
			->get();

		$search_button = false;

		return view('store.checkout.check_out', compact('categories', 'years', 'search_button'));
	}

	public function validate_student(Request $request) {
		$code = $request->code;
		$identity_document = $request->identity_document;

		$student = Entity::where('identity_document', $identity_document)
			->first();

		$order = Order::where('code', $code)
			->where('entity_id', $student->id)
			->first();

		if ($order) {
			return redirect('/cartilla')->with('code', $code);
		}

		return "Estudiante no encontrado";
	}

	public function view_about_us() {

		$categories = Categoria::whereCondicion(1)
			->select(['idcategoria', 'nombre as name', 'slug'])
			->get();

		return view('store.about_us', compact('categories'));
	}

	public function details_document_view(Request $request) {
		$products = [];
		$total = 0;

		$categories = Categoria::whereCondicion(1)
			->select(['idcategoria', 'nombre as name', 'slug'])
			->get();

		return view('store.checkout.shopping_cart', compact('products', 'total', 'categories'));
	}

	public function get_document_state($id) {

		$document_state = DocumentState::find($id);
		return $document_state;

	}

	public function email_view() {

		$company = Company::first();

		$logo = $company->logo;
		$company_name = $company->name;
		$firstname = "Luis";
		$dni_ruc = "7214634";
		$course = "dwada";
		$course = "dwada";
		$date = "19/09/2020";
		$city = "Tacna";
		$email = "my@gmail.com";
		$phone = "993943";
		$payment_way_id = "1";
		$amount = "10";
		$account_name = "mi cuenta";
		return view('emails.notification_entity', compact('logo', 'company_name', 'firstname', 'dni_ruc', 'course', 'date', 'city', 'email', 'phone', 'payment_way_id', 'amount', 'account_name'));
	}

	public function detail_entity($identity_document)
	{
		$entity = Entity::whereIdentityDocument($identity_document)->get();

		if (count($entity)) {
			return response()->json(['success' => true, 'entity' => $entity[0]]);
		}

		return response()->json(['success' => false]);

	}

	public function view_requirements(Request $request) {

		$order_id = $request->order_id;
		$entity_id = $request->entity_id;

		$entity = Entity::find($entity_id);
		$order = Order::find($order_id);

		$nivel_arr_values = array(1 => "PRIMARIA", 2 => "SECUNDARIA");
		$grade_arr_values = array(1 => "1ro", 2 => "2do", 3 => "3ro", 4 => "4to", 5 => "5to", 6 => "6to");

		$nivel = $nivel_arr_values[$order->order_type_id];
		$grade = $grade_arr_values[$order->tupa_id];

		$identifier = $request->identificador;

		$company = Company::first();
		##NEW---
		$document_types = DocumentType::all();
		$search_button = true;

		$all_tupa = Tupa::where('id', '!=', 0)
			->get();

		if ($identifier != "") {
			$tupa = Tupa::with('requirements')
				->where('id', $identifier)
				->paginate(5);
		} else {
			$tupa = Tupa::with('requirements')
				->where('id', '!=', 0)
				->paginate(5);
		}

		$candidates = Candidate::where('position', 'Alcalde')->get();

		return view('store.products.requirements', compact('company', 'document_types', 'search_button', 'tupa', 'all_tupa', 'identifier', 'candidates', 'entity', 'order', 'nivel', 'grade'));
	}

	public function send_vote(Request $request) {
		$data = $request->all();

		if(!$data['index']) {
			return response()->json(['success' => false, 'title' => 'Error', 'message' => 'Estudiante no encontrado.'], 400);
		}

		if(!$data['candidate_id']) {
			return response()->json(['success' => false, 'title' => 'Error', 'message' => 'Candidato no seleccionado.'], 400);
		}

		$order = Order::find($data['index']);

		if($order->voted) {
			return response()->json(['success' => false, 'title' => 'Error', 'message' => 'Estudiante ya ha votado.'], 400);
		}

		$new_vote = new Vote();
		$new_vote->nivel = $order->order_type_id;
		$new_vote->grade = $order->tupa_id;
		$new_vote->section = $order->subject;
		$new_vote->category_candidate_id = 1;
		$new_vote->candidate_id = $data['candidate_id'];
		$new_vote->save();

		$order->voted = 1;
		$order->save();

		return response()->json(['success' => true, 'title' => 'Éxito', 'message' => 'Voto enviado correctamente']);


	}

	// public function qr_view()
	// {
	// 	return view('qr_view');
	// }


}
