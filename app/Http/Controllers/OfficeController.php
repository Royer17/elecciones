<?php

namespace sisVentas\Http\Controllers;

use DB;
use Fpdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use sisVentas\DocumentType;
use sisVentas\Entity;
use sisVentas\Http\Requests\OfficeFormRequest;
use sisVentas\Office;
use Datatables;

class OfficeController extends Controller {
	public function __construct() {
		$this->middleware('auth');
	}

	public function datatable(Request $request) {
			//$query = trim($request->get('searchText'));
			$year = $request->year;
			
			$result = DB::table('offices')
				//->where('name', 'LIKE', '%' . $query . '%')
				->orderBy('upper_office_id', 'asc')
				->where('deleted_at', NULL)
				->where('status', 1)
				->where(function($query) use($year) {
					if ($year != "") {
						$query->where('year', $year)
							->orWhere('is_for_all_years', true);
					}

				})
				->select(['id', 'code', 'name', 'created_at', 'sigla', 'upper_office_id', 'year', 'is_for_all_years'])
				->get();

			return $result;

		// return DataTables::of($result)
		// 	->addColumn('Actions', function ($model) {

		// 		return "";

		// 	})
		// 	->make(true);
	}

	public function index(Request $request) {
		if ($request) {
			$query = trim($request->get('searchText'));
			$offices = DB::table('offices')->where('name', 'LIKE', '%' . $query . '%')
				->orderBy('id', 'desc')
				->where('status', 1)
				->paginate(10);
			return view('almacen.office.index', ["offices" => $offices, "searchText" => $query]);
		}
	}
	public function create() {

		$entities = Entity::whereType(2)
			->get();

		$offices = Office::all();
		return view("almacen.office.create", compact('entities', 'offices'));
	}
	public function store(OfficeFormRequest $request) {

		$data = $request->all();
		$data['status'] = 1;
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

		$document_types = DocumentType::with(['office' => function($query) use($id) {
			$query->where('office_id', $id);
		}])
			->get();

		$document_types_selected = DocumentType::with(['office_selected' => function($query) use($id) {
			$query->where('office_id', $id);
		}])
			->get();

		// $document_types_selected = DB::table('document_types')
		// 	->leftJoin('document_type_office_selected', 'document_types.id', '=', 'document_type_office_selected.document_type_id')
		// 	->where('document_type_office_selected.office_id', $id)
		// 	->get(['document_types.id', 'document_types.name', 'document_type_office_selected.office_id']);

		//return $document_types_selected;

		return view("almacen.office.edit", ["office" => Office::findOrFail($id), "entities" => $entities, "offices" => $offices, 'document_types' => $document_types, 'document_types_selected' => $document_types_selected]);
	}

	public function update(OfficeFormRequest $request, $id) {
		$data = $request->all();

		$data['is_for_all_years'] = false;
		if ($request->has('is_for_all_years')) {
			$data['is_for_all_years'] = true;
		}

		$office = Office::findOrFail($id);
		$office->fill($data);
		$office->save();

		if ($request->document_type_changed == 1) {
			$document_types_selected = $request->document_types_id_selected;
			$document_types_selected_arr = explode(',', $document_types_selected);

			$office->document_types_selected()->detach();
			foreach ($document_types_selected_arr as $key => $document_type_id) {
				$office->document_types_selected()->attach($document_type_id);
			}	
		}

		return Redirect::to('admin/oficinas');
	}

	public function destroy($id) {

		$office = Office::with('personal')
			->with('orders_1')
			->with('orders_2')
			->with('details')
			->findOrFail($id);

		if ($id == 60) {
			return redirect()->intended('/admin/oficinas')->with('data', ["No se puede eliminar la oficina {$office->name}. Es la oficina principal."]);
		}

		if (count($office->personal)) {
			return redirect()->intended('/admin/oficinas')->with('data', ["No se puede eliminar la oficina {$office->name}. Hay personal asignado a este."]);

		}

		if (count($office->orders_1)) {
			return redirect()->intended('/admin/oficinas')->with('data', ["No se puede eliminar la oficina {$office->name}. Hay solicitudes asignados a este."]);

		}

		if (count($office->orders_2)) {
			return redirect()->intended('/admin/oficinas')->with('data', ["No se puede eliminar la oficina {$office->name}. Hay solicitudes asignados a este."]);
		}

		if (count($office->details)) {
			return redirect()->intended('/admin/oficinas')->with('data', ["No se puede eliminar la oficina {$office->name}. Hay solicitudes asignados a este."]);
		}

		$office->delete();
		return Redirect::to('admin/oficinas');
	}

	public function delete($id) {

		$office = Office::with('details')
			->findOrFail($id);

		if (count($office->details)) {
			return response()->json(['title' => 'Error', 'message' => "No se puede eliminar el concepto {$office->name}. Existen pagos asignados a este."], 400);
		}

		$office->delete();
		return response()->json(['title' => 'Operación Exitosa', 'message' => "Se ha elimindao correctamente el concepto de pago."], 200);

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

	public function update_document_type_office(Request $request)
	{
		$office_id = $request->office_id;

		$office = Office::find($office_id);

		$office->document_types()->detach();

		$document_type_id_arr = $request->document_type_id;
		$start_with_arr = $request->start_with;

		foreach ($document_type_id_arr as $key => $document_type_id) {
			$office->document_types()->attach($document_type_id, ['start_with' => $start_with_arr[$key]]);
		}
		
		return redirect("/admin/oficinas/{$office_id}/edit");


	}

	function update_order(Request $request)
	{
		$new_order_sortered_by_id = $request->order;

		foreach ($new_order_sortered_by_id as $key => $id) {
			$concept = Office::find($id);
			$concept->upper_office_id = $key + 1;
			$concept->save();
		}

		return response()->json(['title' => 'Operación Exitosa', 'message' => 'Se ha actualizado el orden.'], 200);

	}

}
