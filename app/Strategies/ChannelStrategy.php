<?php

namespace App\Strategies;

use Hashids;

/**
 * 渠道管理公共策略
 * Class ChannelStrategy
 * @package App\Strategies
 */
class ChannelStrategy extends AppStrategy
{
    /**
     * 生成渠道url
     * @param $hashId
     * @return string
     */
    public static function generateUrl($hashId)
    {
        return config('sudai.channel_url_prefix') . '?channel_id=' . $hashId;
    }

    /**
     * 通过渠道主键ID生成hash_id
     * @param $channelId
     * @return mixed
     */
    public static function generateHashId($channelId)
    {
        return Hashids::encode($channelId);
    }
}
