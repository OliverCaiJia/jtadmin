<?php

namespace App\Strategies;

use App\Models\Factory\Admin\AdminUser\AdminUserFactory;

class AdminUserStrategy extends AppStrategy
{
    /**
     * 获取管理员下级id
     *
     * @param $id
     * @param $users
     *
     * @return array
     */
    public static function getSubIds($id, $users)
    {
        $ids = (array) $id;

        $children = array_filter($users, function ($val) use ($id) {
            return $val['created_by'] == $id;
        });

        foreach ($children as &$child) {
            $ids = array_merge($ids, self::getSubIds($child['id'], $users));
        }

        return $ids;
    }

    /**
     * 获取子 id 值
     *
     * @param $id
     *
     * @return array
     */
    public static function getIdsBySubIds($id)
    {
        return self::getSubIds($id, AdminUserFactory::getList());
    }
}
