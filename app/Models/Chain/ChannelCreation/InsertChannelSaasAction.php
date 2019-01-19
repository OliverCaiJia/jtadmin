<?php

namespace App\Models\Chain\ChannelCreation;

use App\Constants\ChannelConstant;
use App\Events\OperationLogEvent;
use App\Models\Chain\AbstractHandler;
use App\Models\Factory\Saas\Channel\ChannelFactory;

class InsertChannelSaasAction extends AbstractHandler
{
    private $params = [];
    protected $error = ['error' => '生成渠道失败，插入合作方渠道对应关系表失败！', 'code' => 7452];

    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * 第五步:插入合作方渠道对应关系表
     *
     * @return array
     */
    public function handleRequest()
    {
        if ($this->insertChannelSaas($this->params)) {
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
    private function insertChannelSaas($params)
    {
        //数组处理
        if ($params['type'] == ChannelConstant::SAAS_CHANNEL_TYPE_DIRECTED) {
            if (!isset($params['saas_id'])) {
                return false;
            }
            $insert = $this->getInsertData($params);
            $res = ChannelFactory::insertChannelSaas($insert);
            $this->triggerEvent($params);
            return $res;
        }
        $this->triggerEvent($params);
        return true;
    }

    /**
     * 获取插入关系表的数据
     * @param $params
     * @return array
     */
    private function getInsertData($params)
    {
        $insert = [
            'saas_user_id' => $params['saas_id'],
            'channel_id' => $params['id'],
            'is_deleted' => ChannelConstant::SAAS_CHANNEL_DELETED_FALSE,
        ];
        return $insert;
    }

    private function triggerEvent($params)
    {
        event(new OperationLogEvent(101, json_encode($params)));
    }
}
