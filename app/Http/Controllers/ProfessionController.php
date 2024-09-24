<?php

namespace sisVentas\Http\Controllers;

use DB;
use Fpdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use sisVentas\Http\Requests\ProfessionFormRequest;
use sisVentas\Profession;

class ProfessionController extends Controller {
	public function __construct() {
		$this->middleware('auth');
	}
	public function index(Request $request) {
		if ($request) {
			$query = trim($request->get('searchText'));
			$professions = DB::table('professions')->where('name', 'LIKE', '%' . $query . '%')
				->orderBy('id', 'desc')
				->paginate(10);
			return view('almacen.profession.index', ["professions" => $professions, "searchText" => $query]);
		}
	}
	public function create() {
		return view("almacen.profession.create");
	}
	public function store(ProfessionFormRequest $request) {
		$data = $request->all();

		$profession = new Profession;
		$profession->fill($data);
		$profession->save();
		return Redirect::to('admin/profesiones');
	}

	// public function show($id) {
	// 	return view("almacen.categoria.show", ["categoria" => Categoria::findOrFail($id)]);
	// }

	public function edit($id) {
		return view("almacen.profession.edit", ["profession" => Profession::findOrFail($id)]);
	}

	public function update(ProfessionFormRequest $request, $id) {
		$data = $request->all();

		$profession = Profession::findOrFail($id);
		$profession->fill($data);
		$profession->save();
		return Redirect::to('admin/profesiones');
	}

	public function destroy($id) {
		$profession = Profession::findOrFail($id);
		$profession->delete();
		return Redirect::to('admin/profesiones');
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
