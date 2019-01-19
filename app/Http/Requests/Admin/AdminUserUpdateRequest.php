<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class AdminUserUpdateRequest extends Request
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
            'name' => 'required|max:255',
            'username' => 'required|unique:admin_users,username,' . $this->get('id') . '|max:255',
            'email' => 'required|email|unique:admin_users,email,' . $this->get('id') . '|max:255',
            'password' => 'confirmed'
        ];
    }
}
