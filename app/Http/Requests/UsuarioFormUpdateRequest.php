<?php

namespace sisVentas\Http\Requests;

use sisVentas\Http\Requests\Request;

class UsuarioFormUpdateRequest extends Request
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

        if ($this->input('password')) {
            return [

// 'name' => 'required|unique:subcategories,name,'.$this->input('subcategory_id').',id,category_id,' . $this->input('category_id'),

                'username' => 'required|max:255|unique:users,email,'.$this->input('id').',id',
                'password' => 'required|min:6|confirmed',
                'role_id' => 'required',
            ];
        }

        return [
            'username' => 'required|max:255|unique:users,email,'.$this->input('id').',id',
            'role_id' => 'required',
        ];
    }
}
