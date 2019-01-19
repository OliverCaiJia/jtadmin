<?php

namespace App\Models\Factory\Saas\Channel;

use App\Constants\ChannelConstant;
use App\Models\Orm\SaasAuth;
use App\Models\Orm\SaasChannel;
use App\Models\Orm\SaasChannelSaas;
use App\Models\AbsModelFactory;

class ChannelFactory extends AbsModelFactory
{

    /**
     * 获取渠道名称通过id
     *
     * @param $id
     *
     * @return mixed|string
     */
    public static function getNameById($id)
    {
        $channel = SaasChannel::select('name')->find($id);

        return $channel->name ?? '未知';
    }

    /**
     * 通过渠道 id 获取渠道信息
     *
     * @param $id
     *
     * @return array
     */
    public static function getChannelInfoById($id)
    {
        $channel = SaasChannel::where('id', '=', $id)->first();

        return $channel ? $channel->toArray() : [];
    }

    /**
     * 通过nid获取渠道信息
     * @param $nid
     * @return array
     */
    public static function getChannelInfoByNid($nid)
    {
        $channel = SaasChannel::where('nid', '=', $nid)->first();

        return $channel ? $channel->toArray() : [];
    }

    /**
     * 通过渠道ID获得所属
     *
     * @param $channelId
     * @param $field
     *
     * @return string
     */
    public static function getSaasInfoByChannelId($channelId, $field = 'short_company_name')
    {
        $saasInfo = SaasChannelSaas::where('channel_id', '=', $channelId)->first();
        if ($saasInfo) {
            $saasName = SaasAuth::whereKey($saasInfo->saas_user_id)->first([$field])->toArray();
            if ($saasName) {
                return $saasName[$field];
            }
        }
        return '';
    }

    /**
     * 插入渠道数据
     *
     * @param $insert
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function insertChannel($insert)
    {
        return SaasChannel::Create($insert);
    }

    /**
     * 插入合作方渠道关系数据
     *
     * @param $insert
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public static function insertChannelSaas($insert)
    {
        return SaasChannelSaas::updateOrCreate([
            'channel_id' => $insert['channel_id'],
            'saas_user_id' => $insert['saas_user_id'],
            'is_deleted' => ChannelConstant::SAAS_CHANNEL_DELETED_TRUE
        ], $insert);
    }

    /**
     * 通过渠道的展示ID获取渠道表的主键ID
     *
     * @param $displayId
     *
     * @return string
     */
    public static function getChannelPriKeyIdByDspId($displayId)
    {
        $channelInfo = SaasChannel::where('nid', $displayId)->first(['id'])->toArray();
        if (!empty($channelInfo['id'])) {
            return $channelInfo['id'];
        }
        return '';
    }

    /**
     * 软删除合作方渠道对应关系
     *
     * @param $channelPriKeyId
     *
     * @return bool
     */
    public static function deleteChannelSaasByChannelId($channelPriKeyId)
    {
        $saasChannelSaas = SaasChannelSaas::lockForUpdate()
            ->where(['channel_id' => $channelPriKeyId, 'is_deleted' => ChannelConstant::SAAS_CHANNEL_DELETED_FALSE])
            ->first();
        if ($saasChannelSaas) {
            $saasChannelSaas->is_deleted = ChannelConstant::SAAS_CHANNEL_DELETED_TRUE;
            return $saasChannelSaas->save();
        }
        return false;
    }

    /**
     * 软删除渠道表记录
     *
     * @param $channelPriKeyId
     *
     * @return bool
     */
    public static function deleteChannelSaas($channelPriKeyId)
    {
        $saasChannel = SaasChannel::lockForUpdate()
            ->where(['id' => $channelPriKeyId, 'is_deleted' => ChannelConstant::SAAS_CHANNEL_DELETED_FALSE])
            ->first();
        if ($saasChannel) {
            $saasChannel->is_deleted = ChannelConstant::SAAS_CHANNEL_DELETED_TRUE;
            return $saasChannel->save();
        }
        return false;
    }

    /**
     * 根据渠道表主键ID更新渠道信息
     *
     * @param $param
     *
     * @return int
     */
    public static function updateChannelById($param)
    {
        $update = [
            'name' => $param['name'],
            'product_name' => $param['product_name'],
            'contact_person' => $param['contact_person'],
            'contact_mobile' => $param['contact_mobile'],
            'picture' => $param['picture'],
            'promotion_cost' => $param['promotion_cost'],
            'borrowing_balance' => $param['borrowing_balance'],
            'repayment_method' => $param['repayment_method'],
            'cycle' => $param['cycle'],
        ];
        $saasChannel = SaasChannel::lockForUpdate()
            ->where(['id' => $param['id'], 'is_deleted' => ChannelConstant::SAAS_CHANNEL_DELETED_FALSE])
            ->update($update);
        return (bool)$saasChannel;
    }

    /**
     * 通过 hash_id 获取信息
     *
     * @param string $hashId
     *
     * @return mixed|string
     */
    public static function getInfoByHashId($hashId)
    {
        $channel = SaasChannel::where('hash_id', $hashId)
            ->where('is_deleted', 0)
            ->lockForUpdate()
            ->first();

        return $channel ?: '';
    }

    /**
     * 通过 hash_id 获取 saas_user_id
     *
     * @param string $hash_id
     *
     * @return mixed|string
     */
    public static function getSaasUserIdByHashId($hash_id)
    {
        $channelSaas = SaasChannelSaas::where('hash_id', $hash_id)
            ->select('saas_user_id')
            ->first();

        return $channelSaas ? $channelSaas->saas_user_id : '';
    }

    /**
     * 通过渠道主键ID更新渠道表某个字段
     *
     * @param $channelId
     * @param $update
     *
     * @return bool
     */
    public static function updateChannelFieldById($channelId, $update)
    {
        return SaasChannel::whereKey($channelId)->update($update);
    }

    /**
     * 检查表中是否已存在此hashid
     *
     * @param $hashId
     *
     * @return bool
     */
    public static function checkRepeatedHashId($hashId)
    {
        return !!SaasChannel::where(['hash_id' => $hashId])->count('hash_id');
    }

    /**
     * 检查表中是否已存在此nid
     *
     * @param $nid
     *
     * @return bool
     */
    public static function checkRepeatedNid($nid)
    {
        return !!SaasChannel::where(['nid' => $nid])->count('nid');
    }
}
