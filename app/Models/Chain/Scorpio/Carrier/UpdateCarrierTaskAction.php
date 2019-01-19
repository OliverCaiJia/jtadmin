<?php

namespace App\Models\Chain\Scorpio\Carrier;

use App\Models\Chain\AbstractHandler;
use App\MongoDB\Factory\Credit\ScorpioFactory;

class UpdateCarrierTaskAction extends AbstractHandler
{

    private $params = [];
    private $status = '';
    protected $error = ['error' => '更新运营商任务数据失败！', 'code' => 1004];

    public function __construct($params, $status)
    {
        $this->params = $params;
        $this->status = $status;
    }

    /**
     * 第2步:更新或者创建carrier task状态
     *
     * @return array
     */
    public function handleRequest()
    {
        if ($this->updateCarrier($this->status, $this->params)) {
            $this->setSuccessor(new UpdateReportTaskAction($this->params, $this->status));
            return $this->getSuccessor()->handleRequest();
        } else {
            return $this->error;
        }
    }

    /**
     * 更新或创建运营商数据
     *
     * @param       $status
     * @param array $params
     *
     * @return mixed
     */
    private function updateCarrier($status, $params = [])
    {
        return ScorpioFactory::updateOrCreateDatasourceTask($params, $status);
    }
}
