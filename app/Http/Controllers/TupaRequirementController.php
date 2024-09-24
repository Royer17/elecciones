<?php

namespace sisVentas\Http\Controllers;

use DB;
use Fpdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use sisVentas\Entity;
use sisVentas\Http\Requests\TupaRequirementCreateRequest;
use sisVentas\Office;
use sisVentas\Tupa;
use sisVentas\TupaRequirement;
use Illuminate\Support\Facades\Input;

class TupaRequirementController extends Controller {

	public function __construct() {
		$this->middleware('auth');
	}
	
	public function index(Request $request) {
		if ($request) {
			$query = trim($request->get('searchText'));
			$tupa = DB::table('tupa')->where('title', 'LIKE', '%' . $query . '%')
				->orderBy('id', 'desc')
				->paginate(7);
			return view('almacen.tupa.index', ["procediments" => $tupa, "searchText" => $query]);
		}
	}
	public function create() {

		$entities = Entity::whereType(2)
			->get();

		$offices = Office::all();
		return view("almacen.tupa.create", compact('entities', 'offices'));
	}
	public function store(TupaRequirementCreateRequest $request) {
		$data = $request->all();

		$tupa_requirement = new TupaRequirement();

		if (Input::hasFile('attached_file')) {
			$file = Input::file('attached_file');
			$file->move(public_path() . '/archivos/tupa-requisitos/', $file->getClientOriginalName());
			$path = '/archivos/tupa-requisitos/' . $file->getClientOriginalName();
			$data['link'] = $path;
		}

		$tupa_requirement->fill($data);
		$tupa_requirement->save();

		return Redirect::to("admin/tupa/{$tupa_requirement->tupa_id}/edit");
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

	public function update(TupaRequirementCreateRequest $request, $id) {
		$data = $request->all();

		$tupa_requirement = TupaRequirement::find($id);

		if (Input::hasFile('attached_file')) {
			$file = Input::file('attached_file');
			$file->move(public_path() . '/archivos/tupa-requisitos/', $file->getClientOriginalName());
			$path = '/archivos/tupa-requisitos/' . $file->getClientOriginalName();
			$data['link'] = $path;
		}

		$tupa_requirement->fill($data);
		$tupa_requirement->save();

		return Redirect::to("admin/tupa/{$tupa_requirement->tupa_id}/edit");
	}

	public function destroy($id) {
		$tupa_requirement = TupaRequirement::findOrFail($id);

		$tupa_id = $tupa_requirement->tupa_id;

		$tupa_requirement->delete();
		return;
	}


}
