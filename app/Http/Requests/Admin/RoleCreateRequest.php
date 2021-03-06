<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class RoleCreateRequest extends Request
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
            'name' => 'required|unique:admin_roles|max:255',
            'description' => 'required|max:255',
            'dpm_id' => 'required',
        ];
    }
}
