<?php

namespace App\Models\Chain\ChannelDelete;

use App\Constants\ChannelConstant;
use App\Events\OperationLogEvent;
use App\Models\Chain\AbstractHandler;
use App\Models\Factory\Saas\Channel\ChannelFactory;
use Grpc\Channel;

class UpdateChannelSaasAction extends AbstractHandler
{
    private $params = [];
    protected $error = ['error' => '删除合作方渠道对应关系表失败！', 'code' => 7552];

    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * 第二步:更新合作方渠道对应关系表
     *
     * @return array
     */
    public function handleRequest()
    {
        if ($this->updateChannelSaas($this->params)) {
            return true;
        } else {
            return $this->error;
        }
    }

    /**
     * 合作方渠道对应关系表插入数据
     *
     * @param $params
     *
     * @return bool
     */
    private function updateChannelSaas($params)
    {
        $channelInfo = ChannelFactory::getChannelInfoById($params['id']);
        if ($channelInfo['type'] == ChannelConstant::SAAS_CHANNEL_TYPE_DIRECTED) {
            $res = ChannelFactory::deleteChannelSaasByChannelId($params['id']);
            $this->triggerEvent($channelInfo);
            return $res;
        }
        $this->triggerEvent($channelInfo);
        return true;
    }

    private function triggerEvent($params)
    {
        event(new OperationLogEvent(102, json_encode($params)));
    }
}
