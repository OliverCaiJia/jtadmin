<?php

namespace App\Http\Requests\Admin;

use App\Http\Requests\Request;

class DepartmentRequest extends Request
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
        if ($this->isMethod('post')) {
            return [
                'name' => 'required|unique:admin_departments|max:60',
            ];
        } else {
            return [
                'name' => 'required|unique:admin_departments,name,' . $this->get('id') . '|max:255',
            ];
        }
    }
}
