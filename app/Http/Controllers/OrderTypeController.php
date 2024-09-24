<?php

namespace sisVentas\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use sisVentas\Http\Requests\OrderTypeRequest;
use sisVentas\Http\Requests\TupaFormRequest;
use sisVentas\Office;
use sisVentas\OrderType;

class OrderTypeController extends Controller {
	public function __construct() {
		$this->middleware('auth');
	}
	
	public function index(Request $request) {
		if ($request) {
			$query = trim($request->get('searchText'));
			$order_types = DB::table('order_types')->where('name', 'LIKE', '%' . $query . '%')
				->orderBy('id', 'desc')
				->paginate(10);

			return view('almacen.order_type.index', ["order_types" => $order_types, "searchText" => $query]);
		}
	}

	public function create() {
		return view("almacen.order_type.create");
	}

	public function store(OrderTypeRequest $request) {
		$data = $request->all();

		$order_type = new OrderType;
		$order_type->fill($data);
		$order_type->save();
		return Redirect::to('admin/tipo-de-atencion');
	}

	public function edit($id) {

		return view("almacen.order_type.edit", ["order_type" => OrderType::findOrFail($id)]);
	}

	public function update(OrderTypeRequest $request, $id) {
		$data = $request->all();

		$order_type = OrderType::findOrFail($id);
		$order_type->fill($data);
		$order_type->save();
		return Redirect::to('admin/tipo-de-atencion');
	}

	public function destroy($id) {
		$order_type = OrderType::findOrFail($id);
		$order_type->delete();
		return Redirect::to('admin/tipo-de-atencion');
	}

}
