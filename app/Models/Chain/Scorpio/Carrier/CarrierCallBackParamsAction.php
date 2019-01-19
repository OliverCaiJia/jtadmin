<?php

namespace App\Models\Chain\Scorpio\Carrier;

use App\Models\Chain\AbstractHandler;
use App\Models\Factory\Saas\Channel\ChannelFactory;
use App\MongoDB\Factory\Credit\ScorpioFactory;
use App\Services\Core\Credit\Scorpio\ScorpioUtils;

class CarrierCallBackParamsAction extends AbstractHandler
{
    private $params = [];
    private $status = '';
    protected $error = ['error' => '参数传递不对！', 'code' => 1004];

    public function __construct($params, $status)
    {
        $this->params = $params;
        $this->status = $status;
    }

    /**
     * 第一步:检查回调参数
     *
     * @return array
     */
    public function handleRequest()
    {
        if ($this->carrierParams($this->status, $this->params)) {
            $this->setSuccessor(new UpdateCarrierTaskAction($this->params, $this->status));
            return $this->getSuccessor()->handleRequest();
        } else {
            return $this->error;
        }
    }

    /**
     * 过滤参数
     *
     * @param       $status
     * @param array $params
     *
     * @return bool
     */
    private function carrierParams($status, $params = [])
    {
        $channel = ChannelFactory::getInfoByHashId($params['channel_hash_id']);
        if (!$channel) {
            $this->error['error'] = '无效的渠道';
            return false;
        }

        //检测 task_id 是否存在
        if ($status != ScorpioUtils::SCORPIO_EVENT_SUBMIT_STATUS) {
            return ScorpioFactory::isValidTaskId($params['task_id']);
        }

        return true;
    }
}
