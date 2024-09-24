<?php

namespace sisVentas\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use sisVentas\Entity;
use sisVentas\Office;
use sisVentas\Profession;
use sisVentas\User;

class EntityController extends Controller {
	public function __construct() {
		$this->middleware('auth');
	}
	public function index(Request $request) {
		if ($request) {
			$query = trim($request->get('searchText'));
			$entities = DB::table('entities')->where('entities.name', 'LIKE', '%' . $query . '%')
				->orderBy('entities.id', 'desc')
				->where('entities.deleted_at', NULL)
				->where('entities.type', 2)
				->leftJoin('professions', 'entities.profession_id', '=', 'professions.id')
				->select(['entities.id as id', 'entities.identity_document as identity_document', DB::raw('CONCAT(entities.name, " ", entities.paternal_surname, " ", entities.maternal_surname) AS full_name'), 'entities.cellphone as cellphone', 'professions.name as profession_name'])
				->paginate(10);
			return view('almacen.entity.index', ["entities" => $entities, "searchText" => $query]);
		}
	}
	public function create() {

		$professions = Profession::all();
		$offices = Office::all();
		return view("almacen.entity.create", compact('professions', 'offices'));

	}
	public function store(Request $request) {
		$data = $request->all();

		$entity = new Entity;
		$entity->fill($data);
		$entity->type_document = 1;
		$entity->sigla = strtoupper(substr($request->name, 0, 1)).strtoupper(substr($request->paternal_surname, 0, 1)).strtoupper(substr($request->maternal_surname, 0, 1));
		$entity->type = 2;
		$entity->status = 1;

		$entity->save();
		return Redirect::to('admin/personal');
	}

	// public function show($id) {
	// 	return view("almacen.categoria.show", ["categoria" => Categoria::findOrFail($id)]);
	// }

	public function edit($id) {

		$professions = Profession::all();
		//$offices = Office::all();

		$offices = DB::table('offices')
			->where('deleted_at', NULL)
			->get();


		$user = User::whereEntityId($id)
			->first();

		$disabled = "";
		
		if ($user) {
			if ($user->role_id == 2) {
				$disabled = "disabled";
			}
		}

		return view("almacen.entity.edit", ["entity" => Entity::findOrFail($id), "professions" => $professions, "offices" => $offices, 'disabled' => $disabled]);
	}

	public function update(Request $request, $id) {
		$data = $request->all();

		$entity = entity::findOrFail($id);
		$entity->fill($data);
		$entity->save();
		return Redirect::to('admin/personal');
	}

	public function destroy($id) {
		$entity = entity::with('user')->with('orders')->findOrFail($id);
		
		if (!empty($entity->user)) {
			return redirect()->intended('/admin/personal')->with('data', ["No se puede eliminar el personal {$entity->name}. Hay un usuario asignado a este."]);
		}
		
		if (!empty($entity->orders)) {
			return redirect()->intended('/admin/personal')->with('data', ["No se puede eliminar el personal {$entity->name}. Hay solicitudes asignados a este."]);
		}


		$entity->delete();
		return Redirect::to('admin/personal');
	}
}
