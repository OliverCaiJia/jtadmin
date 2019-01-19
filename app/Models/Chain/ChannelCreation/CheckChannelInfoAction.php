<?php

namespace App\Models\Chain\ChannelCreation;

use App\Models\Chain\AbstractHandler;
use App\Models\Factory\Saas\Channel\ChannelFactory;

class CheckChannelInfoAction extends AbstractHandler
{
    private $params = [];
    protected $error = ['error' => '插入合作方渠道对应关系表失败，请检查输入参数！', 'code' => 7452];

    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * 第一步:检查输入渠道信息是否合法
     *
     * @return array
     */
    public function handleRequest()
    {
        if ($this->checkChannelInfo($this->params)) {
            $this->setSuccessor(new InsertChannelsAction($this->params));
            return $this->getSuccessor()->handleRequest();
        } else {
            return $this->error;
        }
    }

    private function checkChannelInfo($params)
    {
        if (ChannelFactory::checkRepeatedNid($params['nid'])) {
            $this->error['error'] = '创建渠道失败，此渠道ID:' . $params['nid'] . '已经使用过';
            return false;
        }
        return true;
    }
}
