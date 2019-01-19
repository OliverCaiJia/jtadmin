<?php

namespace App\Models\Chain\ChannelCreation;

use App\Models\Chain\AbstractHandler;
use App\Models\Factory\Saas\Channel\ChannelFactory;
use App\Strategies\ChannelStrategy;

class InsertHashIdAction extends AbstractHandler
{
    private $params = [];
    protected $error = ['error' => '生成渠道失败，插入hash_id失败！', 'code' => 7451];

    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * 第三步:插入hash_id
     *
     * @return array
     */
    public function handleRequest()
    {
        if ($this->insertHashId($this->params)) {
            $this->setSuccessor(new InsertUrlAction($this->params));
            return $this->getSuccessor()->handleRequest();
        } else {
            return $this->error;
        }
    }

    /**
     * 渠道表插入数据
     *
     * @param $params
     *
     * @return bool
     */
    private function insertHashId($params)
    {
        $channelPrimaryKey = ChannelFactory::getChannelPriKeyIdByDspId($params['nid']);
        $hashId = ChannelStrategy::generateHashId($channelPrimaryKey);
        if (ChannelFactory::checkRepeatedHashId($hashId)) {
            $this->error['error'] = '创建渠道失败，hash_id重复。';
            return false;
        }

        $this->params['hash_id'] = $hashId;
        $this->params['id'] = $channelPrimaryKey;
        return ChannelFactory::updateChannelFieldById($channelPrimaryKey, ['hash_id' => $hashId]);
    }
}
