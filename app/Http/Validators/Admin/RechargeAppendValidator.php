<?php

namespace App\Http\Validators\Admin;

use App\Http\Validators\AbstractValidator;


class RechargeAppendValidator extends AbstractValidator
{

    /**
     * Validation rules
     *
     * @var Array
     */
    protected $rules = array(
        'money' => ['numeric', 'required', 'digits_between:1,8', 'min:1'],
    );

    /**
     * Validation messages
     *
     * @var Array
     */
    protected $messages = array(
        'money.required' => '金额必须输入',
        'money.numeric' => '金额必须输入数字',
        'money.digits_between' => '金额必须是1到8位之间的正数'
    );
}
