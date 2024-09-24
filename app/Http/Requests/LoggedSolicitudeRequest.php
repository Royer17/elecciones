<?php

namespace sisVentas\Http\Requests;

use sisVentas\DocumentType;
use sisVentas\Http\Requests\Request;
use Illuminate\Support\Facades\Input;

class LoggedSolicitudeRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
                'identity_document' => 'required',
                'name' => 'required',
                'paternal_surname' => 'required',
                'maternal_surname' => 'required',
                'tupa_id' => 'required',
                'order_type_id' => 'required',
                //'subject' => 'required',
                //'identity_document_parent' => 'required',
                //'paternal_surname_parent' => 'required',
                //'maternal_surname_parent' => 'required',
                //'name_parent' => 'required',
        ];
    }
}
