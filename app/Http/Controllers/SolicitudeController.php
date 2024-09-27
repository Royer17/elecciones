<?php

namespace sisVentas\Http\Controllers;

use Auth;
use Carbon\Carbon;
use DB;
use Datatables;
use Excel;
use Fpdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use sisVentas\Candidate;
use sisVentas\Categoria;
use sisVentas\Company;
use sisVentas\DetailOrder;
use sisVentas\DocumentState;
use sisVentas\DocumentType;
use sisVentas\Entity;
use sisVentas\Http\Requests\LoggedSolicitudeUpdateRequest;
use sisVentas\Http\Requests\OfficeFormRequest;
use sisVentas\Office;
use sisVentas\Order;
use sisVentas\OrderMultipleDocument;
use sisVentas\OrderOrder;
use sisVentas\Payment;
use sisVentas\Tupa;
use sisVentas\User;
use sisVentas\Http\Requests\CandidateRequest;

class SolicitudeController extends Controller {
	public function __construct() {
		$this->middleware('auth');
	}

	public function show_view_candidates(Request $request)
	{
		if ($request) {

			$candidates = Candidate::latest();

			return view('almacen.solicitude.candidates', ["candidates" => $candidates->paginate(10), "searchText" => "", 'offices' => [], 'document_statuses' => [], 'document_status' => "", 'admin' => true, 'start_date' => "", 'end_date' => "", 'status_searched' => ""]);


			// $start_date = "";

			// if ($request->has('inicio')) {
			// 	$start_date = Carbon::createFromFormat('d/m/Y', $request->inicio);
			// }

			// $end_date = "";

			// if ($request->has('fin')) {
			// 	$end_date = Carbon::createFromFormat('d/m/Y', $request->fin);
			// }

			$user = User::with('entity')->find(Auth::user()->id);
			$entity = Entity::find($user->entity_id);

			$office_id = $user->entity->office_id;

			$offices = Office::where('id', '!=', $office_id)
				->get();

			$text = trim($request->get('searchText'));
			$document_status = 1;

			$status_searched = $request->status;

			$orders_status_arr = [
				'activos' => 1,
				'anulados' => 2,
				'retirados' => 3,
			];

			$orders = DB::table('orders')
				->orderBy('orders.id', 'desc')
				//->join('details_order', 'orders.id', '=', 'details_order.order_id')
				//->join('document_types', 'orders.document_type_id', '=', 'document_types.id')
				->join('entities', 'orders.entity_id', '=', 'entities.id')
				//->leftJoin('offices', 'details_order.office_id', '=', 'offices.id')
				//->join('document_statuses', 'orders.status', '=', 'document_statuses.id')
				//->leftJoin('orders as parent_order', 'orders.parent_order_id', '=', 'parent_order.id')
				//->leftJoin('offices as office_parent', 'parent_order.office_id', '=', 'office_parent.id')
				->where('orders.deleted_at', null);
				//->where('orders.multiple', 0);
				// ->whereDate('orders.created_at', '>=', $start_date->format('Y-m-d'))
				// ->whereDate('orders.created_at', '<=', $end_date->format('Y-m-d'));
				if ($request->has('inicio')) {
					$orders = $orders->whereDate('orders.created_at', '>=', $start_date->format('Y-m-d'))
						->whereDate('orders.created_at', '<=', $end_date->format('Y-m-d'));
				}

				if ($text) {
					$orders = $orders->where(function ($query) use($text) {
	           			$query->where('entities.identity_document', 'LIKE', "%$text%")
	           				->orWhere('entities.name', 'LIKE', "%$text%")
	           				->orWhere('entities.paternal_surname', 'LIKE', "%$text%")
	           				->orWhere('entities.maternal_surname', 'LIKE', "%$text%");
	                		//->orWhere('entities.identity_document', 'LIKE', "%$text%");
	       			});
				}

				if ($request->status) {
					$status_id = $orders_status_arr[$request->status];
					$orders = $orders->where('orders.status', $status_id);
				}

			$orders = $orders->select(['orders.id', 'orders.subject as subject', 'entities.name', 'entities.paternal_surname', 'entities.maternal_surname', 'entities.identity_document', 'orders.status as status', 'orders.created_at', 'orders.internal_code', 'orders.tupa_id', 'orders.order_type_id', 'entities.cellphone', 'entities.email', 'entities.address', 'orders.code']);
				// ->paginate(20);

			$document_statuses = DocumentState::all();

			$admin = false;

			//$orders = $orders->where('orders.office_id_origen', $office_id)
				//->where('details_order.office_id_origen', $office_id);

			if ($user->role_id == 2) {
				// admin
				return view('almacen.solicitude.candidates', ["orders" => $orders->paginate(10), "searchText" => $text, 'offices' => $offices, 'document_statuses' => $document_statuses, 'document_status' => $document_status, 'admin' => true, 'start_date' => $start_date ? $start_date->format('d/m/Y') : "", 'end_date' => $end_date ? $end_date->format('d/m/Y') : "", 'status_searched' => $status_searched]);

			}

			return view('almacen.solicitude.candidates', ["orders" => $orders->paginate(10), "searchText" => $text, 'offices' => $offices, 'document_statuses' => $document_statuses, 'document_status' => $document_status, 'admin' => false, 'start_date' => $start_date ? $start_date->format('d/m/Y') : "", 'end_date' => $end_date ? $end_date->format('d/m/Y') : "", 'status_searched' => $status_searched]);
		}

	}

	public function show_candidate($id)
	{
		return Candidate::find($id);
	}

	public function store_candidate(CandidateRequest $request)
	{
		$data = $request->except('photo', 'logo');

		$candidate = new Candidate();
		$candidate->fill($data);

		if ($request->hasFile('photo')) {
			$photo = $request->file('photo');
			$photo_name = time().time() . '.' . $photo->getClientOriginalExtension();
			$photo->move(public_path().'/img/candidates/', $photo_name);
			$candidate->photo = '/img/candidates/'.$photo_name;
		}

		if ($request->hasFile('logo')) {
			$logo = $request->file('logo');
			$logo_name = time().time() . '.' . $logo->getClientOriginalExtension();
			$logo->move(public_path().'/img/candidates/', $logo_name);
			$candidate->logo = '/img/candidates/'.$logo_name;
		}

		$candidate->save();

		return response()->json(['title' => 'Operación exitosa', 'message' => 'Candidato registrado correctamente'], 201);
	}

	public function update_candidate($id, CandidateRequest $request)
	{
		$data = $request->except('photo', 'logo');

		$candidate = Candidate::find($id);
		$candidate->fill($data);

		if ($request->hasFile('photo')) {
			$photo = $request->file('photo');
			$photo_name = time().time() . '.' . $photo->getClientOriginalExtension();
			$photo->move(public_path().'/img/candidates/', $photo_name);
			$candidate->photo = '/img/candidates/'.$photo_name;
		}

		if ($request->hasFile('logo')) {
			$logo = $request->file('logo');
			$logo_name = time().time() . '.' . $logo->getClientOriginalExtension();
			$logo->move(public_path().'/img/candidates/', $logo_name);
			$candidate->logo = '/img/candidates/'.$logo_name;
		}

		$candidate->save();

		return response()->json(['title' => 'Operación exitosa', 'message' => 'Candidato actualizado correctamente'], 200);
	}

	public function delete_candidate($id)
	{
		$candidate = Candidate::find($id);
		$candidate->delete();

		return response()->json(['title' => 'Operación exitosa', 'message' => 'Candidato eliminado correctamente'], 200);
	}

	public function show_view_results()
	{
		$company = Company::first();

		$orders = DB::table('orders')
			->where('deleted_at', null)
			->get();

		$total_students = count($orders);

		$students_voted = DB::table('orders')
			->where('deleted_at', null)
			->where('voted', 1)
			->get();

		$total_students_voted = count($students_voted);

		$category_candidates = ['Alcalde'];

		$candidates_results = [];
		
		foreach ($category_candidates as $category) {
			$candidates = DB::table('candidates')
				->where('position', $category)
				->get();

			foreach ($candidates as $candidate) {
				$candidate_results = DB::table('votes')
					->where('category_candidate_id', 1)
					->where('candidate_id', $candidate->id)
					->count();

				$percentage = 0;

				if($total_students_voted > 0) {
					$percentage = ($candidate_results / $total_students_voted) * 100;
				}

				$candidates_results[] = [
					'candidate' => $candidate->firstname.' '.$candidate->lastname,
					'votes' => $candidate_results,
					'percentage' => $percentage,
				];

			}
		}

		//$orders_voted = Order::where('voted', 1)->count();
		return view('results', compact('company', 'total_students', 'total_students_voted', 'candidates_results'));

	}

	public function get_detail($id)
	{
		$order = Order::with(['details' => function($query){
			$query->with('office')
				->with('office_origen')
				->with('state');
				$query->with('user.entity');
		}])
			->with(['children' => function($query){
				$query->with('office');
				$query->with(['details' => function($query){
					$query->with('user.entity')
						->with('state')
						->with('office_origen');
					$query->with('office');
				}]);
			}])
			->with('tupa')
			->with('order_type')
			->with('document_type')
			->with('entity')
			->find($id);

		$document_type = DocumentType::find($order->document_type_id);

		$orders = OrderOrder::whereLastOrderId($id)
			->where('parent_order_id', '!=', 0)
			->with(['parent_order' => function($query){
				$query->with(['details' => function($query){
					$query->with('office')
						->with('office_origen')
						->with('state');
					$query->with('user.entity');
				}])
				->with(['children' => function($query){
					$query->with('office');
					$query->with(['details' => function($query){
						$query->with('user.entity')
							->with('state')
							->with('office_origen');
							$query->with('office');
					}]);
				}])
				->with('order_type')
				->with('document_type')
				->with('entity')
					->with('tupa');
			}])
			->orderBy('id', 'DESC')
			->get();

		if (!count($orders)) {
			$last_order = OrderOrder::whereParentOrderId($id)
				->first();

			if (!empty($last_order)) {
				$last_order_id = $last_order->last_order_id;
				$order_id = $last_order->order_id;
				$orders = OrderOrder::whereLastOrderId($last_order_id)
					->where('order_id', '<', $order_id)
					->where('parent_order_id', '!=', 0)
					->with(['parent_order' => function($query){
						$query->with(['details' => function($query){
							$query->with('office')
								->with('office_origen')
								->with('state');
							$query->with('user.entity');
						}])
						->with(['children' => function($query){
							$query->with('office');
							$query->with(['details' => function($query){
								$query->with('user.entity')
									->with('state')
									->with('office_origen');
									$query->with('office');
							}]);
						}])
						->with('order_type')
						->with('document_type')
						->with('entity')
							->with('tupa');
					}])
					->orderBy('id', 'DESC')
					->get();
			}
		}

		$orders_from_multiple = [];
		
		if ($document_type->is_multiple) {
			$orders_from_multiple = OrderMultipleDocument::whereParentOrderId($id)
				->where('deleted_at', NULL)
				->with(['order' => function($query){
					$query->with('office');
					$query->with(['details' => function($query){
						$query->with('user.entity')
							->with('state')
							->with('office_origen');
						$query->with('office');
					}]);
				}])
				->get();
		}


		return ['order' => $order, 'orders' => $orders, 'orders_from_multiple' => $orders_from_multiple];
	}

	public function get_detail_extorno($id)
	{
		$order = Order::with(['details' => function($query){
			$query->with('office')
				->where('status', '!=', 2)
				->with('office_origen')
				->with('state');
				$query->with('user.entity');
		}])
			->with(['children' => function($query){
				$query->with('office');
				$query->with(['details' => function($query){
					$query->with('user.entity')
						->with('state')
						->with('office_origen');
					$query->with('office');
				}]);
			}])
			->with('tupa')
			->with('entity')
			->find($id);

		$document_type = DocumentType::find($order->document_type_id);

		$orders = OrderOrder::whereLastOrderId($id)
			->where('parent_order_id', '!=', 0)
			->with(['parent_order' => function($query){
				$query->with(['details' => function($query){
					$query->with('office')
						->with('office_origen')
						->with('state');
					$query->with('user.entity');
				}])
				->with(['children' => function($query){
					$query->with('office');
					$query->with(['details' => function($query){
						$query->with('user.entity')
							->with('state')
							->with('office_origen');
							$query->with('office');
					}]);
				}])
				->with('entity')
					->with('tupa');
			}])
			->orderBy('id', 'DESC')
			->get();

		if (!count($orders)) {
			$last_order = OrderOrder::whereParentOrderId($id)
				->first();

			if (!empty($last_order)) {
				$last_order_id = $last_order->last_order_id;
				$order_id = $last_order->order_id;
				$orders = OrderOrder::whereLastOrderId($last_order_id)
					->where('order_id', '<', $order_id)
					->where('parent_order_id', '!=', 0)
					->with(['parent_order' => function($query){
						$query->with(['details' => function($query){
							$query->with('office')
								->with('office_origen')
								->with('state');
							$query->with('user.entity');
						}])
						->with(['children' => function($query){
							$query->with('office');
							$query->with(['details' => function($query){
								$query->with('user.entity')
									->with('state')
									->with('office_origen');
									$query->with('office');
							}]);
						}])
						->with('entity')
							->with('tupa');
					}])
					->orderBy('id', 'DESC')
					->get();


			}
		}

		$orders_from_multiple = [];
		
		if ($document_type->is_multiple) {
			$orders_from_multiple = OrderMultipleDocument::whereParentOrderId($id)
				->with(['order' => function($query){
					$query->with('office');
					$query->with(['details' => function($query){
						$query->with('user.entity')
							->with('state')
							->with('office_origen');
						$query->with('office');
					}]);
				}])
				->get();
		}


		return ['order' => $order, 'orders' => $orders, 'orders_from_multiple' => $orders_from_multiple];
	}


	public function index(Request $request) {
		if ($request) {

			$start_date = Carbon::now();

			if ($request->has('inicio')) {
				$start_date = Carbon::createFromFormat('d/m/Y', $request->inicio);
			}

			$end_date = Carbon::now();

			if ($request->has('fin')) {
				$end_date = Carbon::createFromFormat('d/m/Y', $request->fin);
			}

			$user = User::with('entity')->find(Auth::user()->id);
			$entity = Entity::find($user->entity_id);

			$office_id = $user->entity->office_id;

			$offices = Office::where('id', '!=', $office_id)
				->get();

			$text = trim($request->get('searchText'));
			$document_status = $request->document_status;
			$orders = DB::table('orders')
				->orderBy('orders.id', 'desc')
				->join('document_types', 'orders.document_type_id', '=', 'document_types.id')
				->join('entities', 'orders.entity_id', '=', 'entities.id')
				->join('offices', 'orders.office_id', '=', 'offices.id')
				->join('document_statuses', 'orders.status', '=', 'document_statuses.id')
				->leftJoin('orders as parent_order', 'orders.parent_order_id', '=', 'parent_order.id')
				->leftJoin('offices as office_parent', 'parent_order.office_id', '=', 'office_parent.id')
				->where('orders.deleted_at', null)
				->whereDate('orders.created_at', '>=', $start_date->format('Y-m-d'))
				->whereDate('orders.created_at', '<=', $end_date->format('Y-m-d'));

				if ($text) {
					$orders = $orders->where(function ($query) use($text) {
               			$query->where('orders.code', $text)
                    		->orWhere('entities.identity_document', $text);
           			});
				}

				if ($document_status) {
					$orders = $orders->where('orders.status', $document_status);
				}


			$orders = $orders->select(['orders.id', 'orders.code', 'document_types.name as document_type_name', 'orders.number as number', 'orders.subject as subject', 'entities.name as name', 'entities.paternal_surname as paternal_surname', 'entities.maternal_surname as maternal_surname', 'entities.identity_document as identity_document', 'entities.type_document as type_document', 'entities.cellphone as cellphone', 'entities.email as email', 'entities.address as address' , 'offices.name as office_name', 'orders.status as status', 'document_statuses.name as status_name', 'orders.attached_file', 'orders.created_at', 'orders.parent_order_id', 'office_parent.name as office_parent_name']);
				// ->paginate(20);

			$document_statuses = DocumentState::all();

			$orders = $orders->where('orders.office_id', $office_id);


			if ($user->role_id == 2) {
				// admin
				return view('almacen.solicitude.index', ["orders" => $orders->paginate(20), "searchText" => $text, 'offices' => $offices, 'document_statuses' => $document_statuses, 'document_status' => $document_status, 'admin' => true, 'start_date' => $start_date->format('d/m/Y'), 'end_date' => $end_date->format('d/m/Y')]);

			}

			return view('almacen.solicitude.index', ["orders" => $orders->paginate(20), "searchText" => $text, 'offices' => $offices, 'document_statuses' => $document_statuses, 'document_status' => $document_status, 'admin' => false, 'start_date' => $start_date->format('d/m/Y'), 'end_date' => $end_date->format('d/m/Y')]);
		}
	}

	public function get_view_new_solicitudes(Request $request) {
		if ($request) {

			$start_date = Carbon::now();

			if ($request->has('inicio')) {
				$start_date = Carbon::createFromFormat('d/m/Y', $request->inicio);
			}

			$end_date = Carbon::now();

			if ($request->has('fin')) {
				$end_date = Carbon::createFromFormat('d/m/Y', $request->fin);
			}

			$user = User::with('entity')->find(Auth::user()->id);
			$entity = Entity::find($user->entity_id);

			$office_id = $user->entity->office_id;

			$offices = Office::where('id', '!=', $office_id)
				->get();

			$text = trim($request->get('searchText'));

			$orders = DB::table('orders')
				->orderBy('orders.id', 'desc')
				->join('document_types', 'orders.document_type_id', '=', 'document_types.id')
				->join('entities', 'orders.entity_id', '=', 'entities.id')
				->join('offices', 'orders.office_id', '=', 'offices.id')
				->join('document_statuses', 'orders.status', '=', 'document_statuses.id')
				->leftJoin('orders as parent_order', 'orders.parent_order_id', '=', 'parent_order.id')
				->leftJoin('offices as office_parent', 'parent_order.office_id', '=', 'office_parent.id')
				->where('orders.deleted_at', null)
				->whereDate('orders.created_at', '>=', $start_date->format('Y-m-d'))
				->whereDate('orders.created_at', '<=', $end_date->format('Y-m-d'));

				if ($text) {
					$orders = $orders->where(function ($query) use($text) {
               			$query->where('orders.code', $text)
                    		->orWhere('entities.identity_document', $text);
           			});
				}

				if (true) {
					$orders = $orders->where('orders.status', 1);
				}


			$orders = $orders->select(['orders.id', 'orders.code', 'document_types.name as document_type_name', 'orders.number as number', 'orders.subject as subject', 'entities.name as name', 'entities.paternal_surname as paternal_surname', 'entities.maternal_surname as maternal_surname', 'entities.identity_document as identity_document', 'entities.type_document as type_document', 'entities.cellphone as cellphone', 'entities.email as email', 'entities.address as address' , 'offices.name as office_name', 'orders.status as status', 'document_statuses.name as status_name', 'orders.attached_file', 'orders.created_at', 'orders.parent_order_id', 'office_parent.name as office_parent_name']);
				// ->paginate(20);


			$document_statuses = DocumentState::all();

			$flag = false;

			$orders = $orders->where('orders.office_id', $office_id);

			if ($user->role_id == 2) {
				// admin
				return view('almacen.solicitude.new', ["orders" => $orders->paginate(20), "searchText" => $text, 'offices' => $offices, 'document_statuses' => $document_statuses, 'flag' => $flag, 'start_date' => $start_date->format('d/m/Y'), 'end_date' => $end_date->format('d/m/Y')]);

			}

			if ($entity->office_id == 1) {
				#no admin
				// $office_id = $user->entity->office_id;
				// $orders = $orders->where('orders.office_id', $office_id);
				return view('almacen.solicitude.new', ["orders" => $orders->paginate(20), "searchText" => $text, 'offices' => $offices, 'document_statuses' => $document_statuses, 'flag' => true, 'start_date' => $start_date->format('d/m/Y'), 'end_date' => $end_date->format('d/m/Y')]);
			}


			return view('almacen.solicitude.new', ["orders" => $orders->paginate(20), "searchText" => $text, 'offices' => $offices, 'document_statuses' => $document_statuses, 'flag' => $flag, 'start_date' => $start_date->format('d/m/Y'), 'end_date' => $end_date->format('d/m/Y')]);
		}
	}


	public function create_solicitude()
	{
		//$document_types = DocumentType::all();


		$user_id = Auth::user()->id;

		$user = User::with('entity.office.entity')->find($user_id);
		$current_office = $user->entity->office->name;
		$current_office_sigla = $user->entity->office->sigla;

		$current_office_id = $user->entity->office->id;

		$document_types = DB::table('document_types')
			->join('document_type_office_selected', 'document_types.id', '=', 'document_type_office_selected.document_type_id')
			->where('document_type_office_selected.office_id', $current_office_id)
			->get(['document_types.id', 'document_types.name']);
		//return $user;

		$offices = Office::where('id', '!=', $current_office_id)
			->get();

		//$user->entity->office->entity->name;
		//$user->entity->office->entity->lastname;

		// { id
		// name,
		// lastname
		// dawd : [{}, {}, {}]
		// entity: {
		// 	name, lastname, adress
		// 	office: {
		// 		entity: {

		// 		}
		// 	}

		// }
		//  }
		// }
		$today = Carbon::now();
		$year = $today->format('Y');

		$last_order = DB::table('orders')
			->orderBy('id', 'DESC')
			->where('parent_order_id', 0)
			->where('code', '!=', "")
			->first();

		$next_number = 1;

		if (!empty($last_order)) {

			$old_year = substr($last_order->code, 0, 4);

			$next_number = 1;

			if ($old_year  == $year) {
				$number_extracted = substr($last_order->code, 4);
				$next_number = (int)$number_extracted + 1;
			}
		}

		$number_of_characters = strlen($next_number);
		$total_length = 7;

		if ($number_of_characters >= 7) {
			$code = $next_number;
		} else {
			$left = $total_length - $number_of_characters;
			$code = str_repeat("0", $left)."{$next_number}";
		}

		$today_date = $today->format('d/m/Y');
		$today_hour = $today->format('H:i');

		//$tupa = Tupa::all();
		$tupa = DB::table('tupa')
			->where('deleted_at', NULL)
			->get();

		$order_types = DB::table('order_types')
			->where('deleted_at', NULL)
			->get();

		return view('create_solicitude', compact('document_types', 'user', 'offices', 'current_office', 'current_office_id', 'today_date', 'today_hour', 'current_office_sigla', 'year', 'code', 'tupa', 'order_types'));
	}

	public function register_student_view(Request $request)
	{
		//$document_types = DocumentType::all();
		$user_id = Auth::user()->id;
		$enrollment_year = $request->anio ? $request->anio : "2024";

		$user = User::with('entity.office.entity')
			->find($user_id);
		$current_office = $user->entity->office->name;
		$current_office_sigla = $user->entity->office->sigla;

		$current_office_id = $user->entity->office->id;

		$document_types = DB::table('document_types')
			->join('document_type_office_selected', 'document_types.id', '=', 'document_type_office_selected.document_type_id')
			->where('document_type_office_selected.office_id', $current_office_id)
			->get(['document_types.id', 'document_types.name']);
		//return $user;

		$offices = Office::where('id', '!=', $current_office_id)
			->get();

		//$user->entity->office->entity->name;
		//$user->entity->office->entity->lastname;

		// { id
		// name,
		// lastname
		// dawd : [{}, {}, {}]
		// entity: {
		// 	name, lastname, adress
		// 	office: {
		// 		entity: {

		// 		}
		// 	}

		// }
		//  }
		// }
		$today = Carbon::now();
		//$year = $today->format('Y');
		$year = $enrollment_year;

		$last_order = DB::table('orders')
			->orderBy('id', 'DESC')
			->where('parent_order_id', 0)
			->where('code', '!=', "")
			->first();

		$next_number = 1;

		if (!empty($last_order)) {

			$old_year = substr($last_order->code, 0, 4);

			$next_number = 1;

			if ($old_year  == $year) {
				$number_extracted = substr($last_order->code, 4);
				$next_number = (int)$number_extracted + 1;
			}
		}

		$number_of_characters = strlen($next_number);
		$total_length = 7;

		if ($number_of_characters >= 7) {
			$code = $next_number;
		} else {
			$left = $total_length - $number_of_characters;
			$code = str_repeat("0", $left)."{$next_number}";
		}

		$today_date = $today->format('d/m/Y');
		$today_hour = $today->format('H:i');

		//$tupa = Tupa::all();
		$tupa = DB::table('tupa')
			->where('deleted_at', NULL)
			->get();

		$order_types = DB::table('order_types')
			->where('deleted_at', NULL)
			->get();

		$payment_concepts = Office::whereStatus(1)
			->where('year', $year)
			->orWhere('is_for_all_years', true)
			->orderBy('upper_office_id', 'ASC')
			->get();

		return view('register_student', compact('document_types', 'user', 'offices', 'current_office', 'current_office_id', 'today_date', 'today_hour', 'current_office_sigla', 'year', 'code', 'tupa', 'order_types', 'payment_concepts'));
	}

public function enrollment_data_view(Request $request)
	{
		//$document_types = DocumentType::all();
		$user_id = Auth::user()->id;

		$user = User::with('entity.office.entity')->find($user_id);
		$current_office = $user->entity->office->name;
		$current_office_sigla = $user->entity->office->sigla;

		$current_office_id = $user->entity->office->id;

		$document_types = DB::table('document_types')
			->join('document_type_office_selected', 'document_types.id', '=', 'document_type_office_selected.document_type_id')
			->where('document_type_office_selected.office_id', $current_office_id)
			->get(['document_types.id', 'document_types.name']);
		//return $user;

		$offices = Office::where('id', '!=', $current_office_id)
			->get();

		//$user->entity->office->entity->name;
		//$user->entity->office->entity->lastname;

		// { id
		// name,
		// lastname
		// dawd : [{}, {}, {}]
		// entity: {
		// 	name, lastname, adress
		// 	office: {
		// 		entity: {

		// 		}
		// 	}

		// }
		//  }
		// }
		$today = Carbon::now();
		$year = $today->format('Y');

		$last_order = DB::table('orders')
			->orderBy('id', 'DESC')
			->where('parent_order_id', 0)
			->where('code', '!=', "")
			->first();

		$next_number = 1;

		if (!empty($last_order)) {

			$old_year = substr($last_order->code, 0, 4);

			$next_number = 1;

			if ($old_year  == $year) {
				$number_extracted = substr($last_order->code, 4);
				$next_number = (int)$number_extracted + 1;
			}
		}

		$number_of_characters = strlen($next_number);
		$total_length = 7;

		if ($number_of_characters >= 7) {
			$code = $next_number;
		} else {
			$left = $total_length - $number_of_characters;
			$code = str_repeat("0", $left)."{$next_number}";
		}

		$today_date = $today->format('d/m/Y');
		$today_hour = $today->format('H:i');

		//$tupa = Tupa::all();
		$tupa = DB::table('tupa')
			->where('deleted_at', NULL)
			->get();

		$order_types = DB::table('order_types')
			->where('deleted_at', NULL)
			->get();

		$first_months = Office::whereStatus(1)
			->whereSection(1)
			->get();

		$last_months = Office::whereStatus(1)
			->whereSection(2)
			->get();

		$year_default = "2024";
		if ($request->year) {
			$year_default = $request->year;
		}

		return view('enrollment_form', compact('document_types', 'user', 'offices', 'current_office', 'current_office_id', 'today_date', 'today_hour', 'current_office_sigla', 'year', 'code', 'tupa', 'order_types', 'first_months', 'last_months', 'year_default'));
	}



	public function edit_student_record_view($order_id)
	{
		//$document_types = DocumentType::all();
		$user_id = Auth::user()->id;

		$user = User::with('entity.office.entity')->find($user_id);
		$current_office = $user->entity->office->name;
		$current_office_sigla = $user->entity->office->sigla;

		$current_office_id = $user->entity->office->id;

		$document_types = DB::table('document_types')
			->join('document_type_office_selected', 'document_types.id', '=', 'document_type_office_selected.document_type_id')
			->where('document_type_office_selected.office_id', $current_office_id)
			->get(['document_types.id', 'document_types.name']);
		//return $user;

		$offices = Office::where('id', '!=', $current_office_id)
			->get();

		//$user->entity->office->entity->name;
		//$user->entity->office->entity->lastname;

		// { id
		// name,
		// lastname
		// dawd : [{}, {}, {}]
		// entity: {
		// 	name, lastname, adress
		// 	office: {
		// 		entity: {

		// 		}
		// 	}

		// }
		//  }
		// }
		$today = Carbon::now();
		$year = $today->format('Y');

		$last_order = DB::table('orders')
			->orderBy('id', 'DESC')
			->where('parent_order_id', 0)
			->where('code', '!=', "")
			->first();

		$next_number = 1;

		if (!empty($last_order)) {

			$old_year = substr($last_order->code, 0, 4);

			$next_number = 1;

			if ($old_year  == $year) {
				$number_extracted = substr($last_order->code, 4);
				$next_number = (int)$number_extracted + 1;
			}
		}

		$number_of_characters = strlen($next_number);
		$total_length = 7;

		if ($number_of_characters >= 7) {
			$code = $next_number;
		} else {
			$left = $total_length - $number_of_characters;
			$code = str_repeat("0", $left)."{$next_number}";
		}

		$today_date = $today->format('d/m/Y');
		$today_hour = $today->format('H:i');

		//$tupa = Tupa::all();
		$tupa = DB::table('tupa')
			->where('deleted_at', NULL)
			->get();

		$order_types = DB::table('order_types')
			->where('deleted_at', NULL)
			->get();

		$order = Order::with('details')
			->find($order_id)
			->toArray();

		if (empty($order['details'])) {

			$first_months = Office::whereStatus(1)
				->whereSection(1)
				->get();

			foreach ($first_months as $key => $month) {
				$new_detail = new DetailOrder();
				$new_detail->order_id = $order_id;
				$new_detail->status = 0;
				$new_detail->office_id_origen = $month->section;
				$new_detail->office_id = $month->id;
				$new_detail->observations = 20;
				$new_detail->last = false;
				$new_detail->save();
			}

			$last_months = Office::whereStatus(1)
				->whereSection(2)
				->get();

			foreach ($last_months as $key => $month) {
				$new_detail = new DetailOrder();
				$new_detail->order_id = $order_id;
				$new_detail->status = 0;
				$new_detail->office_id_origen = $month->section;
				$new_detail->office_id = $month->id;
				$new_detail->observations = 20;
				$new_detail->last = false;
				$new_detail->save();
			}
		}

		$order = Order::with('entity.profession')
			->with('details')
			->find($order_id);

		$order_details_id_arr = DetailOrder::whereOrderId($order_id)
			->pluck('office_id')->toArray();


		$payment_concepts = Office::whereStatus(1)
			->whereNotIn('id', $order_details_id_arr)
			->where('year', $year)
			->orWhere('is_for_all_years', true)
			->whereNotIn('id', $order_details_id_arr)
			->orderBy('upper_office_id', 'ASC')
			->get();

		return view('edit_student_record', compact('document_types', 'user', 'offices', 'current_office', 'current_office_id', 'today_date', 'today_hour', 'current_office_sigla', 'year', 'code', 'tupa', 'order_types', 'order', 'order_details_id_arr', 'payment_concepts'));
	}


	public function report_payments($order_id)
	{

		$today = Carbon::now();
		$year = $today->format('Y');
		$today_formatted = $today->format('d/m/Y H:i:s');

		$nivel_arr_values = array(1 => "PRIMARIA", 2 => "SECUNDARIA");
		$grade_arr_values = array(1 => "1ro", 2 => "2do", 3 => "3ro", 4 => "4to", 5 => "5to", 6 => "6to");

		//$tupa = Tupa::all();
		$tupa = DB::table('tupa')
			->where('deleted_at', NULL)
			->get();

		$order_types = DB::table('order_types')
			->where('deleted_at', NULL)
			->get();

		$order = Order::with('entity.profession')
			->with('details_one.office')
			->with('details_two.office')
			->with('debt_details.office')
			->find($order_id);


			$pdf = app('Fpdf');
            	$pdf->AddPage("L", "A5");

            	$pdf->AddFont('Calibri','','calibri.php');
                $pdf->AddFont('Calibri-Bold','','calibrib.php');
                $pdf->AddFont('Times-Bold','','timesb.php');

                //$pdf->AddFont('Calibri-Italic','','calibri_i.php');
                //$pdf->AddFont('Calibri-BoldItalic','','calibri_bi.php');
                $normal_space = 4.5;
                $footer_font_size = 7;
                $norma_font_size = 8;
                $font_size_11 = 9;
                $font_size_12 = 12;
                $font_size_13 = 13;
                $font_size_14 = 14;

                $margen_x = 5;
                $margen_start_second_head = 110;
                $total_width_allowed = 105;
                $total_width_allowed_no_margin = 95;

                //zamuro
                $pdf->Image(asset('assets/cabeceras/champagnat_escudo.jpg'), $margen_x , 5, 10);

                $pdf->Ln();
            	
                $pdf->SetY(10);
                $pdf->SetX(33);
                //A4
                //anchura go from 0 to 297 / 2 = 148.5
                //80 68
                //10 - 128  - 10

                //A5
                //210 - 105 105  5 95 5

                $pdf->SetFont('Calibri', '', 13);
                $pdf->SetLineWidth(0.5);
                //$pdf->SetX(15);
                //$pdf->Multicell(3, 18, "", "L", "C");

                $pdf->SetLineWidth(0.2);

                $pdf->SetY(6);
                $pdf->SetX(15);
                $pdf->SetFont('Calibri-Bold', '', 7);
                $pdf->Cell(42, 5, utf8_decode("APAFA {$year}"), 0, "", "C");
                $pdf->Ln(2.5);

                $y = $pdf->GetY();

                $pdf->SetX(15);
                $pdf->SetFont('Calibri', '', 7);
                $pdf->Cell(42, 5, utf8_decode('INSTITUCIÓN EDUCATIVA'), 0, "", "C");
                $pdf->Ln(2.5);

                $pdf->SetX(15);
                $pdf->SetFont('Calibri', '', 7);
                $pdf->Cell(42, 5, utf8_decode("CHAMPAGNAT"), 0, "", "C");
                $pdf->Ln();

 				$pdf->SetY(6);
                $pdf->SetX(57);
                $pdf->SetFont('Calibri-Bold', '', 7);
                $pdf->Cell(42, 5, utf8_decode("Nro. : {$order->code}"), 0, "", "C");
                $pdf->Ln(2.5);

                $pdf->SetX(57);
                $pdf->SetFont('Calibri', '', 7);
                $pdf->Cell(42, 5, utf8_decode($today_formatted), 0, "", "C");
                $pdf->Ln(2.5);

                $pdf->SetX(57);
                $pdf->SetFont('Calibri-Bold', '', 6);
                $pdf->Cell(42, 5, utf8_decode("Fecha de impresión"), 0, "", "C");

                $pdf->SetY(16);
                $pdf->SetX(5);
                $pdf->SetFont('Calibri-Bold', '', 15);
                $pdf->Cell(95, 8, utf8_decode('PAGOS DE SERVICIOS'), 0, "", "C");

                $pdf->Ln();
                //$pdf->Ln(1);

                $pdf->SetX(5);
                $pdf->Cell(95, 8, utf8_decode(""), "T", "", "C");

               	$pdf->Ln(1);
                $pdf->SetX(5);

                $pdf->SetFont('Calibri-Bold', '', $norma_font_size);
                $pdf->cell(24, 5.5, utf8_decode("GIRO/RUBRO: "), 0, "", "L");
                $pdf->SetFont('Calibri', '', $norma_font_size);
                $pdf->cell(0, 5.5, utf8_decode("COLEGIOS"), 0, "", "L");

                //$pdf->SetY(52);

                $pdf->Ln(4.5);
                //$pdf->Ln(0);
                $pdf->SetX(5);

                //$pdf->SetX(0);
                $pdf->SetFont('Times-Bold', '', 13);
                $pdf->Cell(95, 8, utf8_decode('I.E. CHAMPAGNAT-TACNA'), 0, "", "C");

                $pdf->Ln(7);
                //$pdf->Ln(0);
                $pdf->SetX(5);

                $pdf->SetFont('Calibri-Bold', '', $norma_font_size);
                $pdf->cell(24, 5.5, utf8_decode("ID ALUMNO: "), 0, "", "L");
                $pdf->SetFont('Calibri', '', $norma_font_size);
                $pdf->cell(0, 5.5, utf8_decode($order->entity->identity_document), 0, "", "L");
                $pdf->Ln(4.5);
                $pdf->SetX(5);

                $pdf->SetFont('Calibri-Bold', '', $norma_font_size);
                $pdf->cell(24, 5.5, utf8_decode("NOMBRES: "), 0, "", "L");
                $pdf->SetFont('Calibri', '', $norma_font_size);
                $pdf->cell(0, 5.5, utf8_decode($order->entity->paternal_surname." ".$order->entity->maternal_surname.", ".$order->entity->name), 0, "", "L");


                $pdf->Ln(4.5);
                $pdf->SetX(5);
                $pdf->SetFont('Calibri-Bold', '', $norma_font_size);
                $pdf->cell(26, 5.5, utf8_decode("MATRICULADO: "), 0, "", "L");
                $pdf->SetFont('Calibri', '', $norma_font_size);

                $nivel = $nivel_arr_values[$order->order_type_id];
                $grade = $grade_arr_values[$order->tupa_id];

                $pdf->cell(0, 5.5, utf8_decode("{$grade} Grado {$order->subject} DE {$nivel} {$order->year}"), 0, "", "L");

                //$pdf->SetY(56);
                $pdf->Ln(5);

				//$pdf->SetX(15);
				$y = $pdf->GetY();
                $pdf->SetX(5);

                $pdf->SetFont('Calibri-Bold', '', $font_size_11);

                $pdf->Multicell(20, 5, utf8_decode("AÑO"), "LTRB", "C");

                $pdf->SetY($y);
                $pdf->SetX(25);
                $pdf->Multicell(55, 5, utf8_decode("DESCRIPCIÓN"), "TRB", "L");

                $pdf->SetY($y);
                $pdf->SetX(80);
                $pdf->Multicell(20, 5, utf8_decode("Sub Total"), "TRB", "C");

	                //$pdf->Ln(0);

               	$total_debt = 0;

                foreach ($order->debt_details as $key => $detail) {
	 				$total_debt += (float)$detail->observations;

	 				$pdf->Ln(0);
               	
	                $y = $pdf->GetY();
	                $pdf->SetFont('Calibri', '', $norma_font_size);
	                $pdf->SetX(5);
	                $pdf->Multicell(20, 5, utf8_decode($order->year), "
	                    LRB", "C");

	                $pdf->SetY($y);
	                $pdf->SetX(25);
	                $pdf->Multicell(55, 5, utf8_decode("MENSUALIDAD ". strtoupper($detail->office->name)), "RB", "L");

	                $pdf->SetY($y);
	                $pdf->SetX(80);
	                //$pdf->SetFontSize(16);
	                $pdf->Multicell(20, 5, utf8_decode(number_format($detail->observations, 2, ',','')), "LRB", "R");

                }

                $pdf->Ln(0);
	            $y = $pdf->GetY();
               
	            $pdf->SetX(55);
                $pdf->Multicell(25, 5, utf8_decode("TOTAL :"), 0, "C");

	            $pdf->SetY($y);
                $pdf->SetX(80);
                $pdf->SetFontSize(13);
                $pdf->Multicell(20, 5, utf8_decode(number_format($total_debt, 2, ',','')), "LRB", "R");

                $total_debt_formatted = number_format($total_debt, 2, ',','');

                $pdf->Ln(3);
                $pdf->SetX(5);
                $pdf->SetFont('Calibri', '', 8);
                $pdf->cell(33.5, 5.5, utf8_decode("** TOTAL DEUDA(S) PENDIENTE(S): S/.{$total_debt_formatted}"), 0, "", "L");

                $pdf->Ln(3);
                $pdf->SetX(5);
                $pdf->SetFont('Calibri', '', 8);
                $pdf->cell(33.5, 5.5, utf8_decode("AÑO: {$order->year} DEUDA: {$total_debt_formatted} SOLES"), 0, "", "L");


                ////-----------------------------------------------------------------
                $init_x = 105;
                $pdf->Image(asset('assets/cabeceras/champagnat_escudo.jpg'), $init_x + 5 , 5, 10);
                $pdf->Ln();
                //$pdf->SetY(10);
                //A4
                //anchura go from 0 to 297 / 2 = 148.5
                //80 68
                //10 - 128  - 10

                //A5
                //210 - 105 105  5 95 5

                $pdf->SetFont('Calibri', '', 13);
                //$pdf->SetLineWidth(0.5);
                //$pdf->SetX(15);
                //$pdf->Multicell(3, 18, "", "L", "C");

                //$pdf->SetLineWidth(0.2);

                $pdf->SetY(6);
                $pdf->SetX($init_x + 15);
                $pdf->SetFont('Calibri-Bold', '', 7);
                $pdf->Cell(42, 5, utf8_decode("APAFA {$year}"), 0, "", "C");
                $pdf->Ln(2.5);

                $y = $pdf->GetY();

                $pdf->SetX($init_x + 15);
                $pdf->SetFont('Calibri', '', 7);
                $pdf->Cell(42, 5, utf8_decode('INSTITUCIÓN EDUCATIVA'), 0, "", "C");
                $pdf->Ln(2.5);

                $pdf->SetX($init_x + 15);
                $pdf->SetFont('Calibri', '', 7);
                $pdf->Cell(42, 5, utf8_decode("CHAMPAGNAT"), 0, "", "C");
                $pdf->Ln();

 				$pdf->SetY(6);
                $pdf->SetX($init_x + 57);
                $pdf->SetFont('Calibri-Bold', '', 7);
                $pdf->Cell(42, 5, utf8_decode("Nro. : {$order->code}"), 0, "", "C");
                $pdf->Ln(2.5);

                $pdf->SetX($init_x + 57);
                $pdf->SetFont('Calibri', '', 7);
                $pdf->Cell(42, 5, utf8_decode($today_formatted), 0, "", "C");
                $pdf->Ln(2.5);

                $pdf->SetX($init_x +57);
                $pdf->SetFont('Calibri-Bold', '', 6);
                $pdf->Cell(42, 5, utf8_decode("Fecha de impresión"), 0, "", "C");

                $pdf->SetY(16);
                $pdf->SetX($init_x + 5);
                $pdf->SetFont('Calibri-Bold', '', 15);
                $pdf->Cell(95, 8, utf8_decode('PAGOS DE SERVICIOS'), 0, "", "C");
                $pdf->Ln();
                //$pdf->Ln(1);
                $pdf->SetX($init_x + 5);
                $pdf->Cell(95, 8, utf8_decode(""), "T", "", "C");

               	$pdf->Ln(1);
                $pdf->SetX($init_x + 5);

                $pdf->SetFont('Calibri-Bold', '', $norma_font_size);
                $pdf->cell(24, 5.5, utf8_decode("GIRO/RUBRO: "), 0, "", "L");
                $pdf->SetFont('Calibri', '', $norma_font_size);
                $pdf->cell(0, 5.5, utf8_decode("COLEGIOS"), 0, "", "L");

                //$pdf->SetY(52);

                $pdf->Ln(4.5);
                //$pdf->Ln(0);
                $pdf->SetX($init_x + 5);

                //$pdf->SetX(0);
                $pdf->SetFont('Times-Bold', '', 13);
                $pdf->Cell(95, 8, utf8_decode('I.E. CHAMPAGNAT-TACNA'), 0, "", "C");

                $pdf->Ln(7);
                //$pdf->Ln(0);
                $pdf->SetX($init_x + 5);

                $pdf->SetFont('Calibri-Bold', '', $norma_font_size);
                $pdf->cell(24, 5.5, utf8_decode("ID ALUMNO: "), 0, "", "L");
                $pdf->SetFont('Calibri', '', $norma_font_size);
                $pdf->cell(0, 5.5, utf8_decode($order->entity->identity_document), 0, "", "L");
                $pdf->Ln(4.5);
                $pdf->SetX($init_x + 5);

                $pdf->SetFont('Calibri-Bold', '', $norma_font_size);
                $pdf->cell(24, 5.5, utf8_decode("NOMBRES: "), 0, "", "L");
                $pdf->SetFont('Calibri', '', $norma_font_size);
                $pdf->cell(0, 5.5, utf8_decode($order->entity->paternal_surname." ".$order->entity->maternal_surname.", ".$order->entity->name), 0, "", "L");


                $pdf->Ln(4.5);
                $pdf->SetX($init_x + 5);
                $pdf->SetFont('Calibri-Bold', '', $norma_font_size);
                $pdf->cell(26, 5.5, utf8_decode("MATRICULADO: "), 0, "", "L");
                $pdf->SetFont('Calibri', '', $norma_font_size);

                $nivel = $nivel_arr_values[$order->order_type_id];
                $grade = $grade_arr_values[$order->tupa_id];

                $pdf->cell(0, 5.5, utf8_decode("{$grade} Grado {$order->subject} DE {$nivel} {$order->year}"), 0, "", "L");

                //$pdf->SetY(56);
                $pdf->Ln(5);

				//$pdf->SetX(15);
				$y = $pdf->GetY();
                $pdf->SetX($init_x + 5);

                $pdf->SetFont('Calibri-Bold', '', $font_size_11);

                $pdf->Multicell(20, 5, utf8_decode("AÑO"), "LTRB", "C");

                $pdf->SetY($y);
                $pdf->SetX($init_x + 25);
                $pdf->Multicell(55, 5, utf8_decode("DESCRIPCIÓN"), "TRB", "L");

                $pdf->SetY($y);
                $pdf->SetX($init_x + 80);
                $pdf->Multicell(20, 5, utf8_decode("Sub Total"), "TRB", "C");

	                //$pdf->Ln(0);

               	$total_debt = 0;

                foreach ($order->debt_details as $key => $detail) {
	 				$total_debt += (float)$detail->observations;

	 				$pdf->Ln(0);
               	
	                $y = $pdf->GetY();
	                $pdf->SetFont('Calibri', '', $norma_font_size);
	                $pdf->SetX($init_x + 5);
	                $pdf->Multicell(20, 5, utf8_decode($order->year), "
	                    LRB", "C");

	                $pdf->SetY($y);
	                $pdf->SetX($init_x + 25);
	                $pdf->Multicell(55, 5, utf8_decode("MENSUALIDAD ". strtoupper($detail->office->name)), "RB", "L");

	                $pdf->SetY($y);
	                $pdf->SetX($init_x + 80);
	                //$pdf->SetFontSize(16);
	                $pdf->Multicell(20, 5, utf8_decode(number_format($detail->observations, 2, ',','')), "LRB", "R");

                }

                $pdf->Ln(0);
	            $y = $pdf->GetY();
               
	            $pdf->SetX($init_x + 55);
                $pdf->Multicell(25, 5, utf8_decode("TOTAL :"), 0, "C");

	            $pdf->SetY($y);
                $pdf->SetX($init_x + 80);
                $pdf->SetFontSize(13);
                $pdf->Multicell(20, 5, utf8_decode(number_format($total_debt, 2, ',','')), "LRB", "R");

                $total_debt_formatted = number_format($total_debt, 2, ',','');

                $pdf->Ln(3);
                $pdf->SetX($init_x + 5);
                $pdf->SetFont('Calibri', '', 8);
                $pdf->cell(33.5, 5.5, utf8_decode("** TOTAL DEUDA(S) PENDIENTE(S): S/.{$total_debt_formatted}"), 0, "", "L");

                $pdf->Ln(3);
                $pdf->SetX($init_x + 5);
                $pdf->SetFont('Calibri', '', 8);
                $pdf->cell(33.5, 5.5, utf8_decode("AÑO: {$order->year} DEUDA: {$total_debt_formatted} SOLES"), 0, "", "L");

                $pdf->Output();


	}

	public function report_payments_done($payment_id)
	{

		$today = Carbon::now();
		$year = $today->format('Y');
		$today_formatted = $today->format('d/m/Y H:i:s');

		$nivel_arr_values = array(1 => "PRIMARIA", 2 => "SECUNDARIA");
		$grade_arr_values = array(1 => "1ro", 2 => "2do", 3 => "3ro", 4 => "4to", 5 => "5to", 6 => "6to");

		//$tupa = Tupa::all();
		$tupa = DB::table('tupa')
			->where('deleted_at', NULL)
			->get();

		$order_types = DB::table('order_types')
			->where('deleted_at', NULL)
			->get();

		$payment = Payment::with('details.office')
			->find($payment_id);

		$order = Order::with('entity.profession')
			->with('debt_details')
			->find($payment->order_id);

		$total_debt_ = 0;
		foreach ($order->debt_details as $ddx => $detail) {
			$total_debt_ += (float)$detail->observations;
		}

			 	$pdf = app('Fpdf');
            	$pdf->AddPage("L", "A5");

            	$pdf->AddFont('Calibri','','calibri.php');
                $pdf->AddFont('Calibri-Bold','','calibrib.php');
                $pdf->AddFont('Times-Bold','','timesb.php');

                //$pdf->AddFont('Calibri-Italic','','calibri_i.php');
                //$pdf->AddFont('Calibri-BoldItalic','','calibri_bi.php');
                $normal_space = 4.5;
                $footer_font_size = 7;
                $norma_font_size = 8;
                $font_size_11 = 9;
                $font_size_12 = 12;
                $font_size_13 = 13;
                $font_size_14 = 14;

                $margen_x = 5;
                $margen_start_second_head = 110;
                $total_width_allowed = 105;
                $total_width_allowed_no_margin = 95;
                //zamuro
                $pdf->Image(asset('assets/cabeceras/champagnat_escudo.jpg'), $margen_x , 5, 10);

                $pdf->Ln();
            	
                $pdf->SetY(10);
                $pdf->SetX(33);
                //A4
                //anchura go from 0 to 297 / 2 = 148.5
                //80 68
                //10 - 128  - 10

                //A5
                //210 - 105 105  5 95 5

                $pdf->SetFont('Calibri', '', 13);
                $pdf->SetLineWidth(0.5);
                //$pdf->SetX(15);
                //$pdf->Multicell(3, 18, "", "L", "C");

                $pdf->SetLineWidth(0.2);

                $pdf->SetY(6);
                $pdf->SetX(15);
                $pdf->SetFont('Calibri-Bold', '', 7);
                $pdf->Cell(42, 5, utf8_decode("APAFA {$year}"), 0, "", "C");
                $pdf->Ln(2.5);

                $y = $pdf->GetY();

                $pdf->SetX(15);
                $pdf->SetFont('Calibri', '', 7);
                $pdf->Cell(42, 5, utf8_decode('INSTITUCIÓN EDUCATIVA'), 0, "", "C");
                $pdf->Ln(2.5);

                $pdf->SetX(15);
                $pdf->SetFont('Calibri', '', 7);
                $pdf->Cell(42, 5, utf8_decode("CHAMPAGNAT"), 0, "", "C");
                $pdf->Ln();

 				$pdf->SetY(6);
                $pdf->SetX(57);
                $pdf->SetFont('Calibri-Bold', '', 7);
                $pdf->Cell(42, 5, utf8_decode("Nro. : {$payment->code}"), 0, "", "C");
                $pdf->Ln(2.5);

                $pdf->SetX(57);
                $pdf->SetFont('Calibri', '', 7);
                $pdf->Cell(42, 5, utf8_decode($today_formatted), 0, "", "C");
                $pdf->Ln(2.5);

                $pdf->SetX(57);
                $pdf->SetFont('Calibri-Bold', '', 6);
                $pdf->Cell(42, 5, utf8_decode("Fecha de impresión"), 0, "", "C");

                $pdf->SetY(16);
                $pdf->SetX(5);
                $pdf->SetFont('Calibri-Bold', '', 15);
                $pdf->Cell(95, 8, utf8_decode('PAGOS DE SERVICIOS'), 0, "", "C");

                $pdf->Ln();
                //$pdf->Ln(1);

                $pdf->SetX(5);
                $pdf->Cell(95, 8, utf8_decode(""), "T", "", "C");

               	$pdf->Ln(1);
                $pdf->SetX(5);

                $pdf->SetFont('Calibri-Bold', '', $norma_font_size);
                $pdf->cell(24, 5.5, utf8_decode("GIRO/RUBRO: "), 0, "", "L");
                $pdf->SetFont('Calibri', '', $norma_font_size);
                $pdf->cell(0, 5.5, utf8_decode("COLEGIOS"), 0, "", "L");

                //$pdf->SetY(52);

                $pdf->Ln(4.5);
                //$pdf->Ln(0);
                $pdf->SetX(5);

                //$pdf->SetX(0);
                $pdf->SetFont('Times-Bold', '', 13);
                $pdf->Cell(95, 8, utf8_decode('I.E. CHAMPAGNAT-TACNA'), 0, "", "C");

                $pdf->Ln(7);
                //$pdf->Ln(0);
                $pdf->SetX(5);

                $pdf->SetFont('Calibri-Bold', '', $norma_font_size);
                $pdf->cell(24, 5.5, utf8_decode("ID ALUMNO: "), 0, "", "L");
                $pdf->SetFont('Calibri', '', $norma_font_size);
                $pdf->cell(0, 5.5, utf8_decode($order->entity->identity_document), 0, "", "L");
                $pdf->Ln(4.5);
                $pdf->SetX(5);

                $pdf->SetFont('Calibri-Bold', '', $norma_font_size);
                $pdf->cell(24, 5.5, utf8_decode("NOMBRES: "), 0, "", "L");
                $pdf->SetFont('Calibri', '', $norma_font_size);
                $pdf->cell(0, 5.5, utf8_decode($order->entity->paternal_surname." ".$order->entity->maternal_surname.", ".$order->entity->name), 0, "", "L");


                $pdf->Ln(4.5);
                $pdf->SetX(5);
                $pdf->SetFont('Calibri-Bold', '', $norma_font_size);
                $pdf->cell(26, 5.5, utf8_decode("MATRICULADO: "), 0, "", "L");
                $pdf->SetFont('Calibri', '', $norma_font_size);

                $nivel = $nivel_arr_values[$order->order_type_id];
                $grade = $grade_arr_values[$order->tupa_id];

                $pdf->cell(0, 5.5, utf8_decode("{$grade} Grado {$order->subject} DE {$nivel} {$order->year}"), 0, "", "L");

                //$pdf->SetY(56);
                $pdf->Ln(5);

				//$pdf->SetX(15);
				$y = $pdf->GetY();
                $pdf->SetX(5);

                $pdf->SetFont('Calibri-Bold', '', $font_size_11);

                $pdf->Multicell(20, 5, utf8_decode("AÑO"), "LTRB", "C");

                $pdf->SetY($y);
                $pdf->SetX(25);
                $pdf->Multicell(55, 5, utf8_decode("DESCRIPCIÓN"), "TRB", "L");

                $pdf->SetY($y);
                $pdf->SetX(80);
                $pdf->Multicell(20, 5, utf8_decode("Sub Total"), "TRB", "C");

	                //$pdf->Ln(0);

               	$total_debt = 0;

                foreach ($payment->details as $key => $detail) {
	 				$total_debt += (float)$detail->observations;

	 				$pdf->Ln(0);
               	
	                $y = $pdf->GetY();
	                $pdf->SetFont('Calibri', '', $norma_font_size);
	                $pdf->SetX(5);
	                $pdf->Multicell(20, 5, utf8_decode($order->year), "
	                    LRB", "C");

	                $pdf->SetY($y);
	                $pdf->SetX(25);
	                $pdf->Multicell(55, 5, utf8_decode("MENSUALIDAD ". strtoupper($detail->office->name)), "RB", "L");

	                $pdf->SetY($y);
	                $pdf->SetX(80);
	                //$pdf->SetFontSize(16);
	                $pdf->Multicell(20, 5, utf8_decode(number_format($detail->observations, 2, ',','')), "LRB", "R");

                }

                $pdf->Ln(0);
	            $y = $pdf->GetY();
               
	            $pdf->SetX(55);
                $pdf->Multicell(25, 5, utf8_decode("TOTAL :"), 0, "C");

	            $pdf->SetY($y);
                $pdf->SetX(80);
                $pdf->SetFontSize(13);
                $pdf->Multicell(20, 5, utf8_decode(number_format($total_debt, 2, ',','')), "LRB", "R");

                $total_debt_formatted = number_format($total_debt_, 2, ',','');

                $pdf->Ln(3);
                $pdf->SetX(5);
                $pdf->SetFont('Calibri', '', $footer_font_size);
                $pdf->cell(33.5, 5.5, utf8_decode("** TOTAL DEUDA(S) PENDIENTE(S): S/.{$total_debt_formatted}"), 0, "", "L");

                $pdf->Ln(3);
                $pdf->SetX(5);
                $pdf->SetFont('Calibri', '', $footer_font_size);
                $pdf->cell(33.5, 5.5, utf8_decode("AÑO: {$order->year} DEUDA: {$total_debt_formatted} SOLES"), 0, "", "L");


                ////-----------------------------------------------------------------
                $init_x = 105;
                $pdf->Image(asset('assets/cabeceras/champagnat_escudo.jpg'), $init_x + 5 , 5, 10);
                $pdf->Ln();
                //$pdf->SetY(10);
                //A4
                //anchura go from 0 to 297 / 2 = 148.5
                //80 68
                //10 - 128  - 10

                //A5
                //210 - 105 105  5 95 5

                $pdf->SetFont('Calibri', '', 13);
                //$pdf->SetLineWidth(0.5);
                //$pdf->SetX(15);
                //$pdf->Multicell(3, 18, "", "L", "C");

                //$pdf->SetLineWidth(0.2);

                $pdf->SetY(6);
                $pdf->SetX($init_x + 15);
                $pdf->SetFont('Calibri-Bold', '', 7);
                $pdf->Cell(42, 5, utf8_decode("APAFA {$year}"), 0, "", "C");
                $pdf->Ln(2.5);

                $y = $pdf->GetY();

                $pdf->SetX($init_x + 15);
                $pdf->SetFont('Calibri', '', 7);
                $pdf->Cell(42, 5, utf8_decode('INSTITUCIÓN EDUCATIVA'), 0, "", "C");
                $pdf->Ln(2.5);

                $pdf->SetX($init_x + 15);
                $pdf->SetFont('Calibri', '', 7);
                $pdf->Cell(42, 5, utf8_decode("CHAMPAGNAT"), 0, "", "C");
                $pdf->Ln();

 				$pdf->SetY(6);
                $pdf->SetX($init_x + 57);
                $pdf->SetFont('Calibri-Bold', '', 7);
                $pdf->Cell(42, 5, utf8_decode("Nro. : {$payment->code}"), 0, "", "C");
                $pdf->Ln(2.5);

                $pdf->SetX($init_x + 57);
                $pdf->SetFont('Calibri', '', 7);
                $pdf->Cell(42, 5, utf8_decode($today_formatted), 0, "", "C");
                $pdf->Ln(2.5);

                $pdf->SetX($init_x +57);
                $pdf->SetFont('Calibri-Bold', '', 6);
                $pdf->Cell(42, 5, utf8_decode("Fecha de impresión"), 0, "", "C");

                $pdf->SetY(16);
                $pdf->SetX($init_x + 5);
                $pdf->SetFont('Calibri-Bold', '', 15);
                $pdf->Cell(95, 8, utf8_decode('PAGOS DE SERVICIOS'), 0, "", "C");
                $pdf->Ln();
                //$pdf->Ln(1);
                $pdf->SetX($init_x + 5);
                $pdf->Cell(95, 8, utf8_decode(""), "T", "", "C");

               	$pdf->Ln(1);
                $pdf->SetX($init_x + 5);

                $pdf->SetFont('Calibri-Bold', '', $norma_font_size);
                $pdf->cell(24, 5.5, utf8_decode("GIRO/RUBRO: "), 0, "", "L");
                $pdf->SetFont('Calibri', '', $norma_font_size);
                $pdf->cell(0, 5.5, utf8_decode("COLEGIOS"), 0, "", "L");

                //$pdf->SetY(52);

                $pdf->Ln(4.5);
                //$pdf->Ln(0);
                $pdf->SetX($init_x + 5);

                //$pdf->SetX(0);
                $pdf->SetFont('Times-Bold', '', 13);
                $pdf->Cell(95, 8, utf8_decode('I.E. CHAMPAGNAT-TACNA'), 0, "", "C");

                $pdf->Ln(7);
                //$pdf->Ln(0);
                $pdf->SetX($init_x + 5);

                $pdf->SetFont('Calibri-Bold', '', $norma_font_size);
                $pdf->cell(24, 5.5, utf8_decode("ID ALUMNO: "), 0, "", "L");
                $pdf->SetFont('Calibri', '', $norma_font_size);
                $pdf->cell(0, 5.5, utf8_decode($order->entity->identity_document), 0, "", "L");
                $pdf->Ln(4.5);
                $pdf->SetX($init_x + 5);

                $pdf->SetFont('Calibri-Bold', '', $norma_font_size);
                $pdf->cell(24, 5.5, utf8_decode("NOMBRES: "), 0, "", "L");
                $pdf->SetFont('Calibri', '', $norma_font_size);
                $pdf->cell(0, 5.5, utf8_decode($order->entity->paternal_surname." ".$order->entity->maternal_surname.", ".$order->entity->name), 0, "", "L");


                $pdf->Ln(4.5);
                $pdf->SetX($init_x + 5);
                $pdf->SetFont('Calibri-Bold', '', $norma_font_size);
                $pdf->cell(26, 5.5, utf8_decode("MATRICULADO: "), 0, "", "L");
                $pdf->SetFont('Calibri', '', $norma_font_size);

                $nivel = $nivel_arr_values[$order->order_type_id];
                $grade = $grade_arr_values[$order->tupa_id];

                $pdf->cell(0, 5.5, utf8_decode("{$grade} Grado {$order->subject} DE {$nivel} {$order->year}"), 0, "", "L");

                //$pdf->SetY(56);
                $pdf->Ln(5);

				//$pdf->SetX(15);
				$y = $pdf->GetY();
                $pdf->SetX($init_x + 5);

                $pdf->SetFont('Calibri-Bold', '', $font_size_11);

                $pdf->Multicell(20, 5, utf8_decode("AÑO"), "LTRB", "C");

                $pdf->SetY($y);
                $pdf->SetX($init_x + 25);
                $pdf->Multicell(55, 5, utf8_decode("DESCRIPCIÓN"), "TRB", "L");

                $pdf->SetY($y);
                $pdf->SetX($init_x + 80);
                $pdf->Multicell(20, 5, utf8_decode("Sub Total"), "TRB", "C");

	                //$pdf->Ln(0);

               	$total_debt = 0;

                foreach ($payment->details as $key => $detail) {
	 				$total_debt += (float)$detail->observations;

	 				$pdf->Ln(0);
               	
	                $y = $pdf->GetY();
	                $pdf->SetFont('Calibri', '', $norma_font_size);
	                $pdf->SetX($init_x + 5);
	                $pdf->Multicell(20, 5, utf8_decode($order->year), "
	                    LRB", "C");

	                $pdf->SetY($y);
	                $pdf->SetX($init_x + 25);
	                $pdf->Multicell(55, 5, utf8_decode("MENSUALIDAD ". strtoupper($detail->office->name)), "RB", "L");

	                $pdf->SetY($y);
	                $pdf->SetX($init_x + 80);
	                //$pdf->SetFontSize(16);
	                $pdf->Multicell(20, 5, utf8_decode(number_format($detail->observations, 2, ',','')), "LRB", "R");

                }

                $pdf->Ln(0);
	            $y = $pdf->GetY();
               
	            $pdf->SetX($init_x + 55);
                $pdf->Multicell(25, 5, utf8_decode("TOTAL :"), 0, "C");

	            $pdf->SetY($y);
                $pdf->SetX($init_x + 80);
                $pdf->SetFontSize(13);
                $pdf->Multicell(20, 5, utf8_decode(number_format($total_debt, 2, ',','')), "LRB", "R");

                $total_debt_formatted = number_format($total_debt_, 2, ',','');

                $pdf->Ln(3);
                $pdf->SetX($init_x + 5);
                $pdf->SetFont('Calibri', '', $footer_font_size);
                $pdf->cell(33.5, 5.5, utf8_decode("** TOTAL DEUDA(S) PENDIENTE(S): S/.{$total_debt_formatted}"), 0, "", "L");

                $pdf->Ln(3);
                $pdf->SetX($init_x + 5);
                $pdf->SetFont('Calibri', '', $footer_font_size);
                $pdf->cell(33.5, 5.5, utf8_decode("AÑO: {$order->year} DEUDA: {$total_debt_formatted} SOLES"), 0, "", "L");



                $pdf->Output();


	}

	public function getNumberFormatted($value)
	{
		$quantity_of_digits = 8;

		$length_value = strlen($value);

		$missing_zeros = $quantity_of_digits - $length_value;

		return str_repeat("0", $missing_zeros).$value;

	}


	public function create() {

		$entities = Entity::whereType(2)
			->get();

		$offices = Office::all();
		return view("almacen.office.create", compact('entities', 'offices'));
	}
	public function store(OfficeFormRequest $request) {
		$data = $request->all();

		$office = new Office;
		$office->fill($data);
		$office->save();
		return Redirect::to('admin/oficinas');
	}

	// public function show($id) {
	// 	return view("almacen.categoria.show", ["categoria" => Categoria::findOrFail($id)]);
	// }

	public function edit($id) {

		$entities = Entity::whereType(2)
			->get();

		$offices = Office::all();
		return view("almacen.office.edit", ["office" => Office::findOrFail($id), "entities" => $entities, "offices" => $offices]);
	}

	public function update(LoggedSolicitudeUpdateRequest $request, $id) {

		$order = Order::findOrFail($id);

		$order->reference = $order->internal_code;
		$order->save();

		//$order->document_type_id = $request->document_type_id;

		if ($request->internal_code) {
			$order->internal_code = $request->internal_code;
		}

		$order->tupa_id = $request->tupa_id;
		$order->subject = $request->subject;
		//$order->reference = $request->reference;
		$order->notes = $request->observations;
		$order->term = $request->term;
		$order->order_type_id = $request->order_type_id;
		$order->folios = $request->folios;

		$unique_string = time().time();

		if (Input::hasFile('attached_file')) {
			$file = Input::file('attached_file');
			$file->move(public_path() . '/archivos/tramites/', $unique_string.$file->getClientOriginalName());
			$path = '/archivos/tramites/'.$unique_string.$file->getClientOriginalName();
			$order->attached_file = $path;
		}
		
		$order->save();
		
		return response()->json(['title' => 'Operación Exitosa', 'message' => 'Se ha actualizado correctamente la solicitud', 'id' => $order->id], 201);

	}

	public function destroy($id) {
		$office = Office::findOrFail($id);
		$office->delete();
		return Redirect::to('admin/oficinas');
	}

	public function delete_solicitude($id) {

		$now = Carbon::now()->format('Y-m-d H:i:s');
		$payment = Payment::find($id);

		DB::table('details_order')
			->where('payment_id', $id)
			->update(['status' => 0]);
		$payment->delete();

		return ['success' => true, 'title' => 'Operación Exitosa','message' => "Se ha eliminado el pago."];

	}

	public function delete_root_answer($id, $now)
	{

		$order_order = DB::table('order_order')
				->where('order_id', $id)
				->where('parent_order_id', 0)
				->get();

		if (!empty($order_order)) {
			//Es el padre que tiene o no respuestas. || es un documento simple que se deriva
			$last_order_id = $order_order[0]->last_order_id;

			$orders_related = DB::table('order_order')
				->where('last_order_id', $last_order_id)
				->get();

			foreach ($orders_related as $key => $order_related) {

				DB::table('details_order')
					->where('order_id', $order_related->order_id)
					->update(['deleted_at' => $now]);

				DB::table('orders')
					->where('id', $order_related->order_id)
					->update(['deleted_at' => $now]);
			}

			DB::table('order_order')
				->where('last_order_id', $last_order_id)
				->update(['deleted_at' => $now]);

			return ['success' => true];

		} else {
			//no es padre de todas las respuestas.
			$last_order_answer = DB::table('order_order')
				->where('last_order_id', $id)
				->where('order_id', $id)
				->get();

			if (!empty($last_order_answer)) {
				// es la ultima respuesta
				DB::table('details_order')
					->where('order_id', $id)
					->update(['deleted_at' => $now]);

				DB::table('orders')
					->where('id', $id)
					->update(['deleted_at' => $now]);

				DB::table('order_order')
					->where('id', $last_order_answer[0]->id)
					->update(['deleted_at' => $now]);



				DB::table('order_multiple_document')
					->where('order_id', $id)
					->update(['deleted_at' => $now]);

				//updating the preview order;
				$children = DB::table('order_multiple_document')
					->where('parent_order_id', $last_order_answer[0]->parent_order_id)
					->where('deleted_at', NULL)
					->get();

				if ($children) {
					DB::table('order_order')
						->where('last_order_id', $id)
						->where('deleted_at', NULL)
						->update(['deleted_at' => $now]);
				} else {

				DB::table('order_order')
					->where('last_order_id', $id)
					->where('deleted_at', NULL)
					->update(['last_order_id' => $last_order_answer[0]->parent_order_id]);

					DB::table('details_order')
						->where('order_id', $last_order_answer[0]->parent_order_id)
						->where('status', 7)
						->update(['deleted_at' => $now]);

					$last_order_detail = DB::table('details_order')
						->where('order_id', $last_order_answer[0]->parent_order_id)
						->where('deleted_at', NULL)
						->orderBy('id', 'DESC')
						->get();

					DB::table('details_order')
						->where('order_id', $last_order_answer[0]->parent_order_id)
						->where('status', $last_order_detail[0]->status)
						->update(['last' => true]);

					DB::table('orders')
						->where('id', $last_order_answer[0]->parent_order_id)
						->update(['status' => $last_order_detail[0]->status, 'office_id' => $last_order_detail[0]->office_id]);

				}

				

			} else {
				return ['success' => false, 'message' => "No se puede  eliminar por que  es una respuesta intermedia.", 'title' => 'Advertencia'];
			}
		}

		return ['success' => true];
	}

	public function answer_cc(Request $request)
	{
		$order = Order::find($request->order_id);
		$order->status = 8;
		$order->save();

		$observations = $request->observations;

		DB::table('details_order')
			->where('order_id', $order->id)
			->update(['last' => false]);

		$new_detail_order = new DetailOrder();
		$new_detail_order->office_id_origen = $order->office_id;
		$new_detail_order->order_id = $order->id;
		$new_detail_order->status = 8;
		$new_detail_order->office_id = $order->office_id;
		$new_detail_order->observations = $observations;
		$new_detail_order->last = true;
		$new_detail_order->user_id = Auth::user()->id;
		$new_detail_order->save();

		return response()->json(['title' => 'Operación Exitosa', 'message' => 'Se ha respondido la solicitud/CC.'], 200);

	}

	public function update_status(Request $request) {

		$order = Order::find($request->order_id);
		$office_id_origen = $order->office_id;
		$last = true;
		$user_id = Auth::user()->id;

		if ($order->status == 3 && $request->status == 2) {
			if ($order->office_id == $request->office_id) {
				return response()->json(['title' => 'Advertencia', 'message' => "El documento con código ".$order->code." no puede derivarse a la misma oficina donde ya está"], 400);
			}
		}

		$order->status = $request->status;
		$order->save();

		if ($request->status == 2) {
			##derivada
			$last = false;

			$offices_arr = [];

			if ($request->offices_arr != "" && $request->offices_arr != "null") {
				$offices_arr = explode(',', $request->offices_arr);
			}

			$order->office_id = $request->office_id;
			$order->save();
			// $second_order_main = $order->replicate();
			// $second_order_main->office_id = $office_main_id_to_send;
			// $second_order_main->status = 1;
			// $second_order_main->parent_order_id = $order->id;
			// $second_order_main->save();

			// $new_detail_order = new DetailOrder();
			// $new_detail_order->order_id = $second_order_main->id;
			// $new_detail_order->status = $second_order_main->status;
			// $new_detail_order->office_id = $second_order_main->office_id;
			// $new_detail_order->observations = $request->observations;
			// $new_detail_order->save();

			foreach ($offices_arr as $key => $office_id) {
				if ($office_id != $order->office_id) {
					$other_order = $order->replicate();
					$other_order->code = $order->code."--COPY".$office_id;
					$other_order->office_id = $office_id;
					$other_order->status = 5;
					$other_order->parent_order_id = $order->id;
					$other_order->save();

					$new_detail_order = new DetailOrder();
					$new_detail_order->office_id_origen = $office_id_origen;
					$new_detail_order->order_id = $other_order->id;
					$new_detail_order->status = 5;
					$new_detail_order->office_id = $other_order->office_id;
					$new_detail_order->observations = $request->observations;
					$new_detail_order->last = true;
					$new_detail_order->user_id = $user_id;
					$new_detail_order->save();
				}
			}

			//return response()->json(['title' => 'Operación Exitosa', 'message' => 'Se ha derivado la solicitud a las respectivas oficinas.'], 200);

		}

		DB::table('details_order')
			->where('order_id', $order->id)
			->update(['last' => false]);

		$new_detail_order = new DetailOrder();
		$new_detail_order->order_id = $order->id;
		$new_detail_order->status = $request->status;
		$new_detail_order->office_id_origen = $office_id_origen;
		$new_detail_order->office_id = $order->office_id;
		$new_detail_order->observations = $request->observations;
		$new_detail_order->last = $last;
		$new_detail_order->user_id = $user_id;
		$new_detail_order->save();


		if (Input::hasFile('attached_file')) {
			$file = Input::file('attached_file');

		    //Storage::disk('google')->put($file->getClientOriginalName(), fopen($file, 'r+'));
            //$url = Storage::disk('google')->url($file->getClientOriginalName());
			$unique_string = time().time();

			$file->move(public_path() . '/archivos/tramites/', $unique_string.$file->getClientOriginalName());
			$path = '/archivos/tramites/'.$unique_string.$file->getClientOriginalName();
			$new_detail_order->attached_file = $path;
			$new_detail_order->save();
			//$new_order->attached_file = $path;
		}

		if ($request->status == 2) {
			$new_detail_order2 = new DetailOrder();
			$new_detail_order2->order_id = $order->id;
			$new_detail_order2->status = 1;
			$new_detail_order2->office_id_origen = $office_id_origen;
			$new_detail_order2->office_id = $order->office_id;
			$new_detail_order2->observations = $request->observations;
			$new_detail_order2->attached_file = $new_detail_order->attached_file;
			$new_detail_order2->last = true;
			$new_detail_order2->user_id = $user_id;
			$new_detail_order2->save();
			$order->status = 1;
			$order->save();
		}

		//if ($request->status == 4) {

		//}

		return response()->json(['title' => 'Operación Exitosa', 'message' => 'Se ha recibido la solicitud.'], 200);
	}

	public function report(Request $request) {
		$order = Order::with('document_type')
			->with('entity')
			->with('office')
			->with(['details' => function ($query) {
				$query->with('state')
					->with('office');
			}])->find($request->solicitude_id);

		$company = Company::first();

		$pdf = new Fpdf();
		$pdf::AddPage();
		//$pdf::SetTextColor(35, 56, 113);
		$pdf::SetTextColor(0, 0, 0); // Establece el color del texto

		$pdf::SetFont('Arial', '', 8);
		$pdf::Write(5, utf8_decode($company->name));
		$pdf::Ln();
		$pdf::SetFont('Arial', 'B', 14);
		$pdf::Cell(0, 10, utf8_decode("Hoja de Ruta"), 0, "", "C");
		$pdf::Ln();
		$pdf::Ln();
		$pdf::SetFont('Arial', 'B', 11);
		$pdf::Write(5, utf8_decode("Código: "));
		$pdf::SetFont('Arial', '', 11);
		$pdf::Write(5, $order->code);
		$pdf::Ln();
		$pdf::SetFont('Arial', 'B', 11);
		$pdf::Write(5, utf8_decode("Tipo de documento: "));
		$pdf::SetFont('Arial', '', 11);
		$pdf::Write(5, $order->document_type->name);
		// $pdf::Cell(0, 10, utf8_decode("Tipo de documento: " . $order->document_type->name), 0, "", "L");
		$pdf::Ln();
		$pdf::SetFont('Arial', 'B', 11);
		$pdf::Write(5, utf8_decode("Emisor: "));
		$pdf::SetFont('Arial', '', 11);
		$pdf::Write(5, "{$order->entity->name} {$order->entity->paternal_surname} {$order->entity->maternal_surname}");

		$pdf::Ln();
		$pdf::SetFont('Arial', 'B', 11);
		$pdf::Write(5, utf8_decode("Asunto: "));
		$pdf::SetFont('Arial', '', 11);
		$pdf::Write(5, $order->subject);

		$pdf::Ln();
		$pdf::SetFont('Arial', 'B', 11);
		$pdf::Write(5, utf8_decode("Fecha de registro: "));
		$pdf::SetFont('Arial', '', 11);
		$pdf::Write(5, $order->created_at->format('d/m/Y H:i'));

		$pdf::Ln();
		$pdf::Ln();

		$pdf::SetFont('Arial', 'B', 11);
		$pdf::Write(5, utf8_decode("Oficina actual: "));
		$pdf::SetFont('Arial', '', 11);
		$pdf::Write(5, $order->office->name);

		$pdf::Ln();
		$pdf::Ln();

		//El ancho de las columnas debe de sumar promedio 190

		$pdf::SetFont('Arial', 'B', 11);
		$pdf::SetTextColor(0, 0, 0); // Establece el color del texto
		$pdf::SetFillColor(255, 255, 255); // establece el color del fondo de la celda

		$pdf::cell(10, 8, utf8_decode("#"), 1, "", "L", true);
		$pdf::cell(57, 8, utf8_decode("Estado / Fecha"), 1, "", "L", true);
		$pdf::cell(54, 8, utf8_decode("Oficina"), 1, "", "L", true);
		$pdf::cell(67, 8, utf8_decode("Observación"), 1, "", "L", true);

		$pdf::Ln();
		$pdf::SetTextColor(0, 0, 0); // Establece el color del texto
		$pdf::SetFillColor(255, 255, 255); // establece el color del fondo de la celda
		$pdf::SetFont("Arial", "", 9);

		foreach ($order->details as $key => $detail) {
			$pdf::cell(10, 6, $key + 1, 1, "", "L", true);
			$pdf::cell(57, 6, utf8_decode($detail->state->name . " - " . $detail->created_at->format('d/m/Y')), 1, "", "L", true);
			$pdf::cell(54, 6, utf8_decode($detail->office->name), 1, "", "L", true);
			$pdf::cell(67, 6, utf8_decode($detail->observations), 1, "", "L", true);
			$pdf::Ln();
		}

		$pdf::Output();
		exit;

		// $pdf::SetTextColor(0, 0, 0); // Establece el color del texto
		// $pdf::SetFillColor(206, 246, 245); // establece el color del fondo de la celda
		// $pdf::SetFont('Arial', 'B', 10);
		// //El ancho de las columnas debe de sumar promedio 190
		// $pdf::cell(50, 8, utf8_decode("Nombre"), 1, "", "L", true);
		// $pdf::cell(140, 8, utf8_decode("Descripción"), 1, "", "L", true);

		// $pdf::Ln();
		// $pdf::SetTextColor(0, 0, 0); // Establece el color del texto
		// $pdf::SetFillColor(255, 255, 255); // establece el color del fondo de la celda
		// $pdf::SetFont("Arial", "", 9);

		// foreach ($registros as $reg) {
		// 	$pdf::cell(50, 6, utf8_decode($reg->nombre), 1, "", "L", true);
		// 	$pdf::cell(140, 6, utf8_decode($reg->descripcion), 1, "", "L", true);
		// 	$pdf::Ln();
		// }
		$pdf::Output();
		exit;
	}

	public function details_document_view(Request $request)
	{	
		$products = [];
		$total = 0;

		$user = User::with('entity')->find(Auth::user()->id);
		$office_id = $user->entity->office_id;
		$role_id = $user->role_id;

		// $orders = Order::where('id', $request->solicitude_id)
		// 	->with('document_type')
		// 	->with('entity')
		// 	->with('office')
		// 	->with(['details' => function ($query) {
		// 		$query->with('state')
		// 			->with('office.entity');
		// 	}])
		// 	->orderBy('created_at', 'DESC')
		// 	->get();

		$search_button = false;
		//if (!count($orders)) {
		$orders = Order::whereCode($request->solicitude_id)
				//->where('multiple', false)
				->with('document_type')
				->with('entity')
				->with('office')
				// ->whereHas('details', function ($query) use ($office_id) {
				// 	$query->where('office_id_origen', $office_id);
				// })
				->with(['details' => function ($query) {
					$query->with('state')
						->with('office.entity')
						->with('office_origen');
				}])
				->orderBy('created_at', 'DESC')
				->get();

			$orders_ = DB::table('orders')
				->join('details_order', 'orders.id', '=', 'details_order.order_id')
				->where('orders.deleted_at', null)
				->where('orders.code', $request->solicitude_id)
				->where('details_order.office_id_origen', $office_id)
				->get();

		$orders_related = [];
		$orders_from_multiple = [];

		if (empty($orders_)) {

			if (count($orders)) {
				//exist
				if ($role_id == 1) {
					$orders = [];

					return view('store.checkout.solicitude_detail', compact('orders', 'search_button', 'orders_related', 'orders_from_multiple'));
				}
			} else {
				return view('store.checkout.solicitude_detail', compact('orders', 'search_button', 'orders_related', 'orders_from_multiple'));
			}
		}

		$document_type = DocumentType::find($orders[0]->document_type_id);
		
		$orders_from_multiple = OrderMultipleDocument::whereParentOrderId($orders[0]->id)
			->where('deleted_at', NULL)
			->with(['order' => function($query){
				$query->with('office');
				$query->where('deleted_at', NULL);
				$query->with(['details' => function($query){
					$query->with('user.entity')
						->with('state')
						->with('office_origen');
					$query->with('office')
						->where('deleted_at', NULL);
				}]);
			}])
			->get();

		if (count($orders_from_multiple)) {
			return view('store.checkout.solicitude_detail', compact('orders', 'search_button', 'orders_related', 'orders_from_multiple'));
		}

		// if ($document_type->is_multiple && $orders[0]->multiple == 0) {
		// 	$orders_from_multiple = OrderMultipleDocument::whereParentOrderId($orders[0]->id)
		// 		->where('deleted_at', NULL)
		// 		->with(['order' => function($query){
		// 			$query->with('office');
		// 			$query->where('deleted_at', NULL);
		// 			$query->with(['details' => function($query){
		// 				$query->with('user.entity')
		// 					->with('state')
		// 					->with('office_origen');
		// 				$query->with('office')
		// 					->where('deleted_at', NULL);
		// 			}]);
		// 		}])
		// 		->get();
		// } else {
			$last_order = OrderOrder::whereOrderId($orders[0]->id)
				->get();

			$last_order_id = $last_order[0]->last_order_id;

			$orders = Order::where('id', $last_order_id)
				->with('document_type')
				->with('entity.office')
				->with('office')
				->with(['details' => function ($query) {
					$query->with('state')
						->with('office.entity');
				}])
				->orderBy('created_at', 'DESC')
				->get();

			$orders_related = OrderOrder::whereLastOrderId($last_order_id)
				->where('parent_order_id', '!=', 0)
				->with(['parent_order' => function($query){
					$query->with(['details' => function($query){
						$query->with('office')
							->with('office_origen')
							->with('state');
						$query->with('user.entity');
					}])
					->with(['children' => function($query){
						$query->with('office');
						$query->with(['details' => function($query){
							$query->with('user.entity')
								->with('state')
								->with('office_origen');
								$query->with('office');
						}]);
					}])
					->with('entity')
						->with('tupa');
				}])
				->orderBy('id', 'DESC')
				->get();
		//}

		return view('store.checkout.solicitude_detail', compact('orders', 'search_button', 'orders_related', 'orders_from_multiple'));
	}

	public function my_solicitude_sent_view(Request $request)
	{
		if ($request) {

			$start_date = "";

			if ($request->has('inicio')) {
				$start_date = Carbon::createFromFormat('d/m/Y', $request->inicio);
			}

			$end_date = "";

			if ($request->has('fin')) {
				$end_date = Carbon::createFromFormat('d/m/Y', $request->fin);
			}

			// $user = User::with('entity')->find(Auth::user()->id);
			// $entity = Entity::find($user->entity_id);

			// $office_id = $user->entity->office_id;

			// $offices = Office::where('id', '!=', $office_id)
			// 	->get();

			$text = trim($request->get('searchText'));
			// $document_status = 1;
			
			$orders = DB::table('payments')
				->orderBy('payments.id', 'desc')
				//->join('details_order', 'orders.id', '=', 'details_order.order_id')
				//->join('document_types', 'orders.document_type_id', '=', 'document_types.id')
				->join('entities', 'payments.entity_id', '=', 'entities.id')
				->join('orders', 'payments.order_id', '=', 'orders.id')
				//->leftJoin('offices', 'details_order.office_id', '=', 'offices.id')
				//->join('document_statuses', 'orders.status', '=', 'document_statuses.id')
				//->leftJoin('orders as parent_order', 'orders.parent_order_id', '=', 'parent_order.id')
				//->leftJoin('offices as office_parent', 'parent_order.office_id', '=', 'office_parent.id')
				->where('payments.deleted_at', null)
				->where('orders.status', 1);
				//->where('orders.multiple', 0);
				// ->whereDate('orders.created_at', '>=', $start_date->format('Y-m-d'))
				// ->whereDate('orders.created_at', '<=', $end_date->format('Y-m-d'));
				
				if ($request->has('inicio')) {
					$orders = $orders->whereDate('payments.created_at', '>=', $start_date->format('Y-m-d'))
						->whereDate('payments.created_at', '<=', $end_date->format('Y-m-d'));
				}


				if ($text) {
					$orders = $orders->where(function ($query) use($text) {
	           			$query->where('entities.identity_document', 'LIKE', "%$text%")
	           				->orWhere('entities.name', 'LIKE', "%$text%")
	           				->orWhere('entities.paternal_surname', 'LIKE', "%$text%")
	           				->orWhere('entities.maternal_surname', 'LIKE', "%$text%");
	                		//->orWhere('entities.identity_document', 'LIKE', "%$text%");
	       			});
				}

				// if ($document_status) {
				// 	$orders = $orders->whereIn('details_order.status', [1]);
				// }

			$orders = $orders->select(['payments.id', 'payments.code', 'orders.subject as subject', 'entities.name as name', 'entities.paternal_surname as paternal_surname', 'entities.maternal_surname as maternal_surname', 'entities.identity_document as identity_document', 'payments.created_at', 'orders.year', 'payments.total']);


			$start_date = "";
			$end_date = "";
			return view('almacen.solicitude.my_solicitude', ["orders" => $orders->paginate(10), "searchText" => $text, 'start_date' => $start_date, 'end_date' => $end_date]);
		}
	}

	public function payment_list_excel(Request $request)
	{
	if ($request) {

			$start_date = "";

			if ($request->has('inicio')) {
				$start_date = Carbon::createFromFormat('d/m/Y', $request->inicio);
			}

			$end_date = "";

			if ($request->has('fin')) {
				$end_date = Carbon::createFromFormat('d/m/Y', $request->fin);
			}

			$text = trim($request->get('searchText'));

			$orders = DB::table('payments')
				->orderBy('payments.id', 'desc')
				//->join('details_order', 'orders.id', '=', 'details_order.order_id')
				//->join('document_types', 'orders.document_type_id', '=', 'document_types.id')
				->join('entities', 'payments.entity_id', '=', 'entities.id')
				->join('orders', 'payments.order_id', '=', 'orders.id')
				//->leftJoin('offices', 'details_order.office_id', '=', 'offices.id')
				//->join('document_statuses', 'orders.status', '=', 'document_statuses.id')
				//->leftJoin('orders as parent_order', 'orders.parent_order_id', '=', 'parent_order.id')
				//->leftJoin('offices as office_parent', 'parent_order.office_id', '=', 'office_parent.id')
				->where('payments.deleted_at', null);
				//->where('orders.multiple', 0);
				// ->whereDate('orders.created_at', '>=', $start_date->format('Y-m-d'))
				// ->whereDate('orders.created_at', '<=', $end_date->format('Y-m-d'));
				
				if ($request->has('inicio')) {
					$orders = $orders->whereDate('payments.created_at', '>=', $start_date->format('Y-m-d'))
						->whereDate('payments.created_at', '<=', $end_date->format('Y-m-d'));
				}


				if ($text) {
					$orders = $orders->where(function ($query) use($text) {
	           			$query->where('entities.identity_document', 'LIKE', "%$text%")
	           				->orWhere('entities.name', 'LIKE', "%$text%")
	           				->orWhere('entities.paternal_surname', 'LIKE', "%$text%")
	           				->orWhere('entities.maternal_surname', 'LIKE', "%$text%");
	                		//->orWhere('entities.identity_document', 'LIKE', "%$text%");
	       			});
				}

				// if ($document_status) {
				// 	$orders = $orders->whereIn('details_order.status', [1]);
				// }

			$orders = $orders->select(['payments.id', 'payments.code', 'orders.subject as subject', 'entities.name as name', 'entities.paternal_surname as paternal_surname', 'entities.maternal_surname as maternal_surname', 'entities.identity_document as identity_document', 'payments.created_at', 'orders.year', 'payments.total'])
				->get();

	        Excel::create("Reporte de pagos", function($excel) use($orders) {
	            $excel->sheet('data', function($sheet) use($orders) {
	                $sheet->setOrientation('landscape');
	                $sheet->fromArray(['#', 'Código', 'Fecha de creación', 'DNI', 'Estudiante', 'Año', 'Monto pagado']);
	                
	                $sheet->cell('A1:H1', function($cell) {
	                    $cell->setFontSize(13);
	                    $cell->setFontWeight('bold');
	                });

	                foreach ($orders as $rs => $order) {
						$sheet->row($rs+2, [
							$rs+1,
							$order->code,
							Carbon::parse($order->created_at)->format('d/m/Y H:i'),
							$order->identity_document,
							$order->name." ".$order->paternal_surname." ".$order->maternal_surname,
							$order->year,
							"S/.".number_format($order->total, 2, ',', ''),
						]);
	                }
	            });
	        })->export('xls');			

		}

	}

	public function students_registered_view(Request $request)
	{
		if ($request) {

			$start_date = "";

			if ($request->has('inicio')) {
				$start_date = Carbon::createFromFormat('d/m/Y', $request->inicio);
			}

			$end_date = "";

			if ($request->has('fin')) {
				$end_date = Carbon::createFromFormat('d/m/Y', $request->fin);
			}

			$user = User::with('entity')->find(Auth::user()->id);
			$entity = Entity::find($user->entity_id);

			$office_id = $user->entity->office_id;

			$offices = Office::where('id', '!=', $office_id)
				->get();

			$text = trim($request->get('searchText'));
			$document_status = 1;

			$status_searched = $request->status;

			$orders_status_arr = [
				'activos' => 1,
				'anulados' => 2,
				'retirados' => 3,
			];

			$orders = DB::table('orders')
				->orderBy('orders.id', 'desc')
				//->join('details_order', 'orders.id', '=', 'details_order.order_id')
				//->join('document_types', 'orders.document_type_id', '=', 'document_types.id')
				->join('entities', 'orders.entity_id', '=', 'entities.id')
				//->leftJoin('offices', 'details_order.office_id', '=', 'offices.id')
				//->join('document_statuses', 'orders.status', '=', 'document_statuses.id')
				//->leftJoin('orders as parent_order', 'orders.parent_order_id', '=', 'parent_order.id')
				//->leftJoin('offices as office_parent', 'parent_order.office_id', '=', 'office_parent.id')
				->where('orders.deleted_at', null);
				//->where('orders.multiple', 0);
				// ->whereDate('orders.created_at', '>=', $start_date->format('Y-m-d'))
				// ->whereDate('orders.created_at', '<=', $end_date->format('Y-m-d'));
				if ($request->has('inicio')) {
					$orders = $orders->whereDate('orders.created_at', '>=', $start_date->format('Y-m-d'))
						->whereDate('orders.created_at', '<=', $end_date->format('Y-m-d'));
				}

				if ($text) {
					$orders = $orders->where(function ($query) use($text) {
	           			$query->where('entities.identity_document', 'LIKE', "%$text%")
	           				->orWhere('entities.name', 'LIKE', "%$text%")
	           				->orWhere('entities.paternal_surname', 'LIKE', "%$text%")
	           				->orWhere('entities.maternal_surname', 'LIKE', "%$text%");
	                		//->orWhere('entities.identity_document', 'LIKE', "%$text%");
	       			});
				}

				if ($request->status) {
					$status_id = $orders_status_arr[$request->status];
					$orders = $orders->where('orders.status', $status_id);
				}

			$orders = $orders->select(['orders.id', 'orders.subject as subject', 'entities.name', 'entities.paternal_surname', 'entities.maternal_surname', 'entities.identity_document', 'orders.status as status', 'orders.created_at', 'orders.internal_code', 'orders.tupa_id', 'orders.order_type_id', 'entities.cellphone', 'entities.email', 'entities.address', 'orders.code']);
				// ->paginate(20);

			$document_statuses = DocumentState::all();

			$admin = false;

			//$orders = $orders->where('orders.office_id_origen', $office_id)
				//->where('details_order.office_id_origen', $office_id);

			if ($user->role_id == 2) {
				// admin
				return view('almacen.solicitude.students_registered_view', ["orders" => $orders->paginate(10), "searchText" => $text, 'offices' => $offices, 'document_statuses' => $document_statuses, 'document_status' => $document_status, 'admin' => true, 'start_date' => $start_date ? $start_date->format('d/m/Y') : "", 'end_date' => $end_date ? $end_date->format('d/m/Y') : "", 'status_searched' => $status_searched]);

			}

			return view('almacen.solicitude.students_registered_view', ["orders" => $orders->paginate(10), "searchText" => $text, 'offices' => $offices, 'document_statuses' => $document_statuses, 'document_status' => $document_status, 'admin' => false, 'start_date' => $start_date ? $start_date->format('d/m/Y') : "", 'end_date' => $end_date ? $end_date->format('d/m/Y') : "", 'status_searched' => $status_searched]);
		}
	}


	public function students_registered_pdf(Request $request)
	{
		if ($request) {

			$start_date = "";

			if ($request->has('inicio')) {
				$start_date = Carbon::createFromFormat('d/m/Y', $request->inicio);
			}

			$end_date = "";

			if ($request->has('fin')) {
				$end_date = Carbon::createFromFormat('d/m/Y', $request->fin);
			}

			$user = User::with('entity')->find(Auth::user()->id);
			$entity = Entity::find($user->entity_id);

			$office_id = $user->entity->office_id;

			$offices = Office::where('id', '!=', $office_id)
				->get();

			$text = trim($request->get('searchText'));
			$document_status = 1;

			$orders = DB::table('orders')
				->orderBy('orders.id', 'desc')
				//->join('details_order', 'orders.id', '=', 'details_order.order_id')
				//->join('document_types', 'orders.document_type_id', '=', 'document_types.id')
				->join('entities', 'orders.entity_id', '=', 'entities.id')
				//->leftJoin('offices', 'details_order.office_id', '=', 'offices.id')
				//->join('document_statuses', 'orders.status', '=', 'document_statuses.id')
				//->leftJoin('orders as parent_order', 'orders.parent_order_id', '=', 'parent_order.id')
				//->leftJoin('offices as office_parent', 'parent_order.office_id', '=', 'office_parent.id')
				->where('orders.deleted_at', null);
				//->where('orders.multiple', 0);
				// ->whereDate('orders.created_at', '>=', $start_date->format('Y-m-d'))
				// ->whereDate('orders.created_at', '<=', $end_date->format('Y-m-d'));
				if ($request->has('inicio')) {
					$orders = $orders->whereDate('orders.created_at', '>=', $start_date->format('Y-m-d'))
						->whereDate('orders.created_at', '<=', $end_date->format('Y-m-d'));
				}

				if ($text) {
					$orders = $orders->where(function ($query) use($text) {
	           			$query->where('entities.identity_document', 'LIKE', "%$text%")
	           				->orWhere('entities.name', 'LIKE', "%$text%")
	           				->orWhere('entities.paternal_surname', 'LIKE', "%$text%")
	           				->orWhere('entities.maternal_surname', 'LIKE', "%$text%");
	                		//->orWhere('entities.identity_document', 'LIKE', "%$text%");
	       			});
				}

			$orders = $orders->select(['orders.id', 'orders.subject as subject', 'entities.name', 'entities.paternal_surname', 'entities.maternal_surname', 'entities.identity_document', 'orders.status as status', 'orders.created_at', 'orders.internal_code', 'orders.tupa_id', 'orders.order_type_id', 'entities.cellphone', 'entities.email', 'entities.address', 'orders.code'])->get();
				// ->paginate(20);
			
	        Excel::create("Reporte de matrículas", function($excel) use($orders) {
	            $excel->sheet('data', function($sheet) use($orders) {
	                $sheet->setOrientation('landscape');
	                $sheet->fromArray(['#', 'Código', 'Fecha de creación', 'DNI', 'Nombres', 'Apellido paterno', 'Apellido materno']);
	                
	                $sheet->cell('A1:H1', function($cell) {
	                    $cell->setFontSize(13);
	                    $cell->setFontWeight('bold');
	                });

	                foreach ($orders as $rs => $order) {
						$sheet->row($rs+2, [
							$rs+1,
							$order->code,
							Carbon::parse($order->created_at)->format('d/m/Y H:i'),
							$order->identity_document,
							$order->name,
							$order->paternal_surname,
							$order->maternal_surname,
						]);
	                }
	            });
	        })->export('xls');			

		
		}
	}



	public function solicitude_report(Request $request)
	{
		// if ($request->has('inicio')) {
		// 	$start_date = Carbon::createFromFormat('d/m/Y', $request->inicio);
		// }

		// if ($request->has('fin')) {
		// 	$end_date = Carbon::createFromFormat('d/m/Y', $request->fin);
		// }

		// $user = User::with('entity')->find(Auth::user()->id);
		// $entity = Entity::find($user->entity_id);

		// $office_id = $user->entity->office_id;

		// $text = trim($request->get('searchText'));

		// $document_status = $request->status;

		// $orders = DB::table('orders')
		// 	->orderBy('orders.id', 'desc')
		// 	->join('document_types', 'orders.document_type_id', '=', 'document_types.id')
		// 	->join('entities', 'orders.entity_id', '=', 'entities.id')
		// 	->join('offices', 'orders.office_id', '=', 'offices.id')
		// 	->join('document_statuses', 'orders.status', '=', 'document_statuses.id')
		// 	// ->leftJoin('orders as parent_order', 'orders.parent_order_id', '=', 'parent_order.id')
		// 	// ->leftJoin('offices as office_parent', 'parent_order.office_id', '=', 'office_parent.id')
		// 	->where('orders.deleted_at', null);

		// 	if ($request->has('inicio')) {
		// 		$orders = $orders->whereDate('orders.created_at', '>=', $start_date->format('Y-m-d'))
		// 			->whereDate('orders.created_at', '<=', $end_date->format('Y-m-d'));
		// 	}


		// 	if ($text) {
		// 		$orders = $orders->where(function ($query) use($text) {
  //          			$query->where('orders.code', $text)
  //               		->orWhere('entities.identity_document', $text);
  //      			});
		// 	}

		// 	//recibidos, generados, finalizados
		// 	if ($document_status) {
		// 		$orders = $orders->where('orders.status', $document_status);
		// 	}

		// $orders = $orders->where('orders.office_id', $office_id);

		// $orders = $orders->select(['orders.id', 'orders.code', 'document_types.name as document_type_name', 'orders.number as number', 'orders.subject as subject', 'entities.name as name', 'entities.paternal_surname as paternal_surname', 'entities.maternal_surname as maternal_surname', 'entities.identity_document as identity_document', 'entities.type_document as type_document', 'entities.cellphone as cellphone', 'entities.email as email', 'entities.address as address' , 'offices.name as office_name', 'orders.status as status', 'document_statuses.name as status_name', 'orders.attached_file', 'orders.created_at', 'orders.parent_order_id'])->get();

		$start_date = "";

		if ($request->has('inixcio')) {
			$start_date = Carbon::createFromFormat('d/m/Y', $request->inicio);
		}

		$end_date = "";

		if ($request->has('fin')) {
			$end_date = Carbon::createFromFormat('d/m/Y', $request->fin);
		}

		$user = User::with('entity')->find(Auth::user()->id);
		$entity = Entity::find($user->entity_id);

		$office_id = $user->entity->office_id;

		$text = trim($request->get('searchText'));
		$document_status = 1;

		$orders = DB::table('orders')
			->orderBy('orders.id', 'desc')
			->join('details_order', 'orders.id', '=', 'details_order.order_id')
			->join('document_types', 'orders.document_type_id', '=', 'document_types.id')
			->join('entities', 'orders.entity_id', '=', 'entities.id')
			->leftJoin('offices', 'details_order.office_id', '=', 'offices.id')
			->join('document_statuses', 'orders.status', '=', 'document_statuses.id')
			->leftJoin('orders as parent_order', 'orders.parent_order_id', '=', 'parent_order.id')
			->leftJoin('offices as office_parent', 'parent_order.office_id', '=', 'office_parent.id')
			->where('orders.deleted_at', null)
			->where('orders.multiple', 0);
			// ->whereDate('orders.created_at', '>=', $start_date->format('Y-m-d'))
			// ->whereDate('orders.created_at', '<=', $end_date->format('Y-m-d'));
			if ($request->has('inicio')) {
				$orders = $orders->whereDate('orders.created_at', '>=', $start_date->format('Y-m-d'))
					->whereDate('orders.created_at', '<=', $end_date->format('Y-m-d'));
			}


			if ($text) {
				$orders = $orders->where(function ($query) use($text) {
           			$query->where('orders.code', 'LIKE', "%$text%")
                		->orWhere('entities.identity_document', 'LIKE', "%$text%");
       			});
			}

			if ($document_status) {
				$orders = $orders->whereIn('details_order.status', [1]);
			}

		$orders = $orders->select(['orders.id', 'orders.code', 'document_types.name as document_type_name', 'orders.number as number', 'orders.subject as subject', 'entities.name as name', 'entities.paternal_surname as paternal_surname', 'entities.maternal_surname as maternal_surname', 'entities.identity_document as identity_document', 'entities.type_document as type_document', 'entities.cellphone as cellphone', 'entities.email as email', 'entities.address as address' , 'offices.name as office_name', 'orders.status as status', 'document_statuses.name as status_name', 'orders.attached_file', 'orders.created_at', 'orders.parent_order_id', 'office_parent.name as office_parent_name', 'orders.internal_code']);
			// ->paginate(20);

		$orders = $orders->where('orders.office_id_origen', $office_id)
			->where('details_order.office_id_origen', $office_id)->get();

		$excelName = "Mis solicitudes enviadas";
		if ($request->has('inicio')) {
        	$excelName = 'Solicitudes '.$start_date->format('d-m-Y')." ".$end_date->format('d-m-Y');
		}

        Excel::create($excelName, function($excel) use($orders) {
            $excel->sheet('data', function($sheet) use($orders) {
                $sheet->setOrientation('landscape');
                $sheet->fromArray(['#', 'Código', 'Fecha de Ingreso', 'Tipo de documento', 'Número', 'Asunto', 'De', 'Estado']);
                $row_line = 0;
                foreach ($orders as $key => $order) {
					$sheet->row($row_line+2, 
							[$row_line+1, 
								$order->code,
							Carbon::parse($order->created_at)->format('d/m/Y H:i'), 
							$order->document_type_name, 
							$order->internal_code ? $order->internal_code : "S/N", 
							$order->subject, $order->name." ".$order->paternal_surname." ".$order->maternal_surname,
							$order->status_name]);

					$row_line++;
                }
            });
        })->export('xls');
        // Excel::create($excelName, function($excel) use($orders) {
        //     $excel->sheet('data', function($sheet) use($orders) {
        //         $sheet->setOrientation('landscape');
        //         $sheet->fromArray(['#', 'Código', 'Fecha de Ingreso', 'Tipo de documento', 'De', 'Estado']);
        //         foreach ($orders as $key => $order) {
        //             $sheet->row($key+2, [$key+1, $order->code,
        //             	Carbon::parse($order->created_at)->format('d/m/Y H:i'),
        //             	$order->document_type_name,
        //             	$order->name." ".$order->paternal_surname." ".$order->maternal_surname,
        //             	$order->status_name]);
        //         }
        //     });
        // })->export('xls');
	}

	public function my_solicitude_report_pdf(Request $request)
	{

		// $document_type_id = $request->documento;
		// $office_id = $request->oficina;

		// $user = User::with('entity')->find(Auth::user()->id);
		// $entity = Entity::with('office')->find($user->entity_id);

		// $admin = false;

		// if ($user->role_id == 2) {
		// }


		// $orders = DB::table('orders')
		// 	->orderBy('details_order.id', 'desc')
		// 	->join('details_order', 'orders.id', '=', 'details_order.order_id')
		// 	->join('document_types', 'orders.document_type_id', '=', 'document_types.id')
		// 	->join('entities', 'orders.entity_id', '=', 'entities.id')
		// 	->leftJoin('offices', 'details_order.office_id_origen', '=', 'offices.id')
		// 	->join('document_statuses', 'details_order.status', '=', 'document_statuses.id')
		// 	->leftJoin('offices as office_destination', 'details_order.office_id', '=', 'office_destination.id')
		// 	->where('orders.deleted_at', null)
		// 	->where('orders.parent_order_id', 0);

		// if ($document_type_id) {
		// 	$orders = $orders->where('orders.document_type_id', $document_type_id);
		// }

		// $orders = $orders->where('details_order.office_id_origen', $office_id);
		$start_date = "";

		if ($request->has('inicio')) {
			$start_date = Carbon::createFromFormat('d/m/Y', $request->inicio);
		}

		$end_date = "";

		if ($request->has('fin')) {
			$end_date = Carbon::createFromFormat('d/m/Y', $request->fin);
		}

		$user = User::with('entity')->find(Auth::user()->id);
		$entity = Entity::find($user->entity_id);

		$office_id = $user->entity->office_id;

		$text = trim($request->get('searchText'));
		$document_status = 1;

		$orders = DB::table('orders')
			->orderBy('orders.id', 'desc')
			->join('details_order', 'orders.id', '=', 'details_order.order_id')
			->join('document_types', 'orders.document_type_id', '=', 'document_types.id')
			->join('entities', 'orders.entity_id', '=', 'entities.id')
			->leftJoin('offices', 'details_order.office_id', '=', 'offices.id')
			->join('document_statuses', 'orders.status', '=', 'document_statuses.id')
			->leftJoin('orders as parent_order', 'orders.parent_order_id', '=', 'parent_order.id')
			->leftJoin('offices as office_parent', 'parent_order.office_id', '=', 'office_parent.id')
			->where('orders.deleted_at', null)
			->where('orders.multiple', 0);

			if ($request->has('inicio')) {
				$orders = $orders->whereDate('orders.created_at', '>=', $start_date->format('Y-m-d'))
					->whereDate('orders.created_at', '<=', $end_date->format('Y-m-d'));
			}

			if ($text) {
				$orders = $orders->where(function ($query) use($text) {
           			$query->where('orders.code', 'LIKE', "%$text%")
                		->orWhere('entities.identity_document', 'LIKE', "%$text%");
       			});
			}

			if ($document_status) {
				$orders = $orders->whereIn('details_order.status', [1]);
			}

		$orders = $orders->where('orders.office_id_origen', $office_id)
			->where('details_order.office_id_origen', $office_id);

		$current_office_id = $entity->office_id;
		$admin = false;

		$orders = $orders->select(['orders.id', 'orders.code', 'document_types.name as document_type_name', 'orders.subject as subject', 'entities.name as name', 'entities.paternal_surname as paternal_surname', 'entities.maternal_surname as maternal_surname', 'entities.identity_document as identity_document', 'entities.type_document as type_document', 'entities.cellphone as cellphone', 'entities.email as email', 'entities.address as address' , 'offices.name as office_name', 'orders.status as status', 'document_statuses.name as status_name', 'orders.attached_file', 'orders.created_at', 'offices.name as office_parent_name', 'orders.office_id as current_office_id', 'orders.office_id_origen', 'orders.multiple', 'orders.internal_code', 'orders.folios'])
				->groupBy('orders.id')
				->get();		

		$pdf = app('Fpdf');
        $pdf->AddPage();

		$pdf->AddFont('Calibri','','calibri.php');
        $pdf->AddFont('Calibri-Bold','','calibri_b.php');
        $pdf->AddFont('Calibri-Italic','','calibri_i.php');
        $pdf->AddFont('Calibri-BoldItalic','','calibri_bi.php');
        $normal_space = 4.5;
        $norma_font_size = 10;
        $space_between_sections = 9;

        $pdf->Image('assets/cabeceras/muni_pachia.png', 13 , 5, 25);
       // $pdf->Image(asset('assets/cabeceras/unjbg-escudo.png'), 170 , 5, 25);

        $pdf->Ln();

        $pdf->SetY(10);


        $pdf->SetFont('Calibri', '', 13);
        $pdf->Cell(0, 8, utf8_decode('MUNICIPALIDAD DISTRITAL DE PACHÍA'), 0, "", "C");
        $pdf->Ln();

        $pdf->SetFont('Calibri-Bold', '', 12);
        $pdf->Cell(0, 8, utf8_decode('ÁREA DE MESA DE PARTES'), 0, "", "C");
        $pdf->Ln();

        $pdf->SetFont('Calibri', '', 9);
        $pdf->cell(4);
        $pdf->cell(30, 10, utf8_decode("Avenida Arias Araguez S/N Tacna - Pachía"), 0, "", "L");


        $pdf->SetX(98);
        $pdf->cell(10, 10, utf8_decode("Tacna - Perú"), 0, "", "C");

        //$pdf->SetY(26);
        $pdf->SetX(162);
        //$pdf->SetFont('Calibri-Bold', '', 8);
        //$pdf->cell(10, 6, utf8_decode("AGENCIA:"), 0, "", "R");

        $pdf->cell(30, 10, utf8_decode("Teléfono: 968 360 022"), 0, "", "R");
        $pdf->Ln(8);

        $pdf->cell(190, 1, "", "TB", "", "C");
        //$pdf->Ln();

        $pdf->SetY(40-2);

        $pdf->SetFont('Calibri-Bold', '', 13);
        $pdf->Cell(0, 8, utf8_decode('REPORTE DE TRÁMITES'), 0, "", "C");

        //$pdf->Ln(5);
        $pdf->SetY(52-3);

        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Calibri-Bold', '', 12);
        $pdf->Cell(0, 5, utf8_decode('##'), 0, "", "L", true);
        $pdf->Ln(3);

        $pdf->SetTextColor(0, 0, 0);

		$pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Calibri-Bold', '', 13);

        $pdf->Ln(5);

        $counter = 0;

        foreach ($orders as $key => $order) {

			if($order->multiple && !$admin){
				if($order->office_id_origen != $current_office_id){
			
				    if ($pdf->GetY() > 230) {
						$pdf->AddPage();
					}
			
					$pdf->SetFont('Calibri-Bold', '', 13);

					$pdf->Cell(30, 8, utf8_decode($order->code), 0, "", "L");

					if($order->internal_code){
						$pdf->Cell(50, 8, utf8_decode($order->internal_code), 0, "", "L");
					}

			        $pdf->Ln(5);

			        $pdf->Cell(25, 8, utf8_decode('Remitente'), 0, "", "L");
			        
			        $pdf->SetFont('Calibri', '', 12);
			        $pdf->Cell(20, 8, utf8_decode($order->name." ".$order->paternal_surname." ".$order->maternal_surname), 0, "", "L");

			        $pdf->Ln(5);

			        $pdf->SetFont('Calibri-Bold', '', 13);
			        $pdf->Cell(25, 8, utf8_decode('Fecha'), 0, "", "L");
			        $pdf->SetFont('Calibri', '', 12);
			        $pdf->Cell(40, 8, utf8_decode($order->created_at), 0, "", "L");

			        $pdf->SetFont('Calibri-Bold', '', 13);
					$pdf->Cell(25, 8, utf8_decode('Folios'), 0, "", "L");
			        $pdf->SetFont('Calibri', '', 12);
			        $pdf->Cell(20, 8, utf8_decode($order->folios), 0, "", "L");

			        $pdf->Ln(5);

			        $pdf->SetFont('Calibri-Bold', '', 13);
			        $pdf->Cell(25, 8, utf8_decode('Asunto'), 0, "", "L");
			        $pdf->SetFont('Calibri', '', 12);
					$pdf->MultiCell(180, 8, utf8_decode($order->subject), 0, "L", 0);
			        $pdf->Ln(2);

		            $pdf->SetTextColor(0, 0, 0);
		            $pdf->SetFont('Calibri', '', $norma_font_size);
		            
		            $nombre_width = 30;
		            $edad_width = 30;

		            $initial_x = 10;
		            $recursive_x = $initial_x;

		            $pdf->SetFont('Calibri-Bold', '', 9);

		            $y = $pdf->GetY();
		            $pdf->SetX($recursive_x);
		            $pdf->Multicell($nombre_width+1, 5, utf8_decode("Procedencia"), "LTRB", "C");

		            $recursive_x += $nombre_width+1;
		            $pdf->SetY($y);
		            $pdf->SetX($recursive_x);
		            $pdf->Multicell($nombre_width, 5, utf8_decode("Destino"), "TRB", "C");

		            $recursive_x += $nombre_width;
		            $pdf->SetY($y);
		            $pdf->SetX($recursive_x);
		            $pdf->Multicell($nombre_width, 5, utf8_decode("Estado"), "TRB", "C");

		            $recursive_x += $nombre_width;
		            $pdf->SetY($y);
		            $pdf->SetX($recursive_x);
		            $pdf->Multicell($edad_width+1, 5, utf8_decode("Fecha"), "TRB", "C");

		            $recursive_x += $edad_width+1;

		            $pdf->SetY($y);
		            $pdf->SetX($recursive_x);
		            $pdf->Multicell($nombre_width+1, 5, utf8_decode("Observación"), "TRB", "C");


		            $recursive_x += $nombre_width+1;
		            $pdf->SetY($y);
		            $pdf->SetX($recursive_x);
		            $pdf->Multicell($nombre_width, 5, utf8_decode("Usuario"), "TRB", "C");

		            $rows_number = 0;
		            $initial_column = 36;

		            $details = DetailOrder::whereOrderId($order->id)
		            		->with('office')
							->with('office_origen')
							->with('state')
							->with('user.entity')
		            		->get();

		            foreach ($details as $keyu => $detail) {

		                    $initial_x = 10;   
		                    $recursive_x = $initial_x;
		                    $rows = [];
		                    $y_max = 0;

		                    $y = $pdf->GetY();
		                    $y1 = $pdf->GetY();
		                    $pdf->SetX($recursive_x);
		                    $x = $pdf->GetX();
		                    $pdf->Multicell($nombre_width+1, 5, utf8_decode($detail->office_origen->name), "LT", "L");
		                    $y2 = $pdf->GetY();
		                    $y_last = $y2-$y1;

		                    $rows[] = array(
		                        'x' => $x,
		                        'y' => $y2,
		                        'total' => $y_last,
		                        'width' => $nombre_width+1,
		                    );

		                    if ($y_last > $y_max) {
		                        $y_max = $y_last;
		                    }

		                    $recursive_x += $nombre_width+1;
		                    $pdf->SetY($y);
		                    $y1 = $pdf->GetY();
		                    $pdf->SetX($recursive_x);
		                    $x = $pdf->GetX();
		                    $pdf->Multicell($nombre_width, 5, utf8_decode($detail->office ? $detail->office->name : "Varias oficinas"), "LT", "L");
		                    $y2 = $pdf->GetY();

		                    $y_last = $y2-$y1;

		                    $rows[] = array(
		                        'x' => $x,
		                        'y' => $y2,
		                        'total' => $y_last,
		                        'width' => $nombre_width,

		                    );

		                    if ($y_last > $y_max) {
		                        $y_max = $y_last;
		                    }

		                    $recursive_x += $nombre_width;
		                    $pdf->SetY($y);
		                    $y1 = $pdf->GetY();
		                    $pdf->SetX($recursive_x);
		                    $x = $pdf->GetX();
		                    $pdf->Multicell($nombre_width, 5, utf8_decode($detail->state->name), "LT", "L");
		                    $y2 = $pdf->GetY();

		                    $y_last = $y2-$y1;

		                    $rows[] = array(
		                        'x' => $x,
		                        'y' => $y2,
		                        'total' => $y_last,
		                        'width' => $nombre_width,
		                    );

		                    if ($y_last > $y_max) {
		                        $y_max = $y_last;
		                    }

		                    $recursive_x += $nombre_width;
		                    $pdf->SetY($y);
		                    $y1 = $pdf->GetY();
		                    $pdf->SetX($recursive_x);
		                    $x = $pdf->GetX();
		                    $pdf->Multicell($edad_width+1, 5, utf8_decode(Carbon::parse($detail->created_at)->format('d/m/Y H:i:s')), "LTR", "L");
		                    $y2 = $pdf->GetY();

		                    $y_last = $y2-$y1;

		                    $rows[] = array(
		                        'x' => $x,
		                        'y' => $y2,
		                        'total' => $y_last,
		                        'width' => $edad_width+1,
		                    );

		                    if ($y_last > $y_max) {
		                        $y_max = $y_last;
		                    }

		                    $recursive_x += $edad_width+1;
		                    $pdf->SetY($y);
		                    $y1 = $pdf->GetY();
		                    $pdf->SetX($recursive_x);
		                    $x = $pdf->GetX();
		                    $pdf->Multicell($nombre_width+1, 5, utf8_decode($detail->observations), "LTR", "L");
		                    $y2 = $pdf->GetY();

		                    $y_last = $y2-$y1;

		                    $rows[] = array(
		                        'x' => $x,
		                        'y' => $y2,
		                        'total' => $y_last,
		                        'width' => $nombre_width+1,
		                    );

		                    if ($y_last > $y_max) {
		                        $y_max = $y_last;
		                    }

		                    $recursive_x += $nombre_width+1;
		                    $pdf->SetY($y);
		                    $y1 = $pdf->GetY();
		                    $pdf->SetX($recursive_x);
		                    $x = $pdf->GetX();
		                    $pdf->Multicell($nombre_width, 5, utf8_decode($detail->user ? $detail->user->name : ""), "LTR", "L");
		                    $y2 = $pdf->GetY();

		                    $y_last = $y2-$y1;

		                    $rows[] = array(
		                        'x' => $x,
		                        'y' => $y2,
		                        'total' => $y_last,
		                        'width' => $nombre_width,
		                    );

		                    if ($y_last > $y_max) {
		                        $y_max = $y_last;
		                    }

		                    foreach ($rows as $keyi => $row) {
		                        if ($y_max > $row['total']) {
		                            $pdf->SetY($row['y']);
		                            $pdf->SetX($row['x']);
		                            $pdf->Multicell($row['width'], $y_max-$row['total'], utf8_decode(""), "LRB", "L");

		                        } else if($y_max == $row['total']) {
		                            $pdf->SetY($row['y']);
		                            $pdf->SetX($row['x']);
		                            $pdf->Multicell($row['width'], 0, utf8_decode(""), "B", "L");

		                        }
		                    }

		                   	if ($y2 > 236) {
								$pdf->AddPage();
							}

		            }

		            // while ($rows_number < 2) {

		            //     if (true) {


		                    
		            //     }

		            //     $initial_column += 14;
		            //     $rows_number++;
		            // }

			        $pdf->Ln(3);

			  //       $counter++;

					// if ($counter%3 == 0) {
					// 	$pdf->AddPage();
					// }

				}

			} else {

			    if ($pdf->GetY() > 230) {
					$pdf->AddPage();
				}				

				$pdf->SetFont('Calibri-Bold', '', 13);

				$pdf->Cell(30, 8, utf8_decode($order->code), 0, "", "L");

				if($order->internal_code){
					$pdf->Cell(50, 8, utf8_decode($order->internal_code), 0, "", "L");
				}

		        $pdf->Ln(5);

		        $pdf->Cell(25, 8, utf8_decode('Remitente'), 0, "", "L");
		        
		        $pdf->SetFont('Calibri', '', 12);
		        $pdf->Cell(20, 8, utf8_decode($order->name." ".$order->paternal_surname." ".$order->maternal_surname), 0, "", "L");

		        $pdf->Ln(5);

		        $pdf->SetFont('Calibri-Bold', '', 13);
		        $pdf->Cell(25, 8, utf8_decode('Fecha'), 0, "", "L");
		        $pdf->SetFont('Calibri', '', 12);
		        $pdf->Cell(40, 8, utf8_decode($order->created_at), 0, "", "L");

		        $pdf->SetFont('Calibri-Bold', '', 13);
				$pdf->Cell(25, 8, utf8_decode('Folios'), 0, "", "L");
		        $pdf->SetFont('Calibri', '', 12);
		        $pdf->Cell(20, 8, utf8_decode($order->folios), 0, "", "L");

		        $pdf->Ln(5);

		        $pdf->SetFont('Calibri-Bold', '', 13);
		        $pdf->Cell(25, 8, utf8_decode('Asunto'), 0, "", "L");
		        $pdf->SetFont('Calibri', '', 12);
				$pdf->MultiCell(180, 8, utf8_decode($order->subject), 0, "L", 0);
		        $pdf->Ln(2);

	            $pdf->SetTextColor(0, 0, 0);
	            $pdf->SetFont('Calibri', '', $norma_font_size);
	            
	            $nombre_width = 30;
	            $edad_width = 30;

	            $initial_x = 10;
	            $recursive_x = $initial_x;

	            $pdf->SetFont('Calibri-Bold', '', 9);

	            $y = $pdf->GetY();
	            $pdf->SetX($recursive_x);
	            $pdf->Multicell($nombre_width+1, 5, utf8_decode("Procedencia"), "LTRB", "C");

	            $recursive_x += $nombre_width+1;
	            $pdf->SetY($y);
	            $pdf->SetX($recursive_x);
	            $pdf->Multicell($nombre_width, 5, utf8_decode("Destino"), "TRB", "C");

	            $recursive_x += $nombre_width;
	            $pdf->SetY($y);
	            $pdf->SetX($recursive_x);
	            $pdf->Multicell($nombre_width, 5, utf8_decode("Estado"), "TRB", "C");

	            $recursive_x += $nombre_width;
	            $pdf->SetY($y);
	            $pdf->SetX($recursive_x);
	            $pdf->Multicell($edad_width+1, 5, utf8_decode("Fecha"), "TRB", "C");

	            $recursive_x += $edad_width+1;

	            $pdf->SetY($y);
	            $pdf->SetX($recursive_x);
	            $pdf->Multicell($nombre_width+1, 5, utf8_decode("Observación"), "TRB", "C");


	            $recursive_x += $nombre_width+1;
	            $pdf->SetY($y);
	            $pdf->SetX($recursive_x);
	            $pdf->Multicell($nombre_width, 5, utf8_decode("Usuario"), "TRB", "C");

	            $rows_number = 0;
	            $initial_column = 36;

	            $details = DetailOrder::whereOrderId($order->id)
	            		->with('office')
						->with('office_origen')
						->with('state')
						->with('user.entity')
	            		->get();

	            foreach ($details as $keyu => $detail) {

	                    $initial_x = 10;   
	                    $recursive_x = $initial_x;
	                    $rows = [];
	                    $y_max = 0;

	                    $y = $pdf->GetY();
	                    $y1 = $pdf->GetY();
	                    $pdf->SetX($recursive_x);
	                    $x = $pdf->GetX();
	                    $pdf->Multicell($nombre_width+1, 5, utf8_decode($detail->office_origen->name), "LT", "L");
	                    $y2 = $pdf->GetY();
	                    $y_last = $y2-$y1;

	                    $rows[] = array(
	                        'x' => $x,
	                        'y' => $y2,
	                        'total' => $y_last,
	                        'width' => $nombre_width+1,
	                    );

	                    if ($y_last > $y_max) {
	                        $y_max = $y_last;
	                    }

	                    $recursive_x += $nombre_width+1;
	                    $pdf->SetY($y);
	                    $y1 = $pdf->GetY();
	                    $pdf->SetX($recursive_x);
	                    $x = $pdf->GetX();
	                    $pdf->Multicell($nombre_width, 5, utf8_decode($detail->office ? $detail->office->name : "Varias oficinas"), "LT", "L");
	                    $y2 = $pdf->GetY();

	                    $y_last = $y2-$y1;

	                    $rows[] = array(
	                        'x' => $x,
	                        'y' => $y2,
	                        'total' => $y_last,
	                        'width' => $nombre_width,

	                    );

	                    if ($y_last > $y_max) {
	                        $y_max = $y_last;
	                    }

	                    $recursive_x += $nombre_width;
	                    $pdf->SetY($y);
	                    $y1 = $pdf->GetY();
	                    $pdf->SetX($recursive_x);
	                    $x = $pdf->GetX();
	                    $pdf->Multicell($nombre_width, 5, utf8_decode($detail->state->name), "LT", "L");
	                    $y2 = $pdf->GetY();

	                    $y_last = $y2-$y1;

	                    $rows[] = array(
	                        'x' => $x,
	                        'y' => $y2,
	                        'total' => $y_last,
	                        'width' => $nombre_width,
	                    );

	                    if ($y_last > $y_max) {
	                        $y_max = $y_last;
	                    }

	                    $recursive_x += $nombre_width;
	                    $pdf->SetY($y);
	                    $y1 = $pdf->GetY();
	                    $pdf->SetX($recursive_x);
	                    $x = $pdf->GetX();
	                    $pdf->Multicell($edad_width+1, 5, utf8_decode(Carbon::parse($detail->created_at)->format('d/m/Y H:i:s')), "LTR", "L");
	                    $y2 = $pdf->GetY();

	                    $y_last = $y2-$y1;

	                    $rows[] = array(
	                        'x' => $x,
	                        'y' => $y2,
	                        'total' => $y_last,
	                        'width' => $edad_width+1,
	                    );

	                    if ($y_last > $y_max) {
	                        $y_max = $y_last;
	                    }

	                    $recursive_x += $edad_width+1;
	                    $pdf->SetY($y);
	                    $y1 = $pdf->GetY();
	                    $pdf->SetX($recursive_x);
	                    $x = $pdf->GetX();
	                    $pdf->Multicell($nombre_width+1, 5, utf8_decode($detail->observations), "LTR", "L");
	                    $y2 = $pdf->GetY();

	                    $y_last = $y2-$y1;

	                    $rows[] = array(
	                        'x' => $x,
	                        'y' => $y2,
	                        'total' => $y_last,
	                        'width' => $nombre_width+1,
	                    );

	                    if ($y_last > $y_max) {
	                        $y_max = $y_last;
	                    }

	                    $recursive_x += $nombre_width+1;
	                    $pdf->SetY($y);
	                    $y1 = $pdf->GetY();
	                    $pdf->SetX($recursive_x);
	                    $x = $pdf->GetX();
	                    $pdf->Multicell($nombre_width, 5, utf8_decode($detail->user ? $detail->user->name : ""), "LTR", "L");
	                    $y2 = $pdf->GetY();

	                    $y_last = $y2-$y1;

	                    $rows[] = array(
	                        'x' => $x,
	                        'y' => $y2,
	                        'total' => $y_last,
	                        'width' => $nombre_width,
	                    );

	                    if ($y_last > $y_max) {
	                        $y_max = $y_last;
	                    }

	                    foreach ($rows as $keyi => $row) {
	                        if ($y_max > $row['total']) {
	                            $pdf->SetY($row['y']);
	                            $pdf->SetX($row['x']);
	                            $pdf->Multicell($row['width'], $y_max-$row['total'], utf8_decode(""), "LRB", "L");

	                        } else if($y_max == $row['total']) {
	                            $pdf->SetY($row['y']);
	                            $pdf->SetX($row['x']);
	                            $pdf->Multicell($row['width'], 0, utf8_decode(""), "B", "L");

	                        }
	                    }

                    	if ($y2 > 236) {
							$pdf->AddPage();
						}

	            }

	            // while ($rows_number < 2) {

	            //     if (true) {


	                    
	            //     }

	            //     $initial_column += 14;
	            //     $rows_number++;
	            // }

		        $pdf->Ln(3);

		  //       $counter++;

				// if ($counter%3 == 0) {
				// 	$pdf->AddPage();
				// }

			}	


        }

        $pdf->Output();

	}


	public function solicitude_report_pdf(Request $request)
	{

		$document_type_id = $request->documento;
		$office_id = $request->oficina;

		$user = User::with('entity')->find(Auth::user()->id);
		$entity = Entity::with('office')->find($user->entity_id);
		$current_office_id = $entity->office_id;

		$admin = false;

		if ($user->role_id == 2) {
			$admin = true;
		}

		$orders = DB::table('orders')
			->orderBy('details_order.id', 'desc')
			->join('details_order', 'orders.id', '=', 'details_order.order_id')
			->join('document_types', 'orders.document_type_id', '=', 'document_types.id')
			->join('entities', 'orders.entity_id', '=', 'entities.id')
			->leftJoin('offices', 'details_order.office_id_origen', '=', 'offices.id')
			->join('document_statuses', 'details_order.status', '=', 'document_statuses.id')
			->leftJoin('offices as office_destination', 'details_order.office_id', '=', 'office_destination.id')
			->where('orders.deleted_at', null)
			->where('orders.parent_order_id', 0);

		if ($document_type_id) {
			$orders = $orders->where('orders.document_type_id', $document_type_id);
		}

		$orders = $orders->where('details_order.office_id_origen', $office_id);

		$orders = $orders->select(['orders.id', 'orders.code', 'document_types.name as document_type_name', 'orders.subject as subject', 'entities.name as name', 'entities.paternal_surname as paternal_surname', 'entities.maternal_surname as maternal_surname', 'entities.identity_document as identity_document', 'entities.type_document as type_document', 'entities.cellphone as cellphone', 'entities.email as email', 'entities.address as address' , 'offices.name as office_name', 'orders.status as status', 'document_statuses.name as status_name', 'orders.attached_file', 'orders.created_at', 'offices.name as office_parent_name', 'office_destination.name as office_destination_name', 'orders.office_id as current_office_id', 'orders.office_id_origen', 'orders.multiple', 'orders.internal_code', 'orders.folios'])
				->groupBy('orders.id')
				->get();		

		$pdf = app('Fpdf');
        $pdf->AddPage();

		$pdf->AddFont('Calibri','','calibri.php');
        $pdf->AddFont('Calibri-Bold','','calibri_b.php');
        $pdf->AddFont('Calibri-Italic','','calibri_i.php');
        $pdf->AddFont('Calibri-BoldItalic','','calibri_bi.php');
        $normal_space = 4.5;
        $norma_font_size = 10;
        $space_between_sections = 9;

        $pdf->Image('assets/cabeceras/muni_pachia.png', 13 , 5, 25);
       // $pdf->Image(asset('assets/cabeceras/unjbg-escudo.png'), 170 , 5, 25);

        $pdf->Ln();

        $pdf->SetY(10);


        $pdf->SetFont('Calibri', '', 13);
        $pdf->Cell(0, 8, utf8_decode('MUNICIPALIDAD DISTRITAL DE PACHÍA'), 0, "", "C");
        $pdf->Ln();

        $pdf->SetFont('Calibri-Bold', '', 12);
        $pdf->Cell(0, 8, utf8_decode('ÁREA DE MESA DE PARTES'), 0, "", "C");
        $pdf->Ln();

        $pdf->SetFont('Calibri', '', 9);
        $pdf->cell(4);
        $pdf->cell(30, 10, utf8_decode("Avenida Arias Araguez S/N Tacna - Pachía"), 0, "", "L");


        $pdf->SetX(98);
        $pdf->cell(10, 10, utf8_decode("Tacna - Perú"), 0, "", "C");

        //$pdf->SetY(26);
        $pdf->SetX(162);
        //$pdf->SetFont('Calibri-Bold', '', 8);
        //$pdf->cell(10, 6, utf8_decode("AGENCIA:"), 0, "", "R");

        $pdf->cell(30, 10, utf8_decode("Teléfono: 968 360 022"), 0, "", "R");
        $pdf->Ln(8);

        $pdf->cell(190, 1, "", "TB", "", "C");
        //$pdf->Ln();

        $pdf->SetY(40-2);

        $pdf->SetFont('Calibri-Bold', '', 13);
        $pdf->Cell(0, 8, utf8_decode('REPORTE DE TRÁMITES'), 0, "", "C");

        //$pdf->Ln(5);
        $pdf->SetY(52-3);

        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Calibri-Bold', '', 12);
        $pdf->Cell(0, 5, utf8_decode('##'), 0, "", "L", true);
        $pdf->Ln(3);

        $pdf->SetTextColor(0, 0, 0);

		$pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Calibri-Bold', '', 13);

        $pdf->Ln(5);

        $counter = 0;

        foreach ($orders as $key => $order) {

			if($order->multiple && !$admin){
				if($order->office_id_origen != $current_office_id){
			
				    if ($pdf->GetY() > 230) {
						$pdf->AddPage();
					}
			
					$pdf->SetFont('Calibri-Bold', '', 13);

					$pdf->Cell(30, 8, utf8_decode($order->code), 0, "", "L");

					if($order->internal_code){
						$pdf->Cell(50, 8, utf8_decode($order->internal_code), 0, "", "L");
					}

			        $pdf->Ln(5);

			        $pdf->Cell(25, 8, utf8_decode('Remitente'), 0, "", "L");
			        
			        $pdf->SetFont('Calibri', '', 12);
			        $pdf->Cell(20, 8, utf8_decode($order->name." ".$order->paternal_surname." ".$order->maternal_surname), 0, "", "L");

			        $pdf->Ln(5);

			        $pdf->SetFont('Calibri-Bold', '', 13);
			        $pdf->Cell(25, 8, utf8_decode('Fecha'), 0, "", "L");
			        $pdf->SetFont('Calibri', '', 12);
			        $pdf->Cell(40, 8, utf8_decode($order->created_at), 0, "", "L");

			        $pdf->SetFont('Calibri-Bold', '', 13);
					$pdf->Cell(25, 8, utf8_decode('Folios'), 0, "", "L");
			        $pdf->SetFont('Calibri', '', 12);
			        $pdf->Cell(20, 8, utf8_decode($order->folios), 0, "", "L");

			        $pdf->Ln(5);

			        $pdf->SetFont('Calibri-Bold', '', 13);
			        $pdf->Cell(25, 8, utf8_decode('Asunto'), 0, "", "L");
			        $pdf->SetFont('Calibri', '', 12);
					$pdf->MultiCell(180, 8, utf8_decode($order->subject), 0, "L", 0);
			        $pdf->Ln(2);

		            $pdf->SetTextColor(0, 0, 0);
		            $pdf->SetFont('Calibri', '', $norma_font_size);
		            
		            $nombre_width = 30;
		            $edad_width = 30;

		            $initial_x = 10;
		            $recursive_x = $initial_x;

		            $pdf->SetFont('Calibri-Bold', '', 9);

		            $y = $pdf->GetY();
		            $pdf->SetX($recursive_x);
		            $pdf->Multicell($nombre_width+1, 5, utf8_decode("Procedencia"), "LTRB", "C");

		            $recursive_x += $nombre_width+1;
		            $pdf->SetY($y);
		            $pdf->SetX($recursive_x);
		            $pdf->Multicell($nombre_width, 5, utf8_decode("Destino"), "TRB", "C");

		            $recursive_x += $nombre_width;
		            $pdf->SetY($y);
		            $pdf->SetX($recursive_x);
		            $pdf->Multicell($nombre_width, 5, utf8_decode("Estado"), "TRB", "C");

		            $recursive_x += $nombre_width;
		            $pdf->SetY($y);
		            $pdf->SetX($recursive_x);
		            $pdf->Multicell($edad_width+1, 5, utf8_decode("Fecha"), "TRB", "C");

		            $recursive_x += $edad_width+1;

		            $pdf->SetY($y);
		            $pdf->SetX($recursive_x);
		            $pdf->Multicell($nombre_width+1, 5, utf8_decode("Observación"), "TRB", "C");


		            $recursive_x += $nombre_width+1;
		            $pdf->SetY($y);
		            $pdf->SetX($recursive_x);
		            $pdf->Multicell($nombre_width, 5, utf8_decode("Usuario"), "TRB", "C");

		            $rows_number = 0;
		            $initial_column = 36;

		            $details = DetailOrder::whereOrderId($order->id)
		            		->with('office')
							->with('office_origen')
							->with('state')
							->with('user.entity')
		            		->get();

		            foreach ($details as $keyu => $detail) {

		                    $initial_x = 10;   
		                    $recursive_x = $initial_x;
		                    $rows = [];
		                    $y_max = 0;

		                    $y = $pdf->GetY();
		                    $y1 = $pdf->GetY();
		                    $pdf->SetX($recursive_x);
		                    $x = $pdf->GetX();
		                    $pdf->Multicell($nombre_width+1, 5, utf8_decode($detail->office_origen->name), "LT", "L");
		                    $y2 = $pdf->GetY();
		                    $y_last = $y2-$y1;

		                    $rows[] = array(
		                        'x' => $x,
		                        'y' => $y2,
		                        'total' => $y_last,
		                        'width' => $nombre_width+1,
		                    );

		                    if ($y_last > $y_max) {
		                        $y_max = $y_last;
		                    }

		                    $recursive_x += $nombre_width+1;
		                    $pdf->SetY($y);
		                    $y1 = $pdf->GetY();
		                    $pdf->SetX($recursive_x);
		                    $x = $pdf->GetX();
		                    $pdf->Multicell($nombre_width, 5, utf8_decode($detail->office ? $detail->office->name : "Varias oficinas"), "LT", "L");
		                    $y2 = $pdf->GetY();

		                    $y_last = $y2-$y1;

		                    $rows[] = array(
		                        'x' => $x,
		                        'y' => $y2,
		                        'total' => $y_last,
		                        'width' => $nombre_width,

		                    );

		                    if ($y_last > $y_max) {
		                        $y_max = $y_last;
		                    }

		                    $recursive_x += $nombre_width;
		                    $pdf->SetY($y);
		                    $y1 = $pdf->GetY();
		                    $pdf->SetX($recursive_x);
		                    $x = $pdf->GetX();
		                    $pdf->Multicell($nombre_width, 5, utf8_decode($detail->state->name), "LT", "L");
		                    $y2 = $pdf->GetY();

		                    $y_last = $y2-$y1;

		                    $rows[] = array(
		                        'x' => $x,
		                        'y' => $y2,
		                        'total' => $y_last,
		                        'width' => $nombre_width,
		                    );

		                    if ($y_last > $y_max) {
		                        $y_max = $y_last;
		                    }

		                    $recursive_x += $nombre_width;
		                    $pdf->SetY($y);
		                    $y1 = $pdf->GetY();
		                    $pdf->SetX($recursive_x);
		                    $x = $pdf->GetX();
		                    $pdf->Multicell($edad_width+1, 5, utf8_decode(Carbon::parse($detail->created_at)->format('d/m/Y H:i:s')), "LTR", "L");
		                    $y2 = $pdf->GetY();

		                    $y_last = $y2-$y1;

		                    $rows[] = array(
		                        'x' => $x,
		                        'y' => $y2,
		                        'total' => $y_last,
		                        'width' => $edad_width+1,
		                    );

		                    if ($y_last > $y_max) {
		                        $y_max = $y_last;
		                    }

		                    $recursive_x += $edad_width+1;
		                    $pdf->SetY($y);
		                    $y1 = $pdf->GetY();
		                    $pdf->SetX($recursive_x);
		                    $x = $pdf->GetX();
		                    $pdf->Multicell($nombre_width+1, 5, utf8_decode($detail->observations), "LTR", "L");
		                    $y2 = $pdf->GetY();

		                    $y_last = $y2-$y1;

		                    $rows[] = array(
		                        'x' => $x,
		                        'y' => $y2,
		                        'total' => $y_last,
		                        'width' => $nombre_width+1,
		                    );

		                    if ($y_last > $y_max) {
		                        $y_max = $y_last;
		                    }

		                    $recursive_x += $nombre_width+1;
		                    $pdf->SetY($y);
		                    $y1 = $pdf->GetY();
		                    $pdf->SetX($recursive_x);
		                    $x = $pdf->GetX();
		                    $pdf->Multicell($nombre_width, 5, utf8_decode($detail->user ? $detail->user->name : ""), "LTR", "L");
		                    $y2 = $pdf->GetY();

		                    $y_last = $y2-$y1;

		                    $rows[] = array(
		                        'x' => $x,
		                        'y' => $y2,
		                        'total' => $y_last,
		                        'width' => $nombre_width,
		                    );

		                    if ($y_last > $y_max) {
		                        $y_max = $y_last;
		                    }

		                    foreach ($rows as $keyi => $row) {
		                        if ($y_max > $row['total']) {
		                            $pdf->SetY($row['y']);
		                            $pdf->SetX($row['x']);
		                            $pdf->Multicell($row['width'], $y_max-$row['total'], utf8_decode(""), "LRB", "L");

		                        } else if($y_max == $row['total']) {
		                            $pdf->SetY($row['y']);
		                            $pdf->SetX($row['x']);
		                            $pdf->Multicell($row['width'], 0, utf8_decode(""), "B", "L");

		                        }
		                    }

		                   	if ($y2 > 236) {
								$pdf->AddPage();
							}

		            }

		            // while ($rows_number < 2) {

		            //     if (true) {


		                    
		            //     }

		            //     $initial_column += 14;
		            //     $rows_number++;
		            // }

			        $pdf->Ln(3);

			  //       $counter++;

					// if ($counter%3 == 0) {
					// 	$pdf->AddPage();
					// }

				}

			} else {

			    if ($pdf->GetY() > 230) {
					$pdf->AddPage();
				}				

				$pdf->SetFont('Calibri-Bold', '', 13);

				$pdf->Cell(30, 8, utf8_decode($order->code), 0, "", "L");

				if($order->internal_code){
					$pdf->Cell(50, 8, utf8_decode($order->internal_code), 0, "", "L");
				}

		        $pdf->Ln(5);

		        $pdf->Cell(25, 8, utf8_decode('Remitente'), 0, "", "L");
		        
		        $pdf->SetFont('Calibri', '', 12);
		        $pdf->Cell(20, 8, utf8_decode($order->name." ".$order->paternal_surname." ".$order->maternal_surname), 0, "", "L");

		        $pdf->Ln(5);

		        $pdf->SetFont('Calibri-Bold', '', 13);
		        $pdf->Cell(25, 8, utf8_decode('Fecha'), 0, "", "L");
		        $pdf->SetFont('Calibri', '', 12);
		        $pdf->Cell(40, 8, utf8_decode($order->created_at), 0, "", "L");

		        $pdf->SetFont('Calibri-Bold', '', 13);
				$pdf->Cell(25, 8, utf8_decode('Folios'), 0, "", "L");
		        $pdf->SetFont('Calibri', '', 12);
		        $pdf->Cell(20, 8, utf8_decode($order->folios), 0, "", "L");

		        $pdf->Ln(5);

		        $pdf->SetFont('Calibri-Bold', '', 13);
		        $pdf->Cell(25, 8, utf8_decode('Asunto'), 0, "", "L");
		        $pdf->SetFont('Calibri', '', 12);
				$pdf->MultiCell(180, 8, utf8_decode($order->subject), 0, "L", 0);
		        $pdf->Ln(2);

	            $pdf->SetTextColor(0, 0, 0);
	            $pdf->SetFont('Calibri', '', $norma_font_size);
	            
	            $nombre_width = 30;
	            $edad_width = 30;

	            $initial_x = 10;
	            $recursive_x = $initial_x;

	            $pdf->SetFont('Calibri-Bold', '', 9);

	            $y = $pdf->GetY();
	            $pdf->SetX($recursive_x);
	            $pdf->Multicell($nombre_width+1, 5, utf8_decode("Procedencia"), "LTRB", "C");

	            $recursive_x += $nombre_width+1;
	            $pdf->SetY($y);
	            $pdf->SetX($recursive_x);
	            $pdf->Multicell($nombre_width, 5, utf8_decode("Destino"), "TRB", "C");

	            $recursive_x += $nombre_width;
	            $pdf->SetY($y);
	            $pdf->SetX($recursive_x);
	            $pdf->Multicell($nombre_width, 5, utf8_decode("Estado"), "TRB", "C");

	            $recursive_x += $nombre_width;
	            $pdf->SetY($y);
	            $pdf->SetX($recursive_x);
	            $pdf->Multicell($edad_width+1, 5, utf8_decode("Fecha"), "TRB", "C");

	            $recursive_x += $edad_width+1;

	            $pdf->SetY($y);
	            $pdf->SetX($recursive_x);
	            $pdf->Multicell($nombre_width+1, 5, utf8_decode("Observación"), "TRB", "C");


	            $recursive_x += $nombre_width+1;
	            $pdf->SetY($y);
	            $pdf->SetX($recursive_x);
	            $pdf->Multicell($nombre_width, 5, utf8_decode("Usuario"), "TRB", "C");

	            $rows_number = 0;
	            $initial_column = 36;

	            $details = DetailOrder::whereOrderId($order->id)
	            		->with('office')
						->with('office_origen')
						->with('state')
						->with('user.entity')
	            		->get();

	            foreach ($details as $keyu => $detail) {

	                    $initial_x = 10;   
	                    $recursive_x = $initial_x;
	                    $rows = [];
	                    $y_max = 0;

	                    $y = $pdf->GetY();
	                    $y1 = $pdf->GetY();
	                    $pdf->SetX($recursive_x);
	                    $x = $pdf->GetX();
	                    $pdf->Multicell($nombre_width+1, 5, utf8_decode($detail->office_origen->name), "LT", "L");
	                    $y2 = $pdf->GetY();
	                    $y_last = $y2-$y1;

	                    $rows[] = array(
	                        'x' => $x,
	                        'y' => $y2,
	                        'total' => $y_last,
	                        'width' => $nombre_width+1,
	                    );

	                    if ($y_last > $y_max) {
	                        $y_max = $y_last;
	                    }

	                    $recursive_x += $nombre_width+1;
	                    $pdf->SetY($y);
	                    $y1 = $pdf->GetY();
	                    $pdf->SetX($recursive_x);
	                    $x = $pdf->GetX();
	                    $pdf->Multicell($nombre_width, 5, utf8_decode($detail->office ? $detail->office->name : "Varias oficinas"), "LT", "L");
	                    $y2 = $pdf->GetY();

	                    $y_last = $y2-$y1;

	                    $rows[] = array(
	                        'x' => $x,
	                        'y' => $y2,
	                        'total' => $y_last,
	                        'width' => $nombre_width,

	                    );

	                    if ($y_last > $y_max) {
	                        $y_max = $y_last;
	                    }

	                    $recursive_x += $nombre_width;
	                    $pdf->SetY($y);
	                    $y1 = $pdf->GetY();
	                    $pdf->SetX($recursive_x);
	                    $x = $pdf->GetX();
	                    $pdf->Multicell($nombre_width, 5, utf8_decode($detail->state->name), "LT", "L");
	                    $y2 = $pdf->GetY();

	                    $y_last = $y2-$y1;

	                    $rows[] = array(
	                        'x' => $x,
	                        'y' => $y2,
	                        'total' => $y_last,
	                        'width' => $nombre_width,
	                    );

	                    if ($y_last > $y_max) {
	                        $y_max = $y_last;
	                    }

	                    $recursive_x += $nombre_width;
	                    $pdf->SetY($y);
	                    $y1 = $pdf->GetY();
	                    $pdf->SetX($recursive_x);
	                    $x = $pdf->GetX();
	                    $pdf->Multicell($edad_width+1, 5, utf8_decode(Carbon::parse($detail->created_at)->format('d/m/Y H:i:s')), "LTR", "L");
	                    $y2 = $pdf->GetY();

	                    $y_last = $y2-$y1;

	                    $rows[] = array(
	                        'x' => $x,
	                        'y' => $y2,
	                        'total' => $y_last,
	                        'width' => $edad_width+1,
	                    );

	                    if ($y_last > $y_max) {
	                        $y_max = $y_last;
	                    }

	                    $recursive_x += $edad_width+1;
	                    $pdf->SetY($y);
	                    $y1 = $pdf->GetY();
	                    $pdf->SetX($recursive_x);
	                    $x = $pdf->GetX();
	                    $pdf->Multicell($nombre_width+1, 5, utf8_decode($detail->observations), "LTR", "L");
	                    $y2 = $pdf->GetY();

	                    $y_last = $y2-$y1;

	                    $rows[] = array(
	                        'x' => $x,
	                        'y' => $y2,
	                        'total' => $y_last,
	                        'width' => $nombre_width+1,
	                    );

	                    if ($y_last > $y_max) {
	                        $y_max = $y_last;
	                    }

	                    $recursive_x += $nombre_width+1;
	                    $pdf->SetY($y);
	                    $y1 = $pdf->GetY();
	                    $pdf->SetX($recursive_x);
	                    $x = $pdf->GetX();
	                    $pdf->Multicell($nombre_width, 5, utf8_decode($detail->user ? $detail->user->name : ""), "LTR", "L");
	                    $y2 = $pdf->GetY();

	                    $y_last = $y2-$y1;

	                    $rows[] = array(
	                        'x' => $x,
	                        'y' => $y2,
	                        'total' => $y_last,
	                        'width' => $nombre_width,
	                    );

	                    if ($y_last > $y_max) {
	                        $y_max = $y_last;
	                    }

	                    foreach ($rows as $keyi => $row) {
	                        if ($y_max > $row['total']) {
	                            $pdf->SetY($row['y']);
	                            $pdf->SetX($row['x']);
	                            $pdf->Multicell($row['width'], $y_max-$row['total'], utf8_decode(""), "LRB", "L");

	                        } else if($y_max == $row['total']) {
	                            $pdf->SetY($row['y']);
	                            $pdf->SetX($row['x']);
	                            $pdf->Multicell($row['width'], 0, utf8_decode(""), "B", "L");

	                        }
	                    }

                    	if ($y2 > 236) {
							$pdf->AddPage();
						}

	            }

	            // while ($rows_number < 2) {

	            //     if (true) {


	                    
	            //     }

	            //     $initial_column += 14;
	            //     $rows_number++;
	            // }

		        $pdf->Ln(3);

		  //       $counter++;

				// if ($counter%3 == 0) {
				// 	$pdf->AddPage();
				// }

			}	


        }

        $pdf->Output();

	}

	public function solicitude_report_code_pdf(Request $request)
	{

		$text = $request->text;
		$office_id = $request->oficina;

		$user = User::with('entity')->find(Auth::user()->id);
		$entity = Entity::with('office')->find($user->entity_id);
		$current_office_id = $entity->office_id;

		$admin = false;

		if ($user->role_id == 2) {
			$admin = true;
		}

		$orders = DB::table('orders')
			->orderBy('details_order.id', 'desc')
			->join('details_order', 'orders.id', '=', 'details_order.order_id')
			->join('document_types', 'orders.document_type_id', '=', 'document_types.id')
			->join('entities', 'orders.entity_id', '=', 'entities.id')
			->leftJoin('offices', 'details_order.office_id_origen', '=', 'offices.id')
			->join('document_statuses', 'details_order.status', '=', 'document_statuses.id')
			->leftJoin('offices as office_destination', 'details_order.office_id', '=', 'office_destination.id')
			->where('orders.deleted_at', null)
			->where('orders.parent_order_id', 0);

		if ($text) {
			$orders = $orders->where(function ($query) use($text) {
       			$query->where('orders.code', 'LIKE', "%$text%");
   			});
		}

		$orders = $orders->where('details_order.office_id_origen', $office_id);

		$orders = $orders->select(['orders.id', 'orders.code', 'document_types.name as document_type_name', 'orders.subject as subject', 'entities.name as name', 'entities.paternal_surname as paternal_surname', 'entities.maternal_surname as maternal_surname', 'entities.identity_document as identity_document', 'entities.type_document as type_document', 'entities.cellphone as cellphone', 'entities.email as email', 'entities.address as address' , 'offices.name as office_name', 'orders.status as status', 'document_statuses.name as status_name', 'orders.attached_file', 'orders.created_at', 'offices.name as office_parent_name', 'office_destination.name as office_destination_name', 'orders.office_id as current_office_id', 'orders.office_id_origen', 'orders.multiple', 'orders.internal_code', 'orders.folios'])
				->groupBy('orders.id')
				->get();		

		$pdf = app('Fpdf');
        $pdf->AddPage();

		$pdf->AddFont('Calibri','','calibri.php');
        $pdf->AddFont('Calibri-Bold','','calibri_b.php');
        $pdf->AddFont('Calibri-Italic','','calibri_i.php');
        $pdf->AddFont('Calibri-BoldItalic','','calibri_bi.php');
        $normal_space = 4.5;
        $norma_font_size = 10;
        $space_between_sections = 9;

        $pdf->Image('assets/cabeceras/muni_pachia.png', 13 , 5, 25);
       // $pdf->Image(asset('assets/cabeceras/unjbg-escudo.png'), 170 , 5, 25);

        $pdf->Ln();

        $pdf->SetY(10);


        $pdf->SetFont('Calibri', '', 13);
        $pdf->Cell(0, 8, utf8_decode('MUNICIPALIDAD DISTRITAL DE PACHÍA'), 0, "", "C");
        $pdf->Ln();

        $pdf->SetFont('Calibri-Bold', '', 12);
        $pdf->Cell(0, 8, utf8_decode('ÁREA DE MESA DE PARTES'), 0, "", "C");
        $pdf->Ln();

        $pdf->SetFont('Calibri', '', 9);
        $pdf->cell(4);
        $pdf->cell(30, 10, utf8_decode("Avenida Arias Araguez S/N Tacna - Pachía"), 0, "", "L");


        $pdf->SetX(98);
        $pdf->cell(10, 10, utf8_decode("Tacna - Perú"), 0, "", "C");

        //$pdf->SetY(26);
        $pdf->SetX(162);
        //$pdf->SetFont('Calibri-Bold', '', 8);
        //$pdf->cell(10, 6, utf8_decode("AGENCIA:"), 0, "", "R");

        $pdf->cell(30, 10, utf8_decode("Teléfono: 968 360 022"), 0, "", "R");
        $pdf->Ln(8);

        $pdf->cell(190, 1, "", "TB", "", "C");
        //$pdf->Ln();

        $pdf->SetY(40-2);

        $pdf->SetFont('Calibri-Bold', '', 13);
        $pdf->Cell(0, 8, utf8_decode('REPORTE DE TRÁMITES'), 0, "", "C");

        //$pdf->Ln(5);
        $pdf->SetY(52-3);

        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Calibri-Bold', '', 12);
        $pdf->Cell(0, 5, utf8_decode('##'), 0, "", "L", true);
        $pdf->Ln(3);

        $pdf->SetTextColor(0, 0, 0);

		$pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Calibri-Bold', '', 13);

        $pdf->Ln(5);

        $counter = 0;

        foreach ($orders as $key => $order) {

			if($order->multiple && !$admin){
				if($order->office_id_origen != $current_office_id){
			
				    if ($pdf->GetY() > 230) {
						$pdf->AddPage();
					}

					$pdf->SetFont('Calibri-Bold', '', 13);

					$pdf->Cell(30, 8, utf8_decode($order->code), 0, "", "L");

					if($order->internal_code){
						$pdf->Cell(50, 8, utf8_decode($order->internal_code), 0, "", "L");
					}

			        $pdf->Ln(5);

			        $pdf->Cell(25, 8, utf8_decode('Remitente'), 0, "", "L");
			        
			        $pdf->SetFont('Calibri', '', 12);
			        $pdf->Cell(20, 8, utf8_decode($order->name." ".$order->paternal_surname." ".$order->maternal_surname), 0, "", "L");

			        $pdf->Ln(5);

			        $pdf->SetFont('Calibri-Bold', '', 13);
			        $pdf->Cell(25, 8, utf8_decode('Fecha'), 0, "", "L");
			        $pdf->SetFont('Calibri', '', 12);
			        $pdf->Cell(40, 8, utf8_decode($order->created_at), 0, "", "L");

			        $pdf->SetFont('Calibri-Bold', '', 13);
					$pdf->Cell(25, 8, utf8_decode('Folios'), 0, "", "L");
			        $pdf->SetFont('Calibri', '', 12);
			        $pdf->Cell(20, 8, utf8_decode($order->folios), 0, "", "L");

			        $pdf->Ln(5);

			        $pdf->SetFont('Calibri-Bold', '', 13);
			        $pdf->Cell(25, 8, utf8_decode('Asunto'), 0, "", "L");
			        $pdf->SetFont('Calibri', '', 12);
					$pdf->MultiCell(180, 8, utf8_decode($order->subject), 0, "L", 0);
			        $pdf->Ln(2);

		            $pdf->SetTextColor(0, 0, 0);
		            $pdf->SetFont('Calibri', '', $norma_font_size);
		            
		            $nombre_width = 30;
		            $edad_width = 30;

		            $initial_x = 10;
		            $recursive_x = $initial_x;

		            $pdf->SetFont('Calibri-Bold', '', 9);

		            $y = $pdf->GetY();
		            $pdf->SetX($recursive_x);
		            $pdf->Multicell($nombre_width+1, 5, utf8_decode("Procedencia"), "LTRB", "C");

		            $recursive_x += $nombre_width+1;
		            $pdf->SetY($y);
		            $pdf->SetX($recursive_x);
		            $pdf->Multicell($nombre_width, 5, utf8_decode("Destino"), "TRB", "C");

		            $recursive_x += $nombre_width;
		            $pdf->SetY($y);
		            $pdf->SetX($recursive_x);
		            $pdf->Multicell($nombre_width, 5, utf8_decode("Estado"), "TRB", "C");

		            $recursive_x += $nombre_width;
		            $pdf->SetY($y);
		            $pdf->SetX($recursive_x);
		            $pdf->Multicell($edad_width+1, 5, utf8_decode("Fecha"), "TRB", "C");

		            $recursive_x += $edad_width+1;

		            $pdf->SetY($y);
		            $pdf->SetX($recursive_x);
		            $pdf->Multicell($nombre_width+1, 5, utf8_decode("Observación"), "TRB", "C");


		            $recursive_x += $nombre_width+1;
		            $pdf->SetY($y);
		            $pdf->SetX($recursive_x);
		            $pdf->Multicell($nombre_width, 5, utf8_decode("Usuario"), "TRB", "C");

		            $rows_number = 0;
		            $initial_column = 36;

		            $details = DetailOrder::whereOrderId($order->id)
		            		->with('office')
							->with('office_origen')
							->with('state')
							->with('user.entity')
		            		->get();

		            foreach ($details as $keyu => $detail) {

		                    $initial_x = 10;   
		                    $recursive_x = $initial_x;
		                    $rows = [];
		                    $y_max = 0;

		                    $y = $pdf->GetY();
		                    $y1 = $pdf->GetY();
		                    $pdf->SetX($recursive_x);
		                    $x = $pdf->GetX();
		                    $pdf->Multicell($nombre_width+1, 5, utf8_decode($detail->office_origen->name), "LT", "L");
		                    $y2 = $pdf->GetY();
		                    $y_last = $y2-$y1;

		                    $rows[] = array(
		                        'x' => $x,
		                        'y' => $y2,
		                        'total' => $y_last,
		                        'width' => $nombre_width+1,
		                    );

		                    if ($y_last > $y_max) {
		                        $y_max = $y_last;
		                    }

		                    $recursive_x += $nombre_width+1;
		                    $pdf->SetY($y);
		                    $y1 = $pdf->GetY();
		                    $pdf->SetX($recursive_x);
		                    $x = $pdf->GetX();
		                    $pdf->Multicell($nombre_width, 5, utf8_decode($detail->office ? $detail->office->name : "Varias oficinas"), "LT", "L");
		                    $y2 = $pdf->GetY();

		                    $y_last = $y2-$y1;

		                    $rows[] = array(
		                        'x' => $x,
		                        'y' => $y2,
		                        'total' => $y_last,
		                        'width' => $nombre_width,

		                    );

		                    if ($y_last > $y_max) {
		                        $y_max = $y_last;
		                    }

		                    $recursive_x += $nombre_width;
		                    $pdf->SetY($y);
		                    $y1 = $pdf->GetY();
		                    $pdf->SetX($recursive_x);
		                    $x = $pdf->GetX();
		                    $pdf->Multicell($nombre_width, 5, utf8_decode($detail->state->name), "LT", "L");
		                    $y2 = $pdf->GetY();

		                    $y_last = $y2-$y1;

		                    $rows[] = array(
		                        'x' => $x,
		                        'y' => $y2,
		                        'total' => $y_last,
		                        'width' => $nombre_width,
		                    );

		                    if ($y_last > $y_max) {
		                        $y_max = $y_last;
		                    }

		                    $recursive_x += $nombre_width;
		                    $pdf->SetY($y);
		                    $y1 = $pdf->GetY();
		                    $pdf->SetX($recursive_x);
		                    $x = $pdf->GetX();
		                    $pdf->Multicell($edad_width+1, 5, utf8_decode(Carbon::parse($detail->created_at)->format('d/m/Y H:i:s')), "LTR", "L");
		                    $y2 = $pdf->GetY();

		                    $y_last = $y2-$y1;

		                    $rows[] = array(
		                        'x' => $x,
		                        'y' => $y2,
		                        'total' => $y_last,
		                        'width' => $edad_width+1,
		                    );

		                    if ($y_last > $y_max) {
		                        $y_max = $y_last;
		                    }

		                    $recursive_x += $edad_width+1;
		                    $pdf->SetY($y);
		                    $y1 = $pdf->GetY();
		                    $pdf->SetX($recursive_x);
		                    $x = $pdf->GetX();
		                    $pdf->Multicell($nombre_width+1, 5, utf8_decode($detail->observations), "LTR", "L");
		                    $y2 = $pdf->GetY();

		                    $y_last = $y2-$y1;

		                    $rows[] = array(
		                        'x' => $x,
		                        'y' => $y2,
		                        'total' => $y_last,
		                        'width' => $nombre_width+1,
		                    );

		                    if ($y_last > $y_max) {
		                        $y_max = $y_last;
		                    }

		                    $recursive_x += $nombre_width+1;
		                    $pdf->SetY($y);
		                    $y1 = $pdf->GetY();
		                    $pdf->SetX($recursive_x);
		                    $x = $pdf->GetX();
		                    $pdf->Multicell($nombre_width, 5, utf8_decode($detail->user ? $detail->user->name : ""), "LTR", "L");
		                    $y2 = $pdf->GetY();

		                    $y_last = $y2-$y1;

		                    $rows[] = array(
		                        'x' => $x,
		                        'y' => $y2,
		                        'total' => $y_last,
		                        'width' => $nombre_width,
		                    );

		                    if ($y_last > $y_max) {
		                        $y_max = $y_last;
		                    }

		                    foreach ($rows as $keyi => $row) {
		                        if ($y_max > $row['total']) {
		                            $pdf->SetY($row['y']);
		                            $pdf->SetX($row['x']);
		                            $pdf->Multicell($row['width'], $y_max-$row['total'], utf8_decode(""), "LRB", "L");

		                        } else if($y_max == $row['total']) {
		                            $pdf->SetY($row['y']);
		                            $pdf->SetX($row['x']);
		                            $pdf->Multicell($row['width'], 0, utf8_decode(""), "B", "L");

		                        }
		                    }
    
	    	                if ($y2 > 236) {
								$pdf->AddPage();
							}


		            }

		            // while ($rows_number < 2) {

		            //     if (true) {


		                    
		            //     }

		            //     $initial_column += 14;
		            //     $rows_number++;
		            // }

			        $pdf->Ln(3);

			  //       $counter++;

					// if ($counter%6 == 0) {
					// 	$pdf->AddPage();
					// }

				}

			} else {

			    if ($pdf->GetY() > 230) {
					$pdf->AddPage();
				}

				$pdf->SetFont('Calibri-Bold', '', 13);

				$pdf->Cell(30, 8, utf8_decode($order->code), 0, "", "L");

				if($order->internal_code){
					$pdf->Cell(50, 8, utf8_decode($order->internal_code), 0, "", "L");
				}

		        $pdf->Ln(5);

		        $pdf->Cell(25, 8, utf8_decode('Remitente'), 0, "", "L");
		        
		        $pdf->SetFont('Calibri', '', 12);
		        $pdf->Cell(20, 8, utf8_decode($order->name." ".$order->paternal_surname." ".$order->maternal_surname), 0, "", "L");

		        $pdf->Ln(5);

		        $pdf->SetFont('Calibri-Bold', '', 13);
		        $pdf->Cell(25, 8, utf8_decode('Fecha'), 0, "", "L");
		        $pdf->SetFont('Calibri', '', 12);
		        $pdf->Cell(40, 8, utf8_decode($order->created_at), 0, "", "L");

		        $pdf->SetFont('Calibri-Bold', '', 13);
				$pdf->Cell(25, 8, utf8_decode('Folios'), 0, "", "L");
		        $pdf->SetFont('Calibri', '', 12);
		        $pdf->Cell(20, 8, utf8_decode($order->folios), 0, "", "L");

		        $pdf->Ln(5);

		        $pdf->SetFont('Calibri-Bold', '', 13);
		        $pdf->Cell(25, 8, utf8_decode('Asunto'), 0, "", "L");
		        $pdf->SetFont('Calibri', '', 12);
				$pdf->MultiCell(180, 8, utf8_decode($order->subject), 0, "L", 0);
		        $pdf->Ln(2);

	            $pdf->SetTextColor(0, 0, 0);
	            $pdf->SetFont('Calibri', '', $norma_font_size);
	            
	            $nombre_width = 30;
	            $edad_width = 30;

	            $initial_x = 10;
	            $recursive_x = $initial_x;

	            $pdf->SetFont('Calibri-Bold', '', 9);

	            $y = $pdf->GetY();


	            $pdf->SetX($recursive_x);
	            $pdf->Multicell($nombre_width+1, 5, utf8_decode("Procedencia"), "LTRB", "C");

	            $recursive_x += $nombre_width+1;
	            $pdf->SetY($y);
	            $pdf->SetX($recursive_x);
	            $pdf->Multicell($nombre_width, 5, utf8_decode("Destino"), "TRB", "C");

	            $recursive_x += $nombre_width;
	            $pdf->SetY($y);
	            $pdf->SetX($recursive_x);
	            $pdf->Multicell($nombre_width, 5, utf8_decode("Estado"), "TRB", "C");

	            $recursive_x += $nombre_width;
	            $pdf->SetY($y);
	            $pdf->SetX($recursive_x);
	            $pdf->Multicell($edad_width+1, 5, utf8_decode("Fecha"), "TRB", "C");

	            $recursive_x += $edad_width+1;

	            $pdf->SetY($y);
	            $pdf->SetX($recursive_x);
	            $pdf->Multicell($nombre_width+1, 5, utf8_decode("Observación"), "TRB", "C");


	            $recursive_x += $nombre_width+1;
	            $pdf->SetY($y);
	            $pdf->SetX($recursive_x);
	            $pdf->Multicell($nombre_width, 5, utf8_decode("Usuario"), "TRB", "C");

	            $rows_number = 0;
	            $initial_column = 36;

	            $details = DetailOrder::whereOrderId($order->id)
	            		->with('office')
						->with('office_origen')
						->with('state')
						->with('user.entity')
	            		->get();

	            foreach ($details as $keyu => $detail) {

	                    $initial_x = 10;   
	                    $recursive_x = $initial_x;
	                    $rows = [];
	                    $y_max = 0;

	                    $y = $pdf->GetY();
	                    $y1 = $pdf->GetY();
	                    $pdf->SetX($recursive_x);
	                    $x = $pdf->GetX();
	                    $pdf->Multicell($nombre_width+1, 5, utf8_decode($detail->office_origen->name), "LT", "L");
	                    $y2 = $pdf->GetY();
	                    $y_last = $y2-$y1;

	                    $rows[] = array(
	                        'x' => $x,
	                        'y' => $y2,
	                        'total' => $y_last,
	                        'width' => $nombre_width+1,
	                    );

	                    if ($y_last > $y_max) {
	                        $y_max = $y_last;
	                    }

	                    $recursive_x += $nombre_width+1;
	                    $pdf->SetY($y);
	                    $y1 = $pdf->GetY();
	                    $pdf->SetX($recursive_x);
	                    $x = $pdf->GetX();
	                    $pdf->Multicell($nombre_width, 5, utf8_decode($detail->office ? $detail->office->name : "Varias oficinas"), "LT", "L");
	                    $y2 = $pdf->GetY();

	                    $y_last = $y2-$y1;

	                    $rows[] = array(
	                        'x' => $x,
	                        'y' => $y2,
	                        'total' => $y_last,
	                        'width' => $nombre_width,

	                    );

	                    if ($y_last > $y_max) {
	                        $y_max = $y_last;
	                    }

	                    $recursive_x += $nombre_width;
	                    $pdf->SetY($y);
	                    $y1 = $pdf->GetY();
	                    $pdf->SetX($recursive_x);
	                    $x = $pdf->GetX();
	                    $pdf->Multicell($nombre_width, 5, utf8_decode($detail->state->name), "LT", "L");
	                    $y2 = $pdf->GetY();

	                    $y_last = $y2-$y1;

	                    $rows[] = array(
	                        'x' => $x,
	                        'y' => $y2,
	                        'total' => $y_last,
	                        'width' => $nombre_width,
	                    );

	                    if ($y_last > $y_max) {
	                        $y_max = $y_last;
	                    }

	                    $recursive_x += $nombre_width;
	                    $pdf->SetY($y);
	                    $y1 = $pdf->GetY();
	                    $pdf->SetX($recursive_x);
	                    $x = $pdf->GetX();
	                    $pdf->Multicell($edad_width+1, 5, utf8_decode(Carbon::parse($detail->created_at)->format('d/m/Y H:i:s')), "LTR", "L");
	                    $y2 = $pdf->GetY();

	                    $y_last = $y2-$y1;

	                    $rows[] = array(
	                        'x' => $x,
	                        'y' => $y2,
	                        'total' => $y_last,
	                        'width' => $edad_width+1,
	                    );

	                    if ($y_last > $y_max) {
	                        $y_max = $y_last;
	                    }

	                    $recursive_x += $edad_width+1;
	                    $pdf->SetY($y);
	                    $y1 = $pdf->GetY();
	                    $pdf->SetX($recursive_x);
	                    $x = $pdf->GetX();
	                    $pdf->Multicell($nombre_width+1, 5, utf8_decode($detail->observations), "LTR", "L");
	                    $y2 = $pdf->GetY();

	                    $y_last = $y2-$y1;

	                    $rows[] = array(
	                        'x' => $x,
	                        'y' => $y2,
	                        'total' => $y_last,
	                        'width' => $nombre_width+1,
	                    );

	                    if ($y_last > $y_max) {
	                        $y_max = $y_last;
	                    }

	                    $recursive_x += $nombre_width+1;
	                    $pdf->SetY($y);
	                    $y1 = $pdf->GetY();
	                    $pdf->SetX($recursive_x);
	                    $x = $pdf->GetX();
	                    $pdf->Multicell($nombre_width, 5, utf8_decode($detail->user ? $detail->user->name : ""), "LTR", "L");
	                    $y2 = $pdf->GetY();

	                    $y_last = $y2-$y1;

	                    $rows[] = array(
	                        'x' => $x,
	                        'y' => $y2,
	                        'total' => $y_last,
	                        'width' => $nombre_width,
	                    );

	                    if ($y_last > $y_max) {
	                        $y_max = $y_last;
	                    }

	                    foreach ($rows as $keyi => $row) {
	                        if ($y_max > $row['total']) {
	                            $pdf->SetY($row['y']);
	                            $pdf->SetX($row['x']);
	                            $pdf->Multicell($row['width'], $y_max-$row['total'], utf8_decode(""), "LRB", "L");

	                        } else if($y_max == $row['total']) {
	                            $pdf->SetY($row['y']);
	                            $pdf->SetX($row['x']);
	                            $pdf->Multicell($row['width'], 0, utf8_decode(""), "B", "L");

	                        }
	                    }

		                if ($y2 > 236) {
							$pdf->AddPage();
						}

	            }

	            // while ($rows_number < 2) {

	            //     if (true) {


	                    
	            //     }

	            //     $initial_column += 14;
	            //     $rows_number++;
	            // }

		        $pdf->Ln(3);

		  //       $counter++;

				// if ($counter%6 == 0) {
				// 	$pdf->AddPage();
				// }
			}	


        }

			//$pdf->Ln(3);
        $pdf->Output();
                // $question_font = "Calibri";
                // $question_font_size = $norma_font_size;
                // $main_answers_font = "Calibri-Bold";
                // $main_answers_font_size = $norma_font_size;


                // $pdf->SetFont($question_font, '', $question_font_size);
                // $pdf->cell(7, 10, utf8_decode("DNI: "), 0, "", "L");
                // $pdf->SetFont($main_answers_font, '', $main_answers_font_size);
                // $pdf->cell(20, 10, utf8_decode($data[10]), 0, "", "L");


                // $pdf->SetX(100);
                // $pdf->SetFont($question_font, '', $question_font_size);
                // $pdf->cell(27.5, 10, utf8_decode("Cod. Universitario: "), 0, "", "L");
                // $pdf->SetFont($main_answers_font, '', $main_answers_font_size);
                // $pdf->cell(5, 10, utf8_decode($data[13]), 0, "", "L");

                // $pdf->Ln($normal_space);

	}

	public function solicitude_report_fecha_pdf(Request $request)
	{

		$start_date = "";

		if ($request->has('inicio')) {
			$start_date = Carbon::createFromFormat('d/m/Y', $request->inicio);
		}

		//$end_date = Carbon::now();
		$end_date = "";

		if ($request->has('fin')) {
			$end_date = Carbon::createFromFormat('d/m/Y', $request->fin);
		}		

		$office_id = $request->oficina;

		$user = User::with('entity')->find(Auth::user()->id);
		$entity = Entity::with('office')->find($user->entity_id);
		$current_office_id = $entity->office_id;

		$admin = false;

		if ($user->role_id == 2) {
			$admin = true;
		}

		$orders = DB::table('orders')
			->orderBy('details_order.id', 'desc')
			->join('details_order', 'orders.id', '=', 'details_order.order_id')
			->join('document_types', 'orders.document_type_id', '=', 'document_types.id')
			->join('entities', 'orders.entity_id', '=', 'entities.id')
			->leftJoin('offices', 'details_order.office_id_origen', '=', 'offices.id')
			->join('document_statuses', 'details_order.status', '=', 'document_statuses.id')
			->leftJoin('offices as office_destination', 'details_order.office_id', '=', 'office_destination.id')
			->where('orders.deleted_at', null)
			->where('orders.parent_order_id', 0);

		if ($request->has('inicio')) {
			$orders = $orders->whereDate('orders.created_at', '>=', $start_date->format('Y-m-d'))
				->whereDate('orders.created_at', '<=', $end_date->format('Y-m-d'));
		}

		$orders = $orders->where('details_order.office_id_origen', $office_id);

		$orders = $orders->select(['orders.id', 'orders.code', 'document_types.name as document_type_name', 'orders.subject as subject', 'entities.name as name', 'entities.paternal_surname as paternal_surname', 'entities.maternal_surname as maternal_surname', 'entities.identity_document as identity_document', 'entities.type_document as type_document', 'entities.cellphone as cellphone', 'entities.email as email', 'entities.address as address' , 'offices.name as office_name', 'orders.status as status', 'document_statuses.name as status_name', 'orders.attached_file', 'orders.created_at', 'offices.name as office_parent_name', 'office_destination.name as office_destination_name', 'orders.office_id as current_office_id', 'orders.office_id_origen', 'orders.multiple', 'orders.internal_code', 'orders.folios'])
				->groupBy('orders.id')
				->get();		

		$pdf = app('Fpdf');
        $pdf->AddPage();

		$pdf->AddFont('Calibri','','calibri.php');
        $pdf->AddFont('Calibri-Bold','','calibri_b.php');
        $pdf->AddFont('Calibri-Italic','','calibri_i.php');
        $pdf->AddFont('Calibri-BoldItalic','','calibri_bi.php');
        $normal_space = 4.5;
        $norma_font_size = 10;
        $space_between_sections = 9;

        $pdf->Image('assets/cabeceras/muni_pachia.png', 13 , 5, 25);
       // $pdf->Image(asset('assets/cabeceras/unjbg-escudo.png'), 170 , 5, 25);

        $pdf->Ln();

        $pdf->SetY(10);


        $pdf->SetFont('Calibri', '', 13);
        $pdf->Cell(0, 8, utf8_decode('MUNICIPALIDAD DISTRITAL DE PACHÍA'), 0, "", "C");
        $pdf->Ln();

        $pdf->SetFont('Calibri-Bold', '', 12);
        $pdf->Cell(0, 8, utf8_decode('ÁREA DE MESA DE PARTES'), 0, "", "C");
        $pdf->Ln();

        $pdf->SetFont('Calibri', '', 9);
        $pdf->cell(4);
        $pdf->cell(30, 10, utf8_decode("Avenida Arias Araguez S/N Tacna - Pachía"), 0, "", "L");


        $pdf->SetX(98);
        $pdf->cell(10, 10, utf8_decode("Tacna - Perú"), 0, "", "C");

        //$pdf->SetY(26);
        $pdf->SetX(162);
        //$pdf->SetFont('Calibri-Bold', '', 8);
        //$pdf->cell(10, 6, utf8_decode("AGENCIA:"), 0, "", "R");

        $pdf->cell(30, 10, utf8_decode("Teléfono: 968 360 022"), 0, "", "R");
        $pdf->Ln(8);

        $pdf->cell(190, 1, "", "TB", "", "C");
        //$pdf->Ln();

        $pdf->SetY(40-2);

        $pdf->SetFont('Calibri-Bold', '', 13);
        $pdf->Cell(0, 8, utf8_decode('REPORTE DE TRÁMITES'), 0, "", "C");

        //$pdf->Ln(5);
        $pdf->SetY(52-3);

        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Calibri-Bold', '', 12);
        $pdf->Cell(0, 5, utf8_decode('##'), 0, "", "L", true);
        $pdf->Ln(3);

        $pdf->SetTextColor(0, 0, 0);

		$pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Calibri-Bold', '', 13);

        $pdf->Ln(5);

        $counter = 0;

        foreach ($orders as $key => $order) {

			if($order->multiple && !$admin){
				if($order->office_id_origen != $current_office_id){
			
				    if ($pdf->GetY() > 230) {
						$pdf->AddPage();
					}

					$pdf->SetFont('Calibri-Bold', '', 13);

					$pdf->Cell(30, 8, utf8_decode($order->code), 0, "", "L");

					if($order->internal_code){
						$pdf->Cell(50, 8, utf8_decode($order->internal_code), 0, "", "L");
					}

			        $pdf->Ln(5);

			        $pdf->Cell(25, 8, utf8_decode('Remitente'), 0, "", "L");
			        
			        $pdf->SetFont('Calibri', '', 12);
			        $pdf->Cell(20, 8, utf8_decode($order->name." ".$order->paternal_surname." ".$order->maternal_surname), 0, "", "L");

			        $pdf->Ln(5);

			        $pdf->SetFont('Calibri-Bold', '', 13);
			        $pdf->Cell(25, 8, utf8_decode('Fecha'), 0, "", "L");
			        $pdf->SetFont('Calibri', '', 12);
			        $pdf->Cell(40, 8, utf8_decode($order->created_at), 0, "", "L");

			        $pdf->SetFont('Calibri-Bold', '', 13);
					$pdf->Cell(25, 8, utf8_decode('Folios'), 0, "", "L");
			        $pdf->SetFont('Calibri', '', 12);
			        $pdf->Cell(20, 8, utf8_decode($order->folios), 0, "", "L");

			        $pdf->Ln(5);

			        $pdf->SetFont('Calibri-Bold', '', 13);
			        $pdf->Cell(25, 8, utf8_decode('Asunto'), 0, "", "L");
			        $pdf->SetFont('Calibri', '', 12);
					$pdf->MultiCell(180, 8, utf8_decode($order->subject), 0, "L", 0);
			        $pdf->Ln(2);

		            $pdf->SetTextColor(0, 0, 0);
		            $pdf->SetFont('Calibri', '', $norma_font_size);
		            
		            $nombre_width = 30;
		            $edad_width = 30;

		            $initial_x = 10;
		            $recursive_x = $initial_x;

		            $pdf->SetFont('Calibri-Bold', '', 9);

		            $y = $pdf->GetY();
		            $pdf->SetX($recursive_x);
		            $pdf->Multicell($nombre_width+1, 5, utf8_decode("Procedencia"), "LTRB", "C");

		            $recursive_x += $nombre_width+1;
		            $pdf->SetY($y);
		            $pdf->SetX($recursive_x);
		            $pdf->Multicell($nombre_width, 5, utf8_decode("Destino"), "TRB", "C");

		            $recursive_x += $nombre_width;
		            $pdf->SetY($y);
		            $pdf->SetX($recursive_x);
		            $pdf->Multicell($nombre_width, 5, utf8_decode("Estado"), "TRB", "C");

		            $recursive_x += $nombre_width;
		            $pdf->SetY($y);
		            $pdf->SetX($recursive_x);
		            $pdf->Multicell($edad_width+1, 5, utf8_decode("Fecha"), "TRB", "C");

		            $recursive_x += $edad_width+1;

		            $pdf->SetY($y);
		            $pdf->SetX($recursive_x);
		            $pdf->Multicell($nombre_width+1, 5, utf8_decode("Observación"), "TRB", "C");


		            $recursive_x += $nombre_width+1;
		            $pdf->SetY($y);
		            $pdf->SetX($recursive_x);
		            $pdf->Multicell($nombre_width, 5, utf8_decode("Usuario"), "TRB", "C");

		            $rows_number = 0;
		            $initial_column = 36;

		            $details = DetailOrder::whereOrderId($order->id)
		            		->with('office')
							->with('office_origen')
							->with('state')
							->with('user.entity')
		            		->get();

		            foreach ($details as $keyu => $detail) {

		                    $initial_x = 10;   
		                    $recursive_x = $initial_x;
		                    $rows = [];
		                    $y_max = 0;

		                    $y = $pdf->GetY();
		                    $y1 = $pdf->GetY();
		                    $pdf->SetX($recursive_x);
		                    $x = $pdf->GetX();
		                    $pdf->Multicell($nombre_width+1, 5, utf8_decode($detail->office_origen->name), "LT", "L");
		                    $y2 = $pdf->GetY();
		                    $y_last = $y2-$y1;

		                    $rows[] = array(
		                        'x' => $x,
		                        'y' => $y2,
		                        'total' => $y_last,
		                        'width' => $nombre_width+1,
		                    );

		                    if ($y_last > $y_max) {
		                        $y_max = $y_last;
		                    }

		                    $recursive_x += $nombre_width+1;
		                    $pdf->SetY($y);
		                    $y1 = $pdf->GetY();
		                    $pdf->SetX($recursive_x);
		                    $x = $pdf->GetX();
		                    $pdf->Multicell($nombre_width, 5, utf8_decode($detail->office ? $detail->office->name : "Varias oficinas"), "LT", "L");
		                    $y2 = $pdf->GetY();

		                    $y_last = $y2-$y1;

		                    $rows[] = array(
		                        'x' => $x,
		                        'y' => $y2,
		                        'total' => $y_last,
		                        'width' => $nombre_width,

		                    );

		                    if ($y_last > $y_max) {
		                        $y_max = $y_last;
		                    }

		                    $recursive_x += $nombre_width;
		                    $pdf->SetY($y);
		                    $y1 = $pdf->GetY();
		                    $pdf->SetX($recursive_x);
		                    $x = $pdf->GetX();
		                    $pdf->Multicell($nombre_width, 5, utf8_decode($detail->state->name), "LT", "L");
		                    $y2 = $pdf->GetY();

		                    $y_last = $y2-$y1;

		                    $rows[] = array(
		                        'x' => $x,
		                        'y' => $y2,
		                        'total' => $y_last,
		                        'width' => $nombre_width,
		                    );

		                    if ($y_last > $y_max) {
		                        $y_max = $y_last;
		                    }

		                    $recursive_x += $nombre_width;
		                    $pdf->SetY($y);
		                    $y1 = $pdf->GetY();
		                    $pdf->SetX($recursive_x);
		                    $x = $pdf->GetX();
		                    $pdf->Multicell($edad_width+1, 5, utf8_decode(Carbon::parse($detail->created_at)->format('d/m/Y H:i:s')), "LTR", "L");
		                    $y2 = $pdf->GetY();

		                    $y_last = $y2-$y1;

		                    $rows[] = array(
		                        'x' => $x,
		                        'y' => $y2,
		                        'total' => $y_last,
		                        'width' => $edad_width+1,
		                    );

		                    if ($y_last > $y_max) {
		                        $y_max = $y_last;
		                    }

		                    $recursive_x += $edad_width+1;
		                    $pdf->SetY($y);
		                    $y1 = $pdf->GetY();
		                    $pdf->SetX($recursive_x);
		                    $x = $pdf->GetX();
		                    $pdf->Multicell($nombre_width+1, 5, utf8_decode($detail->observations), "LTR", "L");
		                    $y2 = $pdf->GetY();

		                    $y_last = $y2-$y1;

		                    $rows[] = array(
		                        'x' => $x,
		                        'y' => $y2,
		                        'total' => $y_last,
		                        'width' => $nombre_width+1,
		                    );

		                    if ($y_last > $y_max) {
		                        $y_max = $y_last;
		                    }

		                    $recursive_x += $nombre_width+1;
		                    $pdf->SetY($y);
		                    $y1 = $pdf->GetY();
		                    $pdf->SetX($recursive_x);
		                    $x = $pdf->GetX();
		                    $pdf->Multicell($nombre_width, 5, utf8_decode($detail->user ? $detail->user->name : ""), "LTR", "L");
		                    $y2 = $pdf->GetY();

		                    $y_last = $y2-$y1;

		                    $rows[] = array(
		                        'x' => $x,
		                        'y' => $y2,
		                        'total' => $y_last,
		                        'width' => $nombre_width,
		                    );

		                    if ($y_last > $y_max) {
		                        $y_max = $y_last;
		                    }

		                    foreach ($rows as $keyi => $row) {
		                        if ($y_max > $row['total']) {
		                            $pdf->SetY($row['y']);
		                            $pdf->SetX($row['x']);
		                            $pdf->Multicell($row['width'], $y_max-$row['total'], utf8_decode(""), "LRB", "L");

		                        } else if($y_max == $row['total']) {
		                            $pdf->SetY($row['y']);
		                            $pdf->SetX($row['x']);
		                            $pdf->Multicell($row['width'], 0, utf8_decode(""), "B", "L");

		                        }
		                    }

			                if ($y2 > 236) {
								$pdf->AddPage();
							}

		            }

		            // while ($rows_number < 2) {

		            //     if (true) {


		                    
		            //     }

		            //     $initial_column += 14;
		            //     $rows_number++;
		            // }

			        $pdf->Ln(3);

			  //       $counter++;

					// if ($counter%3 == 0) {
					// 	$pdf->AddPage();
					// }

				}

			} else {

			    if ($pdf->GetY() > 230) {
					$pdf->AddPage();
				}

				$pdf->SetFont('Calibri-Bold', '', 13);

				$pdf->Cell(30, 8, utf8_decode($order->code), 0, "", "L");

				if($order->internal_code){
					$pdf->Cell(50, 8, utf8_decode($order->internal_code), 0, "", "L");
				}

		        $pdf->Ln(5);

		        $pdf->Cell(25, 8, utf8_decode('Remitente'), 0, "", "L");
		        
		        $pdf->SetFont('Calibri', '', 12);
		        $pdf->Cell(20, 8, utf8_decode($order->name." ".$order->paternal_surname." ".$order->maternal_surname), 0, "", "L");

		        $pdf->Ln(5);

		        $pdf->SetFont('Calibri-Bold', '', 13);
		        $pdf->Cell(25, 8, utf8_decode('Fecha'), 0, "", "L");
		        $pdf->SetFont('Calibri', '', 12);
		        $pdf->Cell(40, 8, utf8_decode($order->created_at), 0, "", "L");

		        $pdf->SetFont('Calibri-Bold', '', 13);
				$pdf->Cell(25, 8, utf8_decode('Folios'), 0, "", "L");
		        $pdf->SetFont('Calibri', '', 12);
		        $pdf->Cell(20, 8, utf8_decode($order->folios), 0, "", "L");

		        $pdf->Ln(5);

		        $pdf->SetFont('Calibri-Bold', '', 13);
		        $pdf->Cell(25, 8, utf8_decode('Asunto'), 0, "", "L");
		        $pdf->SetFont('Calibri', '', 12);
				$pdf->MultiCell(180, 8, utf8_decode($order->subject), 0, "L", 0);
		        $pdf->Ln(2);

	            $pdf->SetTextColor(0, 0, 0);
	            $pdf->SetFont('Calibri', '', $norma_font_size);
	            
	            $nombre_width = 30;
	            $edad_width = 30;

	            $initial_x = 10;
	            $recursive_x = $initial_x;

	            $pdf->SetFont('Calibri-Bold', '', 9);

	            $y = $pdf->GetY();
	            $pdf->SetX($recursive_x);
	            $pdf->Multicell($nombre_width+1, 5, utf8_decode("Procedencia"), "LTRB", "C");

	            $recursive_x += $nombre_width+1;
	            $pdf->SetY($y);
	            $pdf->SetX($recursive_x);
	            $pdf->Multicell($nombre_width, 5, utf8_decode("Destino"), "TRB", "C");

	            $recursive_x += $nombre_width;
	            $pdf->SetY($y);
	            $pdf->SetX($recursive_x);
	            $pdf->Multicell($nombre_width, 5, utf8_decode("Estado"), "TRB", "C");

	            $recursive_x += $nombre_width;
	            $pdf->SetY($y);
	            $pdf->SetX($recursive_x);
	            $pdf->Multicell($edad_width+1, 5, utf8_decode("Fecha"), "TRB", "C");

	            $recursive_x += $edad_width+1;

	            $pdf->SetY($y);
	            $pdf->SetX($recursive_x);
	            $pdf->Multicell($nombre_width+1, 5, utf8_decode("Observación"), "TRB", "C");


	            $recursive_x += $nombre_width+1;
	            $pdf->SetY($y);
	            $pdf->SetX($recursive_x);
	            $pdf->Multicell($nombre_width, 5, utf8_decode("Usuario"), "TRB", "C");

	            $rows_number = 0;
	            $initial_column = 36;

	            $details = DetailOrder::whereOrderId($order->id)
	            		->with('office')
						->with('office_origen')
						->with('state')
						->with('user.entity')
	            		->get();

	            foreach ($details as $keyu => $detail) {

	                    $initial_x = 10;   
	                    $recursive_x = $initial_x;
	                    $rows = [];
	                    $y_max = 0;

	                    $y = $pdf->GetY();
	                    $y1 = $pdf->GetY();
	                    $pdf->SetX($recursive_x);
	                    $x = $pdf->GetX();
	                    $pdf->Multicell($nombre_width+1, 5, utf8_decode($detail->office_origen->name), "LT", "L");
	                    $y2 = $pdf->GetY();
	                    $y_last = $y2-$y1;

	                    $rows[] = array(
	                        'x' => $x,
	                        'y' => $y2,
	                        'total' => $y_last,
	                        'width' => $nombre_width+1,
	                    );

	                    if ($y_last > $y_max) {
	                        $y_max = $y_last;
	                    }

	                    $recursive_x += $nombre_width+1;
	                    $pdf->SetY($y);
	                    $y1 = $pdf->GetY();
	                    $pdf->SetX($recursive_x);
	                    $x = $pdf->GetX();
	                    $pdf->Multicell($nombre_width, 5, utf8_decode($detail->office ? $detail->office->name : "Varias oficinas"), "LT", "L");
	                    $y2 = $pdf->GetY();

	                    $y_last = $y2-$y1;

	                    $rows[] = array(
	                        'x' => $x,
	                        'y' => $y2,
	                        'total' => $y_last,
	                        'width' => $nombre_width,

	                    );

	                    if ($y_last > $y_max) {
	                        $y_max = $y_last;
	                    }

	                    $recursive_x += $nombre_width;
	                    $pdf->SetY($y);
	                    $y1 = $pdf->GetY();
	                    $pdf->SetX($recursive_x);
	                    $x = $pdf->GetX();
	                    $pdf->Multicell($nombre_width, 5, utf8_decode($detail->state->name), "LT", "L");
	                    $y2 = $pdf->GetY();

	                    $y_last = $y2-$y1;

	                    $rows[] = array(
	                        'x' => $x,
	                        'y' => $y2,
	                        'total' => $y_last,
	                        'width' => $nombre_width,
	                    );

	                    if ($y_last > $y_max) {
	                        $y_max = $y_last;
	                    }

	                    $recursive_x += $nombre_width;
	                    $pdf->SetY($y);
	                    $y1 = $pdf->GetY();
	                    $pdf->SetX($recursive_x);
	                    $x = $pdf->GetX();
	                    $pdf->Multicell($edad_width+1, 5, utf8_decode(Carbon::parse($detail->created_at)->format('d/m/Y H:i:s')), "LTR", "L");
	                    $y2 = $pdf->GetY();

	                    $y_last = $y2-$y1;

	                    $rows[] = array(
	                        'x' => $x,
	                        'y' => $y2,
	                        'total' => $y_last,
	                        'width' => $edad_width+1,
	                    );

	                    if ($y_last > $y_max) {
	                        $y_max = $y_last;
	                    }

	                    $recursive_x += $edad_width+1;
	                    $pdf->SetY($y);
	                    $y1 = $pdf->GetY();
	                    $pdf->SetX($recursive_x);
	                    $x = $pdf->GetX();
	                    $pdf->Multicell($nombre_width+1, 5, utf8_decode($detail->observations), "LTR", "L");
	                    $y2 = $pdf->GetY();

	                    $y_last = $y2-$y1;

	                    $rows[] = array(
	                        'x' => $x,
	                        'y' => $y2,
	                        'total' => $y_last,
	                        'width' => $nombre_width+1,
	                    );

	                    if ($y_last > $y_max) {
	                        $y_max = $y_last;
	                    }

	                    $recursive_x += $nombre_width+1;
	                    $pdf->SetY($y);
	                    $y1 = $pdf->GetY();
	                    $pdf->SetX($recursive_x);
	                    $x = $pdf->GetX();
	                    $pdf->Multicell($nombre_width, 5, utf8_decode($detail->user ? $detail->user->name : ""), "LTR", "L");
	                    $y2 = $pdf->GetY();

	                    $y_last = $y2-$y1;

	                    $rows[] = array(
	                        'x' => $x,
	                        'y' => $y2,
	                        'total' => $y_last,
	                        'width' => $nombre_width,
	                    );

	                    if ($y_last > $y_max) {
	                        $y_max = $y_last;
	                    }

	                    foreach ($rows as $keyi => $row) {
	                        if ($y_max > $row['total']) {
	                            $pdf->SetY($row['y']);
	                            $pdf->SetX($row['x']);
	                            $pdf->Multicell($row['width'], $y_max-$row['total'], utf8_decode(""), "LRB", "L");

	                        } else if($y_max == $row['total']) {
	                            $pdf->SetY($row['y']);
	                            $pdf->SetX($row['x']);
	                            $pdf->Multicell($row['width'], 0, utf8_decode(""), "B", "L");

	                        }
	                    }


    	                if ($y2 > 236) {
							$pdf->AddPage();
						}


	            }

	            // while ($rows_number < 2) {

	            //     if (true) {


	                    
	            //     }

	            //     $initial_column += 14;
	            //     $rows_number++;
	            // }

		        $pdf->Ln(3);

		  //       $counter++;

				// if ($counter%3 == 0) {
				// 	$pdf->AddPage();
				// }

			}	


        }

			//$pdf->Ln(3);
        $pdf->Output();
                // $question_font = "Calibri";
                // $question_font_size = $norma_font_size;
                // $main_answers_font = "Calibri-Bold";
                // $main_answers_font_size = $norma_font_size;


                // $pdf->SetFont($question_font, '', $question_font_size);
                // $pdf->cell(7, 10, utf8_decode("DNI: "), 0, "", "L");
                // $pdf->SetFont($main_answers_font, '', $main_answers_font_size);
                // $pdf->cell(20, 10, utf8_decode($data[10]), 0, "", "L");


                // $pdf->SetX(100);
                // $pdf->SetFont($question_font, '', $question_font_size);
                // $pdf->cell(27.5, 10, utf8_decode("Cod. Universitario: "), 0, "", "L");
                // $pdf->SetFont($main_answers_font, '', $main_answers_font_size);
                // $pdf->cell(5, 10, utf8_decode($data[13]), 0, "", "L");

                // $pdf->Ln($normal_space);


	}


	public function solicitude_report_pdf_sent()
	{
		$pdf = app('Fpdf');
        $pdf->AddPage();


		$pdf->AddFont('Calibri','','calibri.php');
        $pdf->AddFont('Calibri-Bold','','calibri_b.php');
        $pdf->AddFont('Calibri-Italic','','calibri_i.php');
        $pdf->AddFont('Calibri-BoldItalic','','calibri_bi.php');
        $normal_space = 4.5;
        $norma_font_size = 10;
        $space_between_sections = 9;
        $pdf->Rect(5, 5, 200, 287, 'D');

        $pdf->Image('assets/cabeceras/muni_pachia.png', 13 , 10, 60);
       // $pdf->Image(asset('assets/cabeceras/unjbg-escudo.png'), 170 , 5, 25);

        $pdf->Ln();

        $pdf->SetY(15);

        $pdf->SetFont('Calibri', '', 20);
        $pdf->Cell(0, 8, utf8_decode('Constancia de recepción de solicitud'), 0, "", "R");
        $pdf->Ln();

        $pdf->SetFont('Calibri', '', 12);
        $pdf->Cell(0, 8, utf8_decode('Mesa de partes virtual de la Municipalidad Distrital de Pachía'), 0, "", "R");
        $pdf->Ln();

        // $pdf->SetFont('Calibri', '', 9);
        // $pdf->cell(4);
        // $pdf->cell(30, 10, utf8_decode("Avenida Arias Araguez S/N Tacna - Pachía"), 0, "", "L");

        // $pdf->SetX(98);
        // $pdf->cell(10, 10, utf8_decode("Tacna - Perú"), 0, "", "C");

        // $pdf->SetX(162);

        // $pdf->cell(30, 10, utf8_decode("Teléfono: 968 360 022"), 0, "", "R");
        // $pdf->Ln(8);
        $pdf->SetY(35);
        $pdf->SetTextColor(211, 211, 211);
        $pdf->cell(190, 1, "", "B", "", "C");

        //$pdf->Ln();
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetY(40-2);
        $pdf->SetFont('Calibri-Bold', '', 13);
        $pdf->Cell(0, 8, utf8_decode('Código'), 0, "", "C");
        $pdf->Ln();
        $pdf->SetFont('Calibri', '', 20);
        $pdf->SetTextColor(240, 128, 128);
        $pdf->Cell(0, 8, utf8_decode('62b375924b9a5'), 0, "", "C");

        $pdf->SetY(70-3);

        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('Calibri-Bold', '', 12);
        //$pdf->Cell(0, 5, utf8_decode('##'), 0, "", "L", true);
        $pdf->Ln(3);

        $pdf->SetTextColor(0, 0, 0);
        $pdf->SetFont('Calibri-Bold', '', 13);

        $pdf->Cell(90, 8, utf8_decode('DNI'), 0, "", "C");
        $pdf->Cell(90, 8, utf8_decode('Solicitante'), 0, "", "C");

        $pdf->Ln();
        $pdf->SetFont('Calibri', '', 13);
		$pdf->Cell(90, 8, utf8_decode('10203040'), 0, "", "C");
        $pdf->Cell(90, 8, utf8_decode('CARLOS ANDRÉS QUISPE ALEJOS'), 0, "", "C");

        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFont('Calibri-Bold', '', 13);
        $pdf->Cell(90, 8, utf8_decode('RUC'), 0, "", "C");
        $pdf->Cell(90, 8, utf8_decode('Razón Social'), 0, "", "C");

		$pdf->Ln();
        $pdf->SetFont('Calibri', '', 13);
		$pdf->Cell(90, 8, utf8_decode('-'), 0, "", "C");
        $pdf->Cell(90, 8, utf8_decode('-'), 0, "", "C");

        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFont('Calibri-Bold', '', 13);
        $pdf->Cell(180, 8, utf8_decode('Asunto'), 0, "", "C");
		$pdf->Ln();
		$pdf->SetFont('Calibri', '', 13);
		$pdf->Cell(180, 8, utf8_decode('RECURSO DE APELACIÓN EN MATERIA TRIBUTARIA'), 0, "", "C");

        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();
        $pdf->SetFont('Calibri-Bold', '', 13);
        $pdf->Cell(90, 8, utf8_decode('Fecha'), 0, "", "C");
        $pdf->Cell(90, 8, utf8_decode('Nº exp. a reconsiderar'), 0, "", "C");

		$pdf->Ln();
        $pdf->SetFont('Calibri', '', 13);
		$pdf->Cell(90, 8, utf8_decode('22/06/2022 15:03:30'), 0, "", "C");
        $pdf->Cell(90, 8, utf8_decode('-'), 0, "", "C");

        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();

		$pdf->SetFont('Calibri-Bold', '', 13);
        $pdf->Cell(90, 8, utf8_decode('Nro. folios'), 0, "", "C");
        $pdf->Cell(90, 8, utf8_decode('Cant. anexos'), 0, "", "C");

        $pdf->Ln();
        $pdf->SetFont('Calibri', '', 13);
		$pdf->Cell(90, 8, utf8_decode('11'), 0, "", "C");
        $pdf->Cell(90, 8, utf8_decode('0'), 0, "", "C");

        $pdf->Ln();
        $pdf->Ln();
        $pdf->Ln();

        $pdf->SetFont('Calibri', '', 13);
		$pdf->MultiCell(180, 8, utf8_decode('El presente documento es una constancia de haber presentado una solicitud en la mesa de partes virtual de la Municipalidad Distrital de San Martín de Porres. Se procederá a su revisión y la asignación de número de expediente una vez sea aceptada.'), 0, "L", 0);

        $pdf->Output();


	}

	public function get_view_received(Request $request)
	{

		if ($request) {

			$start_date = "";

			if ($request->has('inicio')) {
				$start_date = Carbon::createFromFormat('d/m/Y', $request->inicio);
			}

			$end_date = "";

			if ($request->has('fin')) {
				$end_date = Carbon::createFromFormat('d/m/Y', $request->fin);
			}

			$user = User::with('entity')->find(Auth::user()->id);
			$entity = Entity::find($user->entity_id);

			$office_id = $user->entity->office_id;

			$offices = Office::where('id', '!=', $office_id)
				->get();

			$text = trim($request->get('searchText'));
			$document_status = 3;

			$orders = DB::table('orders')
				->orderBy('orders.id', 'desc')
				->join('document_types', 'orders.document_type_id', '=', 'document_types.id')
				->join('entities', 'orders.entity_id', '=', 'entities.id')
				->leftJoin('offices as entity_office', 'entities.office_id', '=', 'entity_office.id')
				->join('offices', 'orders.office_id', '=', 'offices.id')
				->join('document_statuses', 'orders.status', '=', 'document_statuses.id')
				->leftJoin('orders as parent_order', 'orders.parent_order_id', '=', 'parent_order.id')
				->leftJoin('offices as office_parent', 'parent_order.office_id', '=', 'office_parent.id')
				->where('orders.deleted_at', null);

				if ($request->has('inicio')) {
					$orders = $orders->whereDate('orders.created_at', '>=', $start_date->format('Y-m-d'))
						->whereDate('orders.created_at', '<=', $end_date->format('Y-m-d'));
				}

				if ($text) {
					$orders = $orders->where(function ($query) use($text) {
               			$query->where('orders.code', "LIKE", "%$text%");
                    		//->orWhere('entities.identity_document', "LIKE", "%$text%");
           			});
				}

				if ($document_status) {
					$orders = $orders->where('orders.status', $document_status);
				}

			$orders = $orders->select(['orders.id', 'orders.code', 'document_types.name as document_type_name', 'orders.number as number', 'orders.subject as subject', 'entities.name as name', 'entities.paternal_surname as paternal_surname', 'entities.maternal_surname as maternal_surname', 'entities.identity_document as identity_document', 'entities.type_document as type_document', 'entities.cellphone as cellphone', 'entities.email as email', 'entities.address as address' , 'offices.name as office_name', 'orders.status as status', 'document_statuses.name as status_name', 'orders.attached_file', 'orders.created_at', 'orders.parent_order_id', 'office_parent.name as office_parent_name', 'orders.term_end', 'orders.term', 'orders.internal_code', 'entity_office.name as entity_office_name']);
				// ->paginate(20);

			$document_statuses = DocumentState::all();

			$admin = false;

			$orders = $orders->where('orders.office_id', $office_id);

			$tupa = DB::table('tupa')
				->where('deleted_at', NULL)
				->get();

			$order_types = DB::table('order_types')
				->where('deleted_at', NULL)
				->get();

			//$document_types = DocumentType::all();
			$document_types = DB::table('document_types')
				->join('document_type_office_selected', 'document_types.id', '=', 'document_type_office_selected.document_type_id')
				->where('document_type_office_selected.office_id', $office_id)
				//->where('document_types.is_multiple', 0)
				->get(['document_types.id', 'document_types.name']);

			if ($user->role_id == 2) {
				// admin
				return view('almacen.solicitude.state.received', ["orders" => $orders->paginate(10), "searchText" => $text, 'offices' => $offices, 'document_statuses' => $document_statuses, 'document_status' => $document_status, 'admin' => true, 'start_date' => $start_date ? $start_date->format('d/m/Y') : "", 'end_date' => $end_date ? $end_date->format('d/m/Y') : "", 'tupa' => $tupa, 'document_types' => $document_types, 'order_types' => $order_types, 'current_office_id' => $office_id]);

			}

			return view('almacen.solicitude.state.received', ["orders" => $orders->paginate(10), "searchText" => $text, 'offices' => $offices, 'document_statuses' => $document_statuses, 'document_status' => $document_status, 'admin' => false, 'start_date' => $start_date ? $start_date->format('d/m/Y') : "", 'end_date' => $end_date ? $end_date->format('d/m/Y') : "", 'tupa' => $tupa, 'document_types' => $document_types, 'order_types' => $order_types, 'current_office_id' => $office_id]);
		}

	}


	public function get_view_derivated(Request $request)
	{

		if ($request) {

			$start_date = "";

			if ($request->has('inicio')) {
				$start_date = Carbon::createFromFormat('d/m/Y', $request->inicio);
			}

			$end_date = "";

			if ($request->has('fin')) {
				$end_date = Carbon::createFromFormat('d/m/Y', $request->fin);
			}

			$user = User::with('entity')->find(Auth::user()->id);
			$entity = Entity::find($user->entity_id);

			$office_id = $user->entity->office_id;

			$offices = Office::where('id', '!=', $office_id)
				->get();

			$text = trim($request->get('searchText'));
			$document_status = 2;

			$orders = DB::table('orders')
				->orderBy('orders.id', 'desc')
				->join('document_types', 'orders.document_type_id', '=', 'document_types.id')
				->join('entities', 'orders.entity_id', '=', 'entities.id')
				->join('offices', 'orders.office_id', '=', 'offices.id')
				->join('document_statuses', 'orders.status', '=', 'document_statuses.id')
				->join('details_order', 'orders.id', '=', 'details_order.order_id')
				->leftJoin('orders as parent_order', 'orders.parent_order_id', '=', 'parent_order.id')
				->leftJoin('offices as office_parent', 'parent_order.office_id', '=', 'office_parent.id')
				->where('orders.deleted_at', null);


				if ($request->has('inicio')) {
					$orders = $orders->whereDate('orders.created_at', '>=', $start_date->format('Y-m-d'))
						->whereDate('orders.created_at', '<=', $end_date->format('Y-m-d'));
				}

				if ($text) {
					$orders = $orders->where(function ($query) use($text) {
               			$query->where('orders.code', $text)
                    		->orWhere('entities.identity_document', $text);
           			});
				}

				if ($document_status) {
					$orders = $orders->where('orders.status', $document_status)
						->where('details_order.status', $document_status);
				}


			$orders = $orders->select(['orders.id', 'orders.code', 'document_types.name as document_type_name', 'orders.number as number', 'orders.subject as subject', 'entities.name as name', 'entities.paternal_surname as paternal_surname', 'entities.maternal_surname as maternal_surname', 'entities.identity_document as identity_document', 'entities.type_document as type_document', 'entities.cellphone as cellphone', 'entities.email as email', 'entities.address as address' , 'offices.name as office_name', 'orders.status as status', 'document_statuses.name as status_name', 'orders.attached_file', 'orders.created_at', 'orders.parent_order_id', 'office_parent.name as office_parent_name', 'details_order.created_at as derivated_at']);
				// ->paginate(20);

			$document_statuses = DocumentState::all();

			$admin = false;

			$orders = $orders->where('orders.office_id', $office_id);

			if ($user->role_id == 2) {
				// admin
				return view('almacen.solicitude.state.derivated', ["orders" => $orders->paginate(20), "searchText" => $text, 'offices' => $offices, 'document_statuses' => $document_statuses, 'document_status' => $document_status, 'admin' => true, 'start_date' => $start_date ? $start_date->format('d/m/Y') : "", 'end_date' => $end_date ? $end_date->format('d/m/Y') : ""]);

			}

			return view('almacen.solicitude.state.derivated', ["orders" => $orders->paginate(20), "searchText" => $text, 'offices' => $offices, 'document_statuses' => $document_statuses, 'document_status' => $document_status, 'admin' => false, 'start_date' => $start_date ? $start_date->format('d/m/Y') : "", 'end_date' => $end_date ? $end_date->format('d/m/Y') : ""]);
		}

	}


	public function get_view_finished(Request $request)
	{

		if ($request) {

			$start_date = "";

			if ($request->has('inicio')) {
				$start_date = Carbon::createFromFormat('d/m/Y', $request->inicio);
			}

			$end_date = "";

			if ($request->has('fin')) {
				$end_date = Carbon::createFromFormat('d/m/Y', $request->fin);
			}

			$user = User::with('entity')->find(Auth::user()->id);
			$entity = Entity::find($user->entity_id);

			$office_id = $user->entity->office_id;

			$offices = Office::where('id', '!=', $office_id)
				->get();

			$text = trim($request->get('searchText'));
			$document_status = 4;

			$orders = DB::table('orders')
				->orderBy('orders.id', 'desc')
				->join('document_types', 'orders.document_type_id', '=', 'document_types.id')
				->join('entities', 'orders.entity_id', '=', 'entities.id')
				->join('offices', 'orders.office_id', '=', 'offices.id')
				->join('document_statuses', 'orders.status', '=', 'document_statuses.id')
				->join('details_order', 'orders.id', '=', 'details_order.order_id')
				->leftJoin('orders as parent_order', 'orders.parent_order_id', '=', 'parent_order.id')
				->leftJoin('offices as office_parent', 'parent_order.office_id', '=', 'office_parent.id')
				->where('orders.deleted_at', null);

				if ($request->has('inicio')) {
					$orders = $orders->whereDate('orders.created_at', '>=', $start_date->format('Y-m-d'))
						->whereDate('orders.created_at', '<=', $end_date->format('Y-m-d'));
				}

				if ($text) {
					$orders = $orders->where(function ($query) use($text) {
               			$query->where('orders.code', "LIKE", "%$text%");
                    		//->orWhere('entities.identity_document', "LIKE", "%$text%");
           			});
				}

				if ($document_status) {
					$orders = $orders->where('orders.status', $document_status)
						->where('details_order.status', $document_status);
				}


			$orders = $orders->select(['orders.id', 'orders.code', 'document_types.name as document_type_name', 'orders.number as number', 'orders.subject as subject', 'entities.name as name', 'entities.paternal_surname as paternal_surname', 'entities.maternal_surname as maternal_surname', 'entities.identity_document as identity_document', 'entities.type_document as type_document', 'entities.cellphone as cellphone', 'entities.email as email', 'entities.address as address' , 'offices.name as office_name', 'orders.status as status', 'document_statuses.name as status_name', 'orders.attached_file', 'orders.created_at', 'orders.parent_order_id', 'office_parent.name as office_parent_name', 'details_order.created_at as finished_at', 'orders.internal_code']);
				// ->paginate(20);

			$document_statuses = DocumentState::all();

			$admin = false;

			$orders = $orders->where('orders.office_id', $office_id);

			if ($user->role_id == 2) {
				// admin
				return view('almacen.solicitude.state.finished', ["orders" => $orders->paginate(10), "searchText" => $text, 'offices' => $offices, 'document_statuses' => $document_statuses, 'document_status' => $document_status, 'admin' => true, 'start_date' => $start_date ? $start_date->format('d/m/Y') : "", 'end_date' => $end_date ? $end_date->format('d/m/Y') : ""]);

			}

			return view('almacen.solicitude.state.finished', ["orders" => $orders->paginate(10), "searchText" => $text, 'offices' => $offices, 'document_statuses' => $document_statuses, 'document_status' => $document_status, 'admin' => false, 'start_date' => $start_date ? $start_date->format('d/m/Y') : "", 'end_date' => $end_date ? $end_date->format('d/m/Y') : ""]);
		}

	}

	public function get_view_cc(Request $request)
	{
		if ($request) {

			$start_date = "";

			if ($request->has('inicio')) {
				$start_date = Carbon::createFromFormat('d/m/Y', $request->inicio);
			}

			$end_date = "";

			if ($request->has('fin')) {
				$end_date = Carbon::createFromFormat('d/m/Y', $request->fin);
			}

			$user = User::with('entity')->find(Auth::user()->id);
			$entity = Entity::find($user->entity_id);

			$office_id = $user->entity->office_id;

			$offices = Office::where('id', '!=', $office_id)
				->get();

			$text = trim($request->get('searchText'));
			$document_status = 5;

			$orders = DB::table('orders')
				->orderBy('orders.id', 'desc')
				->join('details_order', 'orders.id', '=', 'details_order.order_id')
				->join('document_types', 'orders.document_type_id', '=', 'document_types.id')
				->join('entities', 'orders.entity_id', '=', 'entities.id')
				->join('offices', 'details_order.office_id_origen', '=', 'offices.id')
				->join('document_statuses', 'orders.status', '=', 'document_statuses.id')
				->where('orders.deleted_at', null);

				if ($request->has('inicio')) {
					$orders = $orders->whereDate('orders.created_at', '>=', $start_date->format('Y-m-d'))
						->whereDate('orders.created_at', '<=', $end_date->format('Y-m-d'));
				}


				if ($text) {
					$orders = $orders->where(function ($query) use($text) {
               			$query->where('orders.code', "LIKE", "%$text%");
                    		//->orWhere('entities.identity_document', "LIKE", "%$text%");
           			});
				}

				if ($document_status) {
					$orders = $orders->where('details_order.status', $document_status);
				}


			$orders = $orders->select(['orders.id', 'orders.code', 'document_types.name as document_type_name', 'orders.number as number', 'orders.subject as subject', 'entities.name as name', 'entities.paternal_surname as paternal_surname', 'entities.maternal_surname as maternal_surname', 'entities.identity_document as identity_document', 'entities.type_document as type_document', 'entities.cellphone as cellphone', 'entities.email as email', 'entities.address as address' , 'offices.name as office_name', 'orders.status as status', 'document_statuses.name as status_name', 'orders.attached_file', 'orders.created_at', 'orders.parent_order_id', 'orders.internal_code']);
				// ->paginate(20);

			$document_statuses = DocumentState::all();

			$admin = false;

			$orders = $orders->where('orders.office_id', $office_id);

			if ($user->role_id == 2) {
				// admin
				return view('almacen.solicitude.state.cc', ["orders" => $orders->paginate(10), "searchText" => $text, 'offices' => $offices, 'document_statuses' => $document_statuses, 'document_status' => $document_status, 'admin' => true, 'start_date' => $start_date ? $start_date->format('d/m/Y') : "", 'end_date' => $end_date ? $end_date->format('d/m/Y') : ""]);

			}

			return view('almacen.solicitude.state.cc', ["orders" => $orders->paginate(10), "searchText" => $text, 'offices' => $offices, 'document_statuses' => $document_statuses, 'document_status' => $document_status, 'admin' => false, 'start_date' => $start_date ? $start_date->format('d/m/Y') : "", 'end_date' => $end_date ? $end_date->format('d/m/Y') : ""]);
		}


	}

	public function get_view_registrated(Request $request)
	{
		if ($request) {

			$start_date = "";

			if ($request->has('inicio')) {
				$start_date = Carbon::createFromFormat('d/m/Y', $request->inicio);
			}

			$end_date = "";

			if ($request->has('fin')) {
				$end_date = Carbon::createFromFormat('d/m/Y', $request->fin);
			}

			$user = User::with('entity')->find(Auth::user()->id);
			$entity = Entity::find($user->entity_id);

			$office_id = $user->entity->office_id;

			$offices = Office::where('id', '!=', $office_id)
				->get();

			$text = trim($request->get('searchText'));
			$document_status = 1;

			$orders = DB::table('orders')
				->orderBy('details_order.id', 'desc')
				->join('details_order', 'orders.id', '=', 'details_order.order_id')
				->join('document_types', 'orders.document_type_id', '=', 'document_types.id')
				->join('entities', 'orders.entity_id', '=', 'entities.id')
				->leftJoin('offices', 'orders.office_id_origen', '=', 'offices.id')
				->join('document_statuses', 'orders.status', '=', 'document_statuses.id')
				->leftJoin('orders as parent_order', 'orders.parent_order_id', '=', 'parent_order.id')
				->leftJoin('offices as office_parent', 'parent_order.office_id', '=', 'office_parent.id')
				->where('orders.deleted_at', null)
				->where('details_order.last', true);

				if ($request->has('inicio')) {
					$orders = $orders->whereDate('orders.created_at', '>=', $start_date->format('Y-m-d'))
						->whereDate('orders.created_at', '<=', $end_date->format('Y-m-d'));
				}

				// ->whereDate('orders.created_at', '>=', $start_date->format('Y-m-d'))
				// ->whereDate('orders.created_at', '<=', $end_date->format('Y-m-d'));

				if ($text) {
					$orders = $orders->where(function ($query) use($text) {
               			$query->where('orders.code', "LIKE", "%$text%");
                    		//->orWhere('entities.identity_document', "LIKE", "%$text%");
           			});
				}

				if ($document_status) {
					$orders = $orders->where('orders.status', $document_status)
						->where('details_order.status', $document_status);
				}


			$orders = $orders->select(['orders.id', 'orders.code', 'document_types.name as document_type_name', 'orders.number as number', 'orders.subject as subject', 'entities.name as name', 'entities.paternal_surname as paternal_surname', 'entities.maternal_surname as maternal_surname', 'entities.identity_document as identity_document', 'entities.type_document as type_document', 'entities.cellphone as cellphone', 'entities.email as email', 'entities.address as address' , 'offices.name as office_name', 'orders.status as status', 'document_statuses.name as status_name', 'orders.attached_file', 'orders.created_at', 'orders.parent_order_id', 'office_parent.name as office_parent_name', 'orders.term_end', 'orders.term', 'orders.internal_code']);
				// ->paginate(20);

			$document_statuses = DocumentState::all();

			$admin = false;

			$orders = $orders->where('orders.office_id', $office_id)
				->where('details_order.office_id', $office_id);

			$tupa = DB::table('tupa')
				->where('deleted_at', NULL)
				->get();

			$order_types = DB::table('order_types')
				->where('deleted_at', NULL)
				->get();

			$document_types = DocumentType::all();

			if ($user->role_id == 2) {
				// admin
				return view('almacen.solicitude.state.registrated', ["orders" => $orders->paginate(10), "searchText" => $text, 'offices' => $offices, 'document_statuses' => $document_statuses, 'document_status' => $document_status, 'admin' => true, 'start_date' => $start_date ? $start_date->format('d/m/Y') : "", 'end_date' => $end_date ? $end_date->format('d/m/Y') : "", 'current_office_id' => $office_id, 'tupa' => $tupa, 'document_types' => $document_types, 'order_types' => $order_types]);

			}

			return view('almacen.solicitude.state.registrated', ["orders" => $orders->paginate(10), "searchText" => $text, 'offices' => $offices, 'document_statuses' => $document_statuses, 'document_status' => $document_status, 'admin' => false, 'start_date' => $start_date ? $start_date->format('d/m/Y') : "", 'end_date' => $end_date ? $end_date->format('d/m/Y') : "", 'current_office_id' => $office_id, 'tupa' => $tupa, 'document_types' => $document_types, 'order_types' => $order_types]);
		}
	}

	public function get_view_sent(Request $request)
	{	
		if ($request) {

			$start_date = "";

			if ($request->has('inicio')) {
				$start_date = Carbon::createFromFormat('d/m/Y', $request->inicio);
			}

			$end_date = "";

			if ($request->has('fin')) {
				$end_date = Carbon::createFromFormat('d/m/Y', $request->fin);
			}

			$user = User::with('entity')->find(Auth::user()->id);
			$entity = Entity::find($user->entity_id);

			$office_id = $user->entity->office_id;

			$offices = Office::where('id', '!=', $office_id)
				->get();

			$text = trim($request->get('searchText'));
			$document_status = 1;

			$orders = DB::table('orders')
				->orderBy('orders.id', 'desc')
				->join('details_order', 'orders.id', '=', 'details_order.order_id')
				->join('document_types', 'orders.document_type_id', '=', 'document_types.id')
				->join('entities', 'orders.entity_id', '=', 'entities.id')
				->leftJoin('offices', 'details_order.office_id', '=', 'offices.id')
				->join('document_statuses', 'orders.status', '=', 'document_statuses.id')
				->leftJoin('orders as parent_order', 'orders.parent_order_id', '=', 'parent_order.id')
				->leftJoin('offices as office_parent', 'parent_order.office_id', '=', 'office_parent.id')
				->where('orders.deleted_at', null)
				->where('details_order.last', 0);
				//->where('orders.office_id_origen', 0);

				if ($request->has('inicio')) {
					$orders = $orders->whereDate('orders.created_at', '>=', $start_date->format('Y-m-d'))
						->whereDate('orders.created_at', '<=', $end_date->format('Y-m-d'));
				}

				if ($text) {
					$orders = $orders->where(function ($query) use($text) {
               			$query->where('orders.code', "LIKE", "%$text%");
                    		//->orWhere('entities.identity_document', "LIKE", "%$text%");
           			});
				}

				if ($document_status) {
					$orders = $orders->whereIn('details_order.status', [2]);
				}

			$orders = $orders->select(['orders.id', 'orders.code', 'document_types.name as document_type_name', 'orders.number as number', 'orders.subject as subject', 'entities.name as name', 'entities.paternal_surname as paternal_surname', 'entities.maternal_surname as maternal_surname', 'entities.identity_document as identity_document', 'entities.type_document as type_document', 'entities.cellphone as cellphone', 'entities.email as email', 'entities.address as address' , 'offices.name as office_name', 'orders.status as status', 'document_statuses.name as status_name', 'orders.attached_file', 'orders.created_at', 'orders.parent_order_id', 'office_parent.name as office_parent_name', 'orders.internal_code']);
				// ->paginate(20);

			$document_statuses = DocumentState::all();

			$admin = false;

			$orders = $orders->where('details_order.office_id_origen', $office_id)->groupBy('orders.id');
			//return $orders->get


			if ($user->role_id == 2) {
				// admin
				return view('almacen.solicitude.state.sent', ["orders" => $orders->paginate(10), "searchText" => $text, 'offices' => $offices, 'document_statuses' => $document_statuses, 'document_status' => $document_status, 'admin' => true, 'start_date' => $start_date ? $start_date->format('d/m/Y') : "", 'end_date' => $end_date ? $end_date->format('d/m/Y') : ""]);

			}

			return view('almacen.solicitude.state.sent', ["orders" => $orders->paginate(10), "searchText" => $text, 'offices' => $offices, 'document_statuses' => $document_statuses, 'document_status' => $document_status, 'admin' => false, 'start_date' => $start_date ? $start_date->format('d/m/Y') : "", 'end_date' => $end_date ? $end_date->format('d/m/Y') : ""]);
		}

	}


	public function get_view_report(Request $request)
	{

		if ($request) {

			//$start_date = Carbon::now();
			$start_date = "";

			if ($request->has('inicio')) {
				$start_date = Carbon::createFromFormat('d/m/Y', $request->inicio);
			}

			//$end_date = Carbon::now();
			$end_date = "";

			if ($request->has('fin')) {
				$end_date = Carbon::createFromFormat('d/m/Y', $request->fin);
			}

			$office_id = $request->oficina;
			$document_status = $request->status;

			$user = User::with('entity')->find(Auth::user()->id);
			$entity = Entity::with('office')->find($user->entity_id);

			if (!$office_id) {
				$office_id = $entity->office->id;
			}

			//$office_id = $user->entity->office_id;

			$offices = Office::all();
			$office = $entity->office;

			// $text = trim($request->get('searchText'));
			//$document_status = 1;

			$orders = DB::table('orders')
				->orderBy('details_order.id', 'desc')
				->join('details_order', 'orders.id', '=', 'details_order.order_id')
				->join('document_types', 'orders.document_type_id', '=', 'document_types.id')
				->join('entities', 'orders.entity_id', '=', 'entities.id')
				->leftJoin('offices', 'details_order.office_id_origen', '=', 'offices.id')
				->join('document_statuses', 'details_order.status', '=', 'document_statuses.id')
				// ->leftJoin('orders as parent_order', 'orders.parent_order_id', '=', 'parent_order.id')
				// ->leftJoin('offices as office_parent', 'parent_order.office_id', '=', 'office_parent.id')
				->leftJoin('offices as office_destination', 'details_order.office_id', '=', 'office_destination.id')
				->where('orders.deleted_at', null)
				->where('orders.parent_order_id', 0);


				if ($request->has('inicio')) {
					$orders = $orders->whereDate('orders.created_at', '>=', $start_date->format('Y-m-d'))
						->whereDate('orders.created_at', '<=', $end_date->format('Y-m-d'));
				}

				// if ($text) {
				// 	$orders = $orders->where(function ($query) use($text) {
    //            			$query->where('orders.code', $text)
    //                 		->orWhere('entities.identity_document', $text);
    //        			});
				// }

				// if (in_array($document_status, [1, 3, 4])) {
				// 	$orders = $orders->where('orders.status', $document_status)
				// 		->where('details_order.last', 1)
				// 		->where('orders.office_id', $office_id);

				// } else {
				// 	$orders = $orders->where('details_order.status', $document_status)
				// 		->where('details_order.office_id_origen', $office_id);

				// }

			$orders = $orders->select(['orders.id', 'orders.code', 'document_types.name as document_type_name', 'orders.number as number', 'orders.subject as subject', 'entities.name as name', 'entities.paternal_surname as paternal_surname', 'entities.maternal_surname as maternal_surname', 'entities.identity_document as identity_document', 'entities.type_document as type_document', 'entities.cellphone as cellphone', 'entities.email as email', 'entities.address as address' , 'offices.name as office_name', 'orders.status as status', 'document_statuses.name as status_name', 'orders.attached_file', 'orders.created_at', 'orders.parent_order_id', 'offices.name as office_parent_name', 'office_destination.name as office_destination_name', 'orders.office_id_origen', 'orders.multiple', 'orders.internal_code'])->groupBy('orders.id');
				// ->paginate(20);

			$document_statuses = DocumentState::whereIn('id', [1, 2, 3, 4])
				->get();

			$admin = false;

			if ($user->role_id == 2) {
				$admin = true;
			}

			$orders = $orders->where('details_order.office_id_origen', $office_id);

			//$orders = $orders->where('orders.office_id', $office_id)				->where('details_order.office_id', $office_id);
			return view('almacen.solicitude.report', ["orders" => $orders->paginate(10), 'offices' => $offices, 'document_statuses' => $document_statuses, 'document_status' => $document_status, 'admin' => $admin, 'start_date' => $start_date ? $start_date->format('d/m/Y') : "", 'end_date' => $end_date ? $end_date->format('d/m/Y') : "", "office" => $office, 'office_id' => $office_id, 'current_office_id' => $entity->office_id]);
		}

	}


	public function get_view_report_code(Request $request)
	{

		if ($request) {

			$office_id = $request->oficina;
			$year = $request->year;
			$text = $request->text;
			//$document_status = $request->status;

			$user = User::with('entity')->find(Auth::user()->id);
			$entity = Entity::with('office')->find($user->entity_id);
			//$office_id = $user->entity->office_id;
			if (!$office_id) {
				$office_id = $entity->office->id;
			}


			$offices = Office::all();
			$office = $entity->office;

			// $text = trim($request->get('searchText'));
			//$document_status = 1;

			$orders = DB::table('orders')
				->orderBy('details_order.id', 'desc')
				->join('details_order', 'orders.id', '=', 'details_order.order_id')
				->join('document_types', 'orders.document_type_id', '=', 'document_types.id')
				->join('entities', 'orders.entity_id', '=', 'entities.id')
				->leftJoin('offices', 'orders.office_id', '=', 'offices.id')
				->join('document_statuses', 'details_order.status', '=', 'document_statuses.id')
				// ->leftJoin('orders as parent_order', 'orders.parent_order_id', '=', 'parent_order.id')
				//->leftJoin('offices as office_destination', 'details_order.office_id', '=', 'office_destination.id')
				->where('orders.deleted_at', null)
				->where('orders.parent_order_id', 0);

				// if ($request->has('inicio')) {
				// 	$orders = $orders->whereDate('orders.created_at', '>=', $start_date->format('Y-m-d'))
				// 		->whereDate('orders.created_at', '<=', $end_date->format('Y-m-d'));
				// }

				if ($text) {
					$orders = $orders->where(function ($query) use($text) {
               			$query->where('orders.code', 'LIKE', "%$text%")
               				->orWhere('orders.internal_code', 'LIKE', "%$text%");
           			});
				}

				if ($year) {
					$orders = $orders->where('orders.year', $year);
				}


				// if (in_array($document_status, [1, 3, 4])) {
				// 	$orders = $orders->where('orders.status', $document_status)
				// 		->where('details_order.last', 1)
				// 		->where('orders.office_id', $office_id);

				// } else {
				// 	$orders = $orders->where('details_order.status', $document_status)
				// 		->where('details_order.office_id_origen', $office_id);
				// }

			$orders = $orders->select(['orders.id', 'orders.code', 'document_types.name as document_type_name', 'orders.number as number', 'orders.subject as subject', 'entities.name as name', 'entities.paternal_surname as paternal_surname', 'entities.maternal_surname as maternal_surname', 'entities.identity_document as identity_document', 'entities.type_document as type_document', 'entities.cellphone as cellphone', 'entities.email as email', 'entities.address as address' , 'offices.name as office_name', 'orders.status as status', 'document_statuses.name as status_name', 'orders.attached_file', 'orders.created_at', 'orders.parent_order_id', 'orders.office_id as current_office_id', 'orders.office_id_origen', 'orders.multiple', 'orders.internal_code'])
				->groupBy('orders.id');
				// ->paginate(20);

			//$document_statuses = DocumentState::whereIn('id', [1, 2, 3, 4])				->get();

			$admin = false;

			if ($user->role_id == 2) {
				$admin = true;
			}

			// if (!$admin) {
			// 	$orders = $orders->where('orders.multiple', false);
			// }

			$orders = $orders->where('details_order.office_id_origen', $office_id);

			return view('almacen.solicitude.report_code', ["orders" => $orders->paginate(10), 'offices' => $offices, 'admin' => $admin, "office" => $office, 'office_id' => $office_id, 'year' => $year, 'text' => $text, 'current_office_id' => $entity->office_id]);
		}

	}

	public function report_debt_datatable(Request $request)
	{

		$year_selected = $request->year;
		$result = DB::table('orders')
				->orderBy('orders.id', 'desc')
				->where('orders.year', $request->year)
				//->join('details_order', 'orders.id', '=', 'details_order.order_id')
				//->join('document_types', 'orders.document_type_id', '=', 'document_types.id')
				->join('entities', 'orders.entity_id', '=', 'entities.id')
				//->leftJoin('offices', 'details_order.office_id', '=', 'offices.id')
				//->join('document_statuses', 'orders.status', '=', 'document_statuses.id')
				//->leftJoin('orders as parent_order', 'orders.parent_order_id', '=', 'parent_order.id')
				//->leftJoin('offices as office_parent', 'parent_order.office_id', '=', 'office_parent.id')
				->where('orders.deleted_at', null)
				->where('orders.status', 1)
				->select(['orders.id', 'orders.code', 'orders.created_at','entities.identity_document', 'entities.name', 'entities.paternal_surname', 'entities.maternal_surname', 'orders.year', 'orders.order_type_id', 'orders.tupa_id', 'orders.subject']);
				//->where('orders.multiple', 0);
				// ->whereDate('orders.created_at', '>=', $start_date->format('Y-m-d'))
				// ->whereDate('orders.created_at', '<=', $end_date->format('Y-m-d'));
				// if ($request->has('inicio')) {
				// 	$orders = $orders->whereDate('orders.created_at', '>=', $start_date->format('Y-m-d'))
				// 		->whereDate('orders.created_at', '<=', $end_date->format('Y-m-d'));
				// }


				// if ($text) {
				// 	$orders = $orders->where(function ($query) use($text) {
	           	// 		$query->where('orders.code', 'LIKE', "%$text%")
	           	// 			->orWhere('orders.internal_code', 'LIKE', "%$text%");
	            //     		//->orWhere('entities.identity_document', 'LIKE', "%$text%");
	       		// 	});
				// }

		//return $result->get();

		// if ($request->status != '') {
		// 	$result = $result->where('orders.status', $request->status);
		// }

		// if ($request->start_date != '') {
		// 	$start_date = Carbon::createFromFormat('d/m/Y', $request->start_date);
		// 	$end_date = Carbon::createFromFormat('d/m/Y', $request->end_date);

		// 	$result = $result->whereDate('orders.created_at', '>=', $start_date)
		// 		->whereDate('orders.created_at', '<=', $end_date);
		// }
		if ($request->nivel) {
			$result = $result->where('orders.order_type_id', $request->nivel);
		}

		if ($request->grade) {
			$result = $result->where('orders.tupa_id', $request->grade);
		}

		if ($request->section) {
			$result = $result->where('orders.subject', $request->section);
		}
		return DataTables::of($result)
			->addColumn('debt', function ($model) use($year_selected) {

				$details = DB::table('details_order')
					->where('order_id', $model->id)
					->where('deleted_at', NULL)
					->where('status', 0)
					->sum('observations');

				return "S/.".number_format($details, 2, ',', '');

			})
			->addColumn('payed', function ($model) use($year_selected) {

				$details = DB::table('details_order')
					->where('order_id', $model->id)
					->where('deleted_at', NULL)
					->where('status', 1)
					->sum('observations');

				return "S/.".number_format($details, 2, ',', '');

			})
			->make(true);

	}

	public function report_debt_datatable_excel(Request $request)
	{

		$year = $request->year;

		$result = DB::table('orders')
				->where('orders.year', $year)
				->join('entities', 'orders.entity_id', '=', 'entities.id')
				->join('details_order', 'orders.id', '=', 'details_order.order_id')
				->where('details_order.deleted_at', NULL)
				//->where('details_order.status', 0)
				->where('orders.deleted_at', null)
				->where('orders.status', 1)
				->orderBy('orders.id', 'desc')
				->groupBy('orders.id')
				->select(['orders.id', 'orders.code', 'orders.created_at','entities.identity_document', 'entities.name', 'entities.paternal_surname', 'entities.maternal_surname', DB::raw('sum(details_order.observations) as total'), 'orders.order_type_id', 'orders.tupa_id', 'orders.subject']);

		if ($request->nivel) {
			$result = $result->where('orders.order_type_id', $request->nivel);
		}

		if ($request->grade) {
			$result = $result->where('orders.tupa_id', $request->grade);
		}
		
		if ($request->section) {
			$result = $result->where('orders.subject', $request->section);
		}
		
		$result = $result->get();

		$nivel_arr_values = array(1 => "PRIMARIA", 2 => "SECUNDARIA");
		$grade_arr_values = array(1 => "1ro", 2 => "2do", 3 => "3ro", 4 => "4to", 5 => "5to", 6 => "6to");

        Excel::create("Reporte general", function($excel) use($result, $year, $nivel_arr_values, $grade_arr_values) {
            $excel->sheet('data', function($sheet) use($result, $year, $nivel_arr_values, $grade_arr_values) {
                $sheet->setOrientation('landscape');
                $sheet->fromArray(['#', 'Código', 'Fecha de creación', 'DNI', 'Nombres', 'Apellido paterno', 'Apellido materno', 'Nivel', 'Grado', 'Pagado', 'Deuda', 'Año']);

                $sheet->cell('A1:L1', function($cell) {
                    $cell->setFontSize(13);
                    $cell->setFontWeight('bold');
                });

                foreach ($result as $rs => $order) {

					$raw_payed = DB::table('details_order')
						->where('order_id', $order->id)
						->where('deleted_at', NULL)
						->where('status', 1)
						->sum('observations');

					$payed = "S/.".number_format($raw_payed, 2, ',', '');
					$raw_debt = (float)$order->total - $raw_payed;

					$sheet->row($rs+2, [
						$rs+1,
						$order->code,
						$order->created_at,
						$order->identity_document,
						$order->name,
						$order->paternal_surname,
						$order->maternal_surname,
						$nivel_arr_values[$order->order_type_id],
						$grade_arr_values[$order->tupa_id]." ".$order->subject,
						$payed,
						"S/.".number_format($raw_debt, 2, ',', ''),
						$year
					]);
                }
            });
        })->export('xls');
			

	}



	public function get_view_report_document(Request $request)
	{

		if ($request) {

			$office_id = $request->oficina;
			$document_type_id = $request->documento;

			$year = $request->year;
			$text = $request->text;
			//$document_status = $request->status;

			$user = User::with('entity')->find(Auth::user()->id);
			$entity = Entity::with('office')->find($user->entity_id);
			$document_types = DocumentType::all();
			//$office_id = $user->entity->office_id;
			if (!$office_id) {
				$office_id = $entity->office->id;
			}


			$offices = Office::all();
			$office = $entity->office;

			// $text = trim($request->get('searchText'));
			//$document_status = 1;

			$orders = DB::table('orders')
				->orderBy('details_order.id', 'desc')
				->join('details_order', 'orders.id', '=', 'details_order.order_id')
				->join('document_types', 'orders.document_type_id', '=', 'document_types.id')
				->join('entities', 'orders.entity_id', '=', 'entities.id')
				->leftJoin('offices', 'details_order.office_id_origen', '=', 'offices.id')
				->join('document_statuses', 'details_order.status', '=', 'document_statuses.id')
				// ->leftJoin('orders as parent_order', 'orders.parent_order_id', '=', 'parent_order.id')
				->leftJoin('offices as office_destination', 'details_order.office_id', '=', 'office_destination.id')
				->where('orders.deleted_at', null)
				->where('orders.parent_order_id', 0);

				// if ($request->has('inicio')) {
				// 	$orders = $orders->whereDate('orders.created_at', '>=', $start_date->format('Y-m-d'))
				// 		->whereDate('orders.created_at', '<=', $end_date->format('Y-m-d'));
				// }

				if ($text) {
					$orders = $orders->where(function ($query) use($text) {
               			$query->where('orders.code', 'LIKE', "%$text%");
           			});
				}

				if ($document_type_id) {
					$orders = $orders->where('orders.document_type_id', $document_type_id);
				}

				if ($year) {
					$orders = $orders->where('orders.year', $year);
				}

			$orders = $orders->select(['orders.id', 'orders.code', 'document_types.name as document_type_name', 'orders.number as number', 'orders.subject as subject', 'entities.name as name', 'entities.paternal_surname as paternal_surname', 'entities.maternal_surname as maternal_surname', 'entities.identity_document as identity_document', 'entities.type_document as type_document', 'entities.cellphone as cellphone', 'entities.email as email', 'entities.address as address' , 'offices.name as office_name', 'orders.status as status', 'document_statuses.name as status_name', 'orders.attached_file', 'orders.created_at', 'orders.parent_order_id', 'offices.name as office_parent_name', 'office_destination.name as office_destination_name', 'orders.office_id as current_office_id', 'orders.office_id_origen', 'orders.multiple', 'orders.internal_code'])
				->groupBy('orders.id');
				// ->paginate(20);

			$admin = false;

			if ($user->role_id == 2) {
				$admin = true;
			}

			$orders = $orders->where('details_order.office_id_origen', $office_id);

			return view('almacen.solicitude.report_document', ["orders" => $orders->paginate(10), 'offices' => $offices, 'admin' => $admin, "office" => $office, 'office_id' => $office_id, 'year' => $year, 'text' => $text, 'current_office_id' => $entity->office_id, 'document_types' => $document_types, 'document_type_id' => $document_type_id]);
		}

	}

	public function update_status_extorno($detail_order_id)
	{

		$detail = DetailOrder::find($detail_order_id);

		$order = Order::find($detail->order_id);
		if ($order->status == 7) {

			$children = DB::table('order_multiple_document')
				->where('parent_order_id', $order->id)
				->where('deleted_at', NULL)
				->get();

			$children_cud = DB::table('order_multiple_document')
				->where('order_multiple_document.parent_order_id', $order->id)
				->join('orders', 'order_multiple_document.order_id', '=', 'orders.id')
				->where('order_multiple_document.deleted_at', NULL)
				->pluck('orders.code');

			$cud_string = implode(',', $children_cud);

			if ($children) {
				return response()->json(['title' => 'Advertencia', 'message' => "No se ha podido extornar la solicitud. Primero elimine las solicitudes hijo con cud: {$cud_string}."], 400);
			}
		}


		DB::table('details_order')
			->where('id', $detail_order_id)
			->update(['last' => true]);

		DB::table('orders')
			->where('id', $detail->order_id)
			->update(['status' => $detail->status, 'office_id' => $detail->office_id]);

		DB::table('details_order')
			->where('id', '>', $detail_order_id)
			->where('order_id', $detail->order_id)
			->update(['deleted_at' => Carbon::now()->format('Y-m-d H:i:s')]);

		return response()->json(['title' => 'Operación Exitosa', 'message' => 'Se ha extornado la solicitud.'], 200);

	}

	public function edit_solicitude_view($id){

		$order = Order::find($id);

		$document_types = DocumentType::all();

		//$tupa = Tupa::all();
		$tupa = DB::table('tupa')
			->where('deleted_at', NULL)
			->get();

		$order_types = DB::table('order_types')
			->where('deleted_at', NULL)
			->get();

		return view('almacen.solicitude.edit_solicitude', compact('order', 'document_types', 'tupa', 'order_types'));
	}

	public function report_by_code(Request $request)
	{
		$code = $request->codigo;
		$office_id = $request->oficina;

		$user = User::with('entity')->find(Auth::user()->id);
		$entity = Entity::with('office')->find($user->entity_id);
		$current_office_id = $entity->office_id;

		$admin = false;

		if ($user->role_id == 2) {
			$admin = true;
		}

		$orders = DB::table('orders')
			->orderBy('details_order.id', 'desc')
			->join('details_order', 'orders.id', '=', 'details_order.order_id')
			->join('document_types', 'orders.document_type_id', '=', 'document_types.id')
			->join('entities', 'orders.entity_id', '=', 'entities.id')
			->leftJoin('offices', 'details_order.office_id_origen', '=', 'offices.id')
			->join('document_statuses', 'details_order.status', '=', 'document_statuses.id')
			->leftJoin('offices as office_destination', 'details_order.office_id', '=', 'office_destination.id')
			->where('orders.deleted_at', null)
			->where('orders.parent_order_id', 0);



		if ($code) {
			$orders = $orders->where(function ($query) use($code) {
	   			$query->where('orders.code', 'LIKE', "%$code%");
				});
		}

		$orders = $orders->where('details_order.office_id_origen', $office_id);

		$orders = $orders->select(['orders.code', 'document_types.name as document_type_name', 'orders.subject as subject', 'entities.name as name', 'entities.paternal_surname as paternal_surname', 'entities.maternal_surname as maternal_surname', 'entities.identity_document as identity_document', 'entities.type_document as type_document', 'entities.cellphone as cellphone', 'entities.email as email', 'entities.address as address' , 'offices.name as office_name', 'orders.status as status', 'document_statuses.name as status_name', 'orders.attached_file', 'orders.created_at', 'offices.name as office_parent_name', 'office_destination.name as office_destination_name', 'orders.office_id as current_office_id', 'orders.office_id_origen', 'orders.multiple', 'orders.internal_code'])
				->groupBy('orders.id')
				->get();
        
        $curent_date = Carbon::now()->format('Y-m-d');
        $excelName = 'REPORTE por código '.$curent_date;

        Excel::create($excelName, function($excel) use($orders, $admin, $current_office_id) {
            $excel->sheet('data', function($sheet) use($orders, $admin, $current_office_id) {
                $sheet->setOrientation('landscape');
                $sheet->fromArray(['#', 'Código', 'Fecha de Ingreso', 'Tipo de documento', 'Número', 'Asunto', 'De', 'Estado']);
                $row = 0;
                foreach ($orders as $key => $order) {
                	if ($order->multiple && !$admin) {
                		if ($order->office_id_origen != $current_office_id) {
							$sheet->row($row+2, [$row+1, $order->code,  Carbon::parse($order->created_at)->format('d/m/Y H:i'), $order->document_type_name, $order->internal_code ? $order->internal_code : "S/N", $order->subject, $order->name." ".$order->paternal_surname." ".$order->maternal_surname, $order->status_name]);
							$row++;
                		}
                	} else {
						$sheet->row($row+2, [$row+1, $order->code,  Carbon::parse($order->created_at)->format('d/m/Y H:i'), $order->document_type_name, $order->internal_code ? $order->internal_code : "S/N", $order->subject, $order->name." ".$order->paternal_surname." ".$order->maternal_surname, $order->status_name]);
						$row++;

                	}


                    
                }
            });
        })->export('xls');
	}

	public function report_by_document(Request $request)
	{
		$document_type_id = $request->documento;
		$office_id = $request->oficina;

		$user = User::with('entity')->find(Auth::user()->id);
		$entity = Entity::with('office')->find($user->entity_id);
		$current_office_id = $entity->office_id;

		$admin = false;

		if ($user->role_id == 2) {
			$admin = true;
		}

		$orders = DB::table('orders')
			->orderBy('details_order.id', 'desc')
			->join('details_order', 'orders.id', '=', 'details_order.order_id')
			->join('document_types', 'orders.document_type_id', '=', 'document_types.id')
			->join('entities', 'orders.entity_id', '=', 'entities.id')
			->leftJoin('offices', 'details_order.office_id_origen', '=', 'offices.id')
			->join('document_statuses', 'details_order.status', '=', 'document_statuses.id')
			->leftJoin('offices as office_destination', 'details_order.office_id', '=', 'office_destination.id')
			->where('orders.deleted_at', null)
			->where('orders.parent_order_id', 0);

		if ($document_type_id) {
			$orders = $orders->where('orders.document_type_id', $document_type_id);
		}

		$orders = $orders->where('details_order.office_id_origen', $office_id);

		$orders = $orders->select(['orders.code', 'document_types.name as document_type_name', 'orders.subject as subject', 'entities.name as name', 'entities.paternal_surname as paternal_surname', 'entities.maternal_surname as maternal_surname', 'entities.identity_document as identity_document', 'entities.type_document as type_document', 'entities.cellphone as cellphone', 'entities.email as email', 'entities.address as address' , 'offices.name as office_name', 'orders.status as status', 'document_statuses.name as status_name', 'orders.attached_file', 'orders.created_at', 'offices.name as office_parent_name', 'office_destination.name as office_destination_name', 'orders.office_id as current_office_id', 'orders.office_id_origen', 'orders.multiple', 'orders.internal_code'])
				->groupBy('orders.id')
				->get();
        
        $curent_date = Carbon::now()->format('Y-m-d');
        $excelName = 'REPORTE por documento '.$curent_date;

        Excel::create($excelName, function($excel) use($orders, $admin, $current_office_id) {
            $excel->sheet('data', function($sheet) use($orders, $admin, $current_office_id) {
                $sheet->setOrientation('landscape');
                $sheet->fromArray(['#', 'Código', 'Fecha de Ingreso', 'Tipo de documento', 'Número', 'Asunto', 'De', 'Estado']);
                $row = 0;
                foreach ($orders as $key => $order) {
                	if ($order->multiple && !$admin) {
                		if ($order->office_id_origen != $current_office_id) {
							$sheet->row($row+2, [$row+1, $order->code,  Carbon::parse($order->created_at)->format('d/m/Y H:i'), $order->document_type_name, $order->internal_code ? $order->internal_code : "S/N", $order->subject, $order->name." ".$order->paternal_surname." ".$order->maternal_surname, $order->status_name]);
							$row++;
                		}
                	} else {
						$sheet->row($row+2, [$row+1, $order->code,  Carbon::parse($order->created_at)->format('d/m/Y H:i'), $order->document_type_name, $order->internal_code ? $order->internal_code : "S/N", $order->subject, $order->name." ".$order->paternal_surname." ".$order->maternal_surname, $order->status_name]);
						$row++;

                	}


                    
                }
            });
        })->export('xls');
	}

	public function simple_report(Request $request)
	{	
		$office_id = $request->oficina;

		$start_date = "";

		if ($request->has('inicio')) {
			$start_date = Carbon::createFromFormat('d/m/Y', $request->inicio);
		}

		//$end_date = Carbon::now();
		$end_date = "";

		if ($request->has('fin')) {
			$end_date = Carbon::createFromFormat('d/m/Y', $request->fin);
		}

		$user = User::with('entity')->find(Auth::user()->id);
		$entity = Entity::with('office')->find($user->entity_id);
		$current_office_id = $entity->office_id;

		$admin = false;

		if ($user->role_id == 2) {
			$admin = true;
		}

		$orders = DB::table('orders')
			->orderBy('details_order.id', 'desc')
			->join('details_order', 'orders.id', '=', 'details_order.order_id')
			->join('document_types', 'orders.document_type_id', '=', 'document_types.id')
			->join('entities', 'orders.entity_id', '=', 'entities.id')
			->leftJoin('offices', 'details_order.office_id_origen', '=', 'offices.id')
			->join('document_statuses', 'details_order.status', '=', 'document_statuses.id')
			->leftJoin('offices as office_destination', 'details_order.office_id', '=', 'office_destination.id')
			->where('orders.deleted_at', null)
			->where('orders.parent_order_id', 0);

		if ($request->has('inicio')) {
			$orders = $orders->whereDate('orders.created_at', '>=', $start_date->format('Y-m-d'))
				->whereDate('orders.created_at', '<=', $end_date->format('Y-m-d'));
		}

		$orders = $orders->where('details_order.office_id_origen', $office_id);

		$orders = $orders->select(['orders.code', 'document_types.name as document_type_name', 'orders.subject as subject', 'entities.name as name', 'entities.paternal_surname as paternal_surname', 'entities.maternal_surname as maternal_surname', 'entities.identity_document as identity_document', 'entities.type_document as type_document', 'entities.cellphone as cellphone', 'entities.email as email', 'entities.address as address' , 'offices.name as office_name', 'orders.status as status', 'document_statuses.name as status_name', 'orders.attached_file', 'orders.created_at', 'offices.name as office_parent_name', 'office_destination.name as office_destination_name', 'orders.office_id as current_office_id', 'orders.office_id_origen', 'orders.multiple', 'orders.internal_code'])
				->groupBy('orders.id')
				->get();
        
        $curent_date = Carbon::now()->format('Y-m-d');
        $excelName = 'REPORTE '.$curent_date;

        Excel::create($excelName, function($excel) use($orders, $admin, $current_office_id) {
            $excel->sheet('data', function($sheet) use($orders, $admin, $current_office_id) {
                $sheet->setOrientation('landscape');
                $sheet->fromArray(['#', 'Código', 'Fecha de Ingreso', 'Tipo de documento', 'Número', 'Asunto', 'De', 'Estado']);
                $row = 0;
                foreach ($orders as $key => $order) {
                	if ($order->multiple && !$admin) {
                		if ($order->office_id_origen != $current_office_id) {
							$sheet->row($row+2, [$row+1, $order->code,  Carbon::parse($order->created_at)->format('d/m/Y H:i'), $order->document_type_name, $order->internal_code ? $order->internal_code : "S/N", $order->subject, $order->name." ".$order->paternal_surname." ".$order->maternal_surname, $order->status_name]);
							$row++;
                		}
                	} else {
						$sheet->row($row+2, [$row+1, $order->code,  Carbon::parse($order->created_at)->format('d/m/Y H:i'), $order->document_type_name, $order->internal_code ? $order->internal_code : "S/N", $order->subject, $order->name." ".$order->paternal_surname." ".$order->maternal_surname, $order->status_name]);
						$row++;

                	}


                    
                }
            });
        })->export('xls');

	}

}
