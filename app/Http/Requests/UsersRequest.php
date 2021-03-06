<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\User;

class UsersRequest extends Request
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
        $rules = [
            'name' => 'required',
            'email' => 'required',
            'role_id' => 'required',
            'is_active' => 'required'
        ];

        if ($this->method() == 'POST'){
            $rules['password'] = 'required';
        }

        return $rules;
    }
}
