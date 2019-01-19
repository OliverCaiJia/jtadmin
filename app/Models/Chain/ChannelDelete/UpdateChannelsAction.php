<?php

namespace App\Models\Chain\ChannelDelete;

use App\Models\Chain\AbstractHandler;
use App\Models\Factory\Saas\Channel\ChannelFactory;

class UpdateChannelsAction extends AbstractHandler
{
    private $params = [];
    protected $error = ['error' => '删除合作方渠道对应关系表失败！', 'code' => 7551];

    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * 第一步:更新渠道表
     *
     * @return array
     */
    public function handleRequest()
    {
        if ($this->updateChannels($this->params)) {
            $this->setSuccessor(new UpdateChannelSaasAction($this->params));
            return $this->getSuccessor()->handleRequest();
        } else {
            return $this->error;
        }
    }

    /**
     * 更新渠道表
     *
     * @param $params
     *
     * @return bool
     */
    private function updateChannels($params)
    {
        $res = ChannelFactory::deleteChannelSaas($params['id']);
        return $res;
    }
}
