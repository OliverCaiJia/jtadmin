<?php

namespace App\Models\Factory\User;

use App\Models\AbsModelFactory;
use App\Models\Orm\User;

class UserFactory extends AbsModelFactory
{
    /**
     * 通过用户主键ID获取用户资料
     * @param $id
     * @return array
     */
    public static function getUserInfoById($id)
    {
        return User::whereKey($id)->first()->toArray();
    }
}
