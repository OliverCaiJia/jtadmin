<?php

namespace App\Http\Validators\Admin;

use App\Http\Validators\AbstractValidator;
use App\Models\Orm\SaasPerson;
use Carbon\Carbon;

/**
 * 新增合作方信息验证器
 * Class SaasAppendValidator
 *
 * @package App\Http\Validators\Admin
 */
class SaasAppendValidator extends AbstractValidator
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
        'password' => ['required','confirmed', 'alpha_num_password'],
        'account_name' => ['unique_saas_user','alpha_num_account_name', 'required'],
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
        'order_price.min' => '单价必须大于0',
        'account_name.required' => '账户名必须输入',
        'password.required' => '密码必须输入',
        'password.alpha_num_password' => '密码必须为8-16位字母数字或下划线',
        'full_company_name.max' => '公司名称不能超过255个字符',
        'short_company_name.max' => '公司简称不能超过255个字符',
        'valid_deadline.date_after' => '账户有效期不合法',
        'account_name.unique_saas_user' => '此账号已存在',
        'account_name.alpha_num_account_name' => '账户名必须为6到23位的字母、下划线和数字组合',
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
        $this->extend('unique_saas_user', function ($attribute, $value, $parameters) {
            $user = SaasPerson::where([
                'username' => $value,
            ])->get()->toArray();
            if (!empty($user)) {
                return false;
            }
            return true;
        });

        $this->extend('alpha_num_account_name', function ($attribute, $value, $parameters) {
            return preg_match('/^[0-9a-zA-Z_]{6,23}$/', $value);
        });

        $this->extend('alpha_num_password', function ($attribute, $value, $parameters) {
            return preg_match('/^[0-9a-zA-Z_]{8,16}$/', $value);
        });

        $this->extend('date_after', function ($attribute, $value, $parameters) {
            return $value >= Carbon::now();
        });
    }
}
