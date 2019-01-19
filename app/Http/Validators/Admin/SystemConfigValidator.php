<?php

namespace App\Http\Validators\Admin;

use App\Http\Validators\AbstractValidator;

class SystemConfigValidator extends AbstractValidator
{
    /**
     * Validation rules
     *
     * @var Array
     */
    protected $rules = array(
        'nid' => ['required'],
        'value' => ['required'],
        'remark' => ['required'],
        'status' => ['required'],
    );

    /**
     * Validation messages
     *
     * @var Array
     */
    protected $messages = array(
        
    );
}
