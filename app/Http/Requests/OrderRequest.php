<?php

namespace sisVentas\Http\Requests;

use Carbon\Carbon;
use Illuminate\Support\Facades\Input;
use sisVentas\Http\Requests\Request;

class OrderRequest extends Request {
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

		if (Input::get('type_document') == 1) {
			return [
				'name' => 'required',
				'paternal_surname' => 'required',
				'maternal_surname' => 'required',
				'identity_document' => 'required|digits_between:8,10',
				'cellphone' => 'required|digits_between:6,9',
				'email' => 'email',
				'subject' => 'required',
				//'tupa_id' => 'required',
				//'attached_file' => 'required',
			];
		} else {
			return [
				'business_name' => 'required',
				'ruc' => 'required|digits:11',
				'cellphone' => 'required|digits_between:6,9',
				'email' => 'email',
				'subject' => 'required',
				//'tupa_id' => 'required',
				//'attached_file' => 'required',
			];

		}
	}
}
