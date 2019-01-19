<?php

namespace App\MongoDB\Filter\Scorpio\Credit;

use App\Models\Factory\User\UserReportFactory;
use App\MongoDB\Factory\Credit\CarrierFactory;
use App\MongoDB\Factory\Credit\ScorpioFactory;
use App\MongoDB\Filter\AbstractHandler;

class UpdateUserReportAction extends AbstractHandler
{
    protected $error = ['error' => '存入报告数据失败！', 'code' => 1004];
    private $reportData = [];
    private $params = [];

    public function __construct($reportData, $params)
    {
        $this->reportData = $reportData;
        $this->params = $params;
    }


    /**
     * 第一步:报告数据入库
     *
     * @return array|bool
     */
    public function handleRequest()
    {
        if ($this->createReport($this->params, $this->reportData)) {
            return true;
        } else {
            return $this->error;
        }
    }

    /**
     * 报告入库
     *
     * @param $params
     * @param $reportData
     *
     * @return bool
     */
    private function createReport($params, $reportData)
    {
        $carrierTaskId = ScorpioFactory::getCarrierId($params['task_id']);
        $reportTaskInfo = ScorpioFactory::getReportTaskInfo($params['user_id'], $carrierTaskId);

        return UserReportFactory::update($reportTaskInfo, $params, $reportData);
    }
}
