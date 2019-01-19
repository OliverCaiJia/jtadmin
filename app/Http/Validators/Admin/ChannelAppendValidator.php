<?php

namespace App\Http\Validators\Admin;

use App\Constants\ChannelConstant;
use App\Http\Validators\AbstractValidator;
use App\Models\Orm\SaasChannel;

class ChannelAppendValidator extends AbstractValidator
{
    /**
     * Validation rules
     *
     * @var Array
     */
    protected $rules = array(
        'name' => ['max:255', 'required'],
        'product_name' => ['max:255', 'required'],
        'nid' => ['alpha_num_nid', 'required', 'unique_channel_id'],
        'type' => ['required'],
        'saas_id' => ['numeric'],
        'contact_person' => ['max:255'],
        'contact_mobile' => ['max:50'],
        'promotion_cost' => ['required', 'numeric', 'between:0,100000000'],
        'borrowing_balance' => ['required', 'numeric', 'between:0,100000000'],
        'cycle' => ['required', 'numeric', 'digits_between:1,10'],
        'picture' => ['required'],
    );

    /**
     * Validation messages
     *
     * @var Array
     */
    protected $messages = array(
        'name.required' => '渠道名称必须输入',
        'product_name.required' => '产品名称必须输入',
        'nid.required' => '渠道Id必须输入',
        'type.required' => '合作方式必须输入',
        'promotion_cost.required' => '单价必须输入',
        'borrowing_balance.required' => '借款金额必须输入',
        'cycle.required' => '借款周期必须输入',
        'name.max' => '渠道名称不能超过255个字符',
        'product_name.max' => '产品名称不能超过255个字符',
        'nid.alpha_num_nid' => '渠道Id必须为4到23位的字母数字或下划线',
        'saas_id.numeric' => '合作方填写格式错误',
        'promotion_cost.numeric' => '单价必须为数字',
        'borrowing_balance.numeric' => '借款金额必须为数字',
        'contact_person.max' => '联系人不能超过255个字符',
        'contact_mobile.max' => '联系方式不能超过50个字符',
        'nid.unique_channel_id' => '此渠道ID已存在',
        'promotion_cost.between' => '单价应大于0',
        'borrowing_balance.between' => '借款金额应大于0',
        'cycle.between:between' => '借款周期应大于0',
        'cycle.digits_between' => '借款周期应为1到10位数字',
        'picture.required' => '必须上传图片',
    );

    /**
     * 自定义验证规则或者扩展Validator类
     */
    public function before()
    {
        // 渠道ID必须唯一
        $this->extend('unique_channel_id', function ($attribute, $value, $parameters) {
            $user = SaasChannel::where([$attribute => $value, 'is_deleted' => ChannelConstant::SAAS_CHANNEL_DELETED_FALSE])->get()->toArray();
            if (!empty($user)) {
                return false;
            }
            return true;
        });

        // 渠道ID必须为4到23位的字母或数字
        $this->extend('alpha_num_nid', function ($attribute, $value, $parameters) {
            return preg_match('/^[0-9a-zA-Z_]{4,23}$/', $value);
        });
    }
}
