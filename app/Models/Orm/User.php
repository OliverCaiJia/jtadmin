<?php

namespace App\Models\Orm;

use App\Models\AbsBaseModel;

class User extends AbsBaseModel
{
    const TABLE_NAME = 'users';
    const PRIMARY_KEY = 'id';

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = self::TABLE_NAME;
    //主键id
    protected $primaryKey = self::PRIMARY_KEY;
    //查询字段
    protected $visible = [];
    //加黑名单
    protected $guarded = [];

    public function orders()
    {
        return $this->hasMany(UserOrder::class);
    }
}
