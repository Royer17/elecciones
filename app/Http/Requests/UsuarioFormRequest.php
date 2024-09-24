<?php

namespace sisVentas\Http\Requests;

use sisVentas\Http\Requests\Request;

class UsuarioFormRequest extends Request {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	public function authorize() {
		return true;
	}

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		return [
			'username' => 'required|max:255|unique:users,email',
			'entity_id' => 'required',
			'password' => 'required|min:6|confirmed',
			'role_id' => 'required',
		];
	}
}
