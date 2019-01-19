<?php

namespace App\Http\Validators\Admin;

use App\Http\Validators\AbstractValidator;
use Carbon\Carbon;

/**
 * 编辑合作方信息验证器
 * Class SaasEditValidator
 * @package App\Http\Validators\Admin
 */
class SaasEditValidator extends AbstractValidator
{
    /**
     * Validation rules
     *
     * @var Array
     */
    protected $rules = array(
        'full_company_name' => ['max:255','required'],
        'short_company_name' => ['max:255','required'],
        'valid_deadline' => ['date_after','required'],
        'order_price' => ['numeric','required','min:0'],
        'order_deadline' => ['date_after','required'],
        'max_order_num' => ['numeric','required','digits_between:1,15'],
    );

    /**
     * Validation messages
     *
     * @var Array
     */
    protected $messages = array(
        'full_company_name.required' => '公司名称必须输入',
        'short_company_name.required' => '公司简称必须输入',
        'valid_deadline.required' => '账户有效期必须输入',
        'order_price.required' => '单价必须输入',
        'order_price.numeric' => '单价必须输入数字',
        'order_price.min' => '单价必须大于0',
        'full_company_name.max' => '公司名称不能超过255个字符',
        'short_company_name.max' => '公司简称不能超过255个字符',
        'valid_deadline.date_after' => '账户截止日期不合法',
        'order_deadline.required' => '订单截止日期必须输入',
        'order_deadline.date_after' => '订单截止日期不合法',
        'max_order_num.required' => '最大订单数量必须输入',
        'max_order_num.numeric' => '最大订单数量必须为数字',
        'max_order_num.digits_between' => '最大订单数量为1到15位',
    );

    /**
     * 自定义验证规则或者扩展Validator类
     */
    public function before()
    {
        $this->extend('date_after', function ($attribute, $value, $parameters) {
            return $value >= Carbon::now();
        });
    }
}
