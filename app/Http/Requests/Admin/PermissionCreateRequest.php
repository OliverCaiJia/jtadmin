<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class PermissionCreateRequest extends Request
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
            'name' => 'required|unique:admin_permissions|max:255',
            'label' => 'required|max:255',
            'cid' => 'required|int',
            'description' => 'required|max:255',
        ];
    }
}
