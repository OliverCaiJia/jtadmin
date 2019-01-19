<?php

namespace App\Models\Orm;

use App\Models\AbsBaseModel;

class UserOrder extends AbsBaseModel
{
    const TABLE_NAME = 'user_orders';
    const PRIMARY_KEY = 'id';

    public $timestamps = true;

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

    protected $casts = [
        'request_text' => 'array',
        'saas_channel_detail' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userReport()
    {
        return $this->hasOne(UserReport::class, 'id', 'user_report_id');
    }
}
