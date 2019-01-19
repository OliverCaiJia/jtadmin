<?php

namespace App\Http\Validators\Admin;

use App\Http\Validators\AbstractValidator;

class OrderAssignValidator extends AbstractValidator
{

    /**
     * Validation rules
     *
     * @var Array
     */
    protected $rules = array(
        'saas_user_id' => ['required'],
    );

    /**
     * Validation messages
     *
     * @var Array
     */
    protected $messages = array(
        'saas_user_id.required' => '合作方必选',
    );
}
