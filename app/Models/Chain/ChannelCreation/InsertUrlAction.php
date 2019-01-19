<?php

namespace App\Models\Chain\ChannelCreation;

use App\Models\Chain\AbstractHandler;
use App\Strategies\ChannelStrategy;
use App\Models\Factory\Saas\Channel\ChannelFactory;

class InsertUrlAction extends AbstractHandler
{
    private $params = [];
    protected $error = ['error' => '生成渠道失败，插入URL失败！', 'code' => 7451];

    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * 第四步:插入URL
     *
     * @return array
     */
    public function handleRequest()
    {
        if ($this->insertUrl($this->params)) {
            $this->setSuccessor(new InsertChannelSaasAction($this->params));
            return $this->getSuccessor()->handleRequest();
        } else {
            return $this->error;
        }
    }

    private function insertUrl($params)
    {
        $url = ChannelStrategy::generateUrl($params['hash_id']);
        return ChannelFactory::updateChannelFieldById($params['id'], ['url' => $url]);
    }
}
