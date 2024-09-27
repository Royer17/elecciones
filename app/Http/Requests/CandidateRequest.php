<?php

namespace sisVentas\Http\Requests;

use sisVentas\Http\Requests\Request;

class CandidateRequest extends Request
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
            'cedula' => 'required|unique:candidates,cedula,' . $this->id . ',id',
            'firstname' => 'required',
            'lastname' => 'required',
            'position' => 'required',
            'nivel' => 'required',
        ];
    }
}
