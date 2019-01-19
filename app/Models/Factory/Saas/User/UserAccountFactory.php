<?php

namespace App\Models\Factory\Saas\User;

use App\Models\AbsModelFactory;
use App\Models\Orm\SaasAccount;

class UserAccountFactory extends AbsModelFactory
{
    /**
     * 通过 id 获取余额
     *
     * @param $id
     *
     * @return mixed
     */
    public static function getBalanceById($id)
    {
        return SaasAccount::where('id', $id)->first()->balance;
    }
}
