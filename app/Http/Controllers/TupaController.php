<?php

namespace sisVentas\Http\Controllers;

use DB;
use Fpdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use sisVentas\Entity;
use sisVentas\Http\Requests\TupaFormRequest;
use sisVentas\Office;
use sisVentas\Tupa;
use sisVentas\TupaRequirement;
use Carbon\Carbon;

class TupaController extends Controller {
	public function __construct() {
		$this->middleware('auth');
	}
	
	public function index(Request $request) {
		if ($request) {
			$query = trim($request->get('searchText'));
			$tupa = DB::table('tupa')->where('title', 'LIKE', '%' . $query . '%')
				//->orderBy('id', 'desc')
				->where('deleted_at', NULL)
				->paginate(10);
			return view('almacen.tupa.index', ["procediments" => $tupa, "searchText" => $query]);
		}
	}
	public function create() {

		$entities = Entity::whereType(2)
			->get();

		$offices = Office::all();
		return view("almacen.tupa.create", compact('entities', 'offices'));
	}
	public function store(TupaFormRequest $request) {
		$data = $request->all();

		$tupa = new Tupa;
		$tupa->fill($data);
		$tupa->save();
		return Redirect::to('admin/tupa');
	}

	// public function show($id) {
	// 	return view("almacen.categoria.show", ["categoria" => Categoria::findOrFail($id)]);
	// }

	public function edit($id) {

		$entities = Entity::whereType(2)
			->get();

		$offices = Office::all();

		$tupa_requirements = TupaRequirement::whereTupaId($id)
			->get();

		return view("almacen.tupa.edit", ["tupa" => Tupa::findOrFail($id), "entities" => $entities, "offices" => $offices, 'requirements' => $tupa_requirements]);
	}

	public function update(TupaFormRequest $request, $id) {
		$data = $request->all();

		$tupa = Tupa::findOrFail($id);
		$tupa->fill($data);
		$tupa->save();
		return Redirect::to('admin/tupa');
	}

	public function destroy($id) {
		$tupa = Tupa::findOrFail($id);

		//$tupa_requirements = TupaRequirement::whereTupaId($id)->delete();

		DB::table('tupa_requirements')
			->where('tupa_id', $id)
			->update(['deleted_at' => Carbon::now()->format('Y-m-d H:i:s')]);

		$tupa->delete();
		return Redirect::to('admin/tupa');
	}

	public function reporte() {
		//Obtenemos los registros
		$registros = DB::table('categoria')
			->where('condicion', '=', '1')
			->orderBy('nombre', 'asc')
			->get();

		$pdf = new Fpdf();
		$pdf::AddPage();
		$pdf::SetTextColor(35, 56, 113);
		$pdf::SetFont('Arial', 'B', 11);
		$pdf::Cell(0, 10, utf8_decode("Listado Categorías"), 0, "", "C");
		$pdf::Ln();
		$pdf::Ln();
		$pdf::SetTextColor(0, 0, 0); // Establece el color del texto
		$pdf::SetFillColor(206, 246, 245); // establece el color del fondo de la celda
		$pdf::SetFont('Arial', 'B', 10);
		//El ancho de las columnas debe de sumar promedio 190
		$pdf::cell(50, 8, utf8_decode("Nombre"), 1, "", "L", true);
		$pdf::cell(140, 8, utf8_decode("Descripción"), 1, "", "L", true);

		$pdf::Ln();
		$pdf::SetTextColor(0, 0, 0); // Establece el color del texto
		$pdf::SetFillColor(255, 255, 255); // establece el color del fondo de la celda
		$pdf::SetFont("Arial", "", 9);

		foreach ($registros as $reg) {
			$pdf::cell(50, 6, utf8_decode($reg->nombre), 1, "", "L", true);
			$pdf::cell(140, 6, utf8_decode($reg->descripcion), 1, "", "L", true);
			$pdf::Ln();
		}

		$pdf::Output();
		exit;
	}

}
