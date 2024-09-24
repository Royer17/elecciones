<?php

namespace sisVentas\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use sisVentas\Http\Requests\FeriadoRequest;
use sisVentas\Http\Requests\TupaFormRequest;
use sisVentas\Office;
use sisVentas\Feriado;
use Carbon\Carbon;
use Jenssegers\Date\Date;

class FeriadosController extends Controller {
	public function __construct() {
		$this->middleware('auth');
	}
	
	public function index(Request $request) {
		if ($request) {
			$query = trim($request->get('searchText'));
			$feriados = DB::table('feriados')->where('description', 'LIKE', '%' . $query . '%')
				->orderBy('id', 'desc')
				->where('deleted_at', NULL)
				->paginate(10);

			return view('almacen.feriado.index', ["feriados" => $feriados, "searchText" => $query]);
		}
	}

	public function create() {
		return view("almacen.feriado.create");
	}

	public function store(FeriadoRequest $request) {
		$data = $request->all();

		$fecha_parsed = Carbon::createFromFormat('d/m/Y', $data['fecha']);

		$data['date_string'] = Date::parse($fecha_parsed)->format('d \d\e F');

		$data['date'] = $fecha_parsed->format('Y-m-d');
		$data['month_day'] = $fecha_parsed->format('m-d');

		$feriado = new Feriado;
		$feriado->fill($data);
		$feriado->save();
		return Redirect::to('admin/feriados');
	}

	public function edit($id) {

		return view("almacen.feriado.edit", ["feriado" => Feriado::findOrFail($id)]);
	}

	public function update(FeriadoRequest $request, $id) {
		$data = $request->all();

		$fecha_parsed = Carbon::createFromFormat('d/m/Y', $data['fecha']);
		$data['date_string'] = Date::parse($fecha_parsed)->format('d \d\e F');

		$data['date'] = $fecha_parsed->format('Y-m-d');
		$data['month_day'] = $fecha_parsed->format('m-d');

		$feriado = Feriado::findOrFail($id);
		$feriado->fill($data);
		$feriado->save();
		return Redirect::to('admin/feriados');
	}

	public function destroy($id) {
		$feriado = Feriado::findOrFail($id);
		$feriado->delete();
		return Redirect::to('admin/feriados');
	}

}
