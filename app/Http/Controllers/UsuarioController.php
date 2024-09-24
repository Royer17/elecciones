<?php

namespace sisVentas\Http\Controllers;

use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use sisVentas\Entity;
use sisVentas\Http\Requests\UsuarioFormRequest;
use sisVentas\Http\Requests\UsuarioFormUpdateRequest;
use sisVentas\User;
use Auth;

class UsuarioController extends Controller {
	public function __construct() {
		$this->middleware('auth');
	}

	public function index(Request $request) {
		if ($request) {
			$text = trim($request->get('searchText'));
			$usuarios = DB::table('users')
				->join('entities', 'users.entity_id', '=', 'entities.id')
				->orderBy('users.id', 'desc')
				//->where('users.deleted_at', NULL)
				->where('entities.deleted_at', NULL)
				->where('users.status', 1)
				->select(['users.id as user_id', 'users.email as user_name', 'entities.name as entity_name', 'entities.paternal_surname as entity_paternal_surname', 'entities.maternal_surname as entity_maternal_surname', 'users.activated']);

				if ($text) {
					$usuarios = $usuarios->where(function($query) use($text){
						$query->where('users.email', 'LIKE', '%' . $text . '%')
							->orWhere('entities.name', 'LIKE', '%' . $text . '%')
							->orWhere('entities.paternal_surname', 'LIKE', '%' . $text . '%')
							->orWhere('entities.maternal_surname', 'LIKE', '%' . $text . '%');

					});
				}

				$usuarios = $usuarios->paginate(10);
			return view('seguridad.usuario.index', ["usuarios" => $usuarios, "searchText" => $text]);
		}
	}

	public function create() {
		$entities = Entity::whereType(2)
			->where('status', 1)
			->get();

		return view("seguridad.usuario.create", compact('entities'));
	}
	public function store(UsuarioFormRequest $request) {
		$usuario = new User;

		$entity = Entity::find($request->entity_id);

		$data = $request->except('password');
		$data['email'] = $data['username'];
		$usuario->name = "";
		$usuario->status = 1;
		$usuario->sigla = $entity->sigla;
		$usuario->fill($data);
		$usuario->password = bcrypt($request->get('password'));
		$usuario->save();
		return Redirect::to('seguridad/usuario');
	}
	public function edit($id) {

		$entities = Entity::whereType(2)
			->get();

		return view("seguridad.usuario.edit", ["usuario" => User::findOrFail($id), "entities" => $entities]);
	}
	public function update(UsuarioFormUpdateRequest $request, $id) {

		$usuario = User::findOrFail($id);
		$usuario->email = $request->get('username');
		$usuario->role_id = $request->get('role_id');
		$usuario->sigla = $request->get('sigla');

		if ($request->password) {
			$usuario->password = bcrypt($request->get('password'));
		}

		$usuario->update();
		return Redirect::to('seguridad/usuario');
	}
	public function destroy($id) {
		$usuario = DB::table('users')->where('id', '=', $id)->delete();
		return Redirect::to('seguridad/usuario');
	}

	public function suspend($id)
	{
		$user = User::find($id);
		$user->activated = 0;
		$user->save();
	}
	
	public function active($id)
	{
		$user = User::find($id);
		$user->activated = 1;
		$user->save();
	}

	public function update_password($user_id, Request $request)
	{
		$usuario = User::findOrFail($user_id);
		$usuario->password = bcrypt($request->get('password'));
		$usuario->update();

		Auth::logout();

		return Redirect::to('/login');

	}
}
