<?php

namespace sisVentas\Http\Requests;

use sisVentas\Http\Requests\Request;

class OfficeFormRequest extends Request {
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
			'name' => 'required|unique:offices,name,'.$this->input('id').',id,deleted_at,NULL',
			//'entity_id' => 'required',
		];
	}
}
