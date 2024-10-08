<?php

namespace sisVentas\Http\Requests;

use sisVentas\DocumentType;
use sisVentas\Http\Requests\Request;

class LoggedSolicitudeUpdateRequest extends Request
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
                'document_type_id' => 'required',
                'subject' => 'required',
                //'tupa_id' => 'required',
                'order_type_id' => 'required',
                //'attached_file' => 'required',
        ];
    }
}
