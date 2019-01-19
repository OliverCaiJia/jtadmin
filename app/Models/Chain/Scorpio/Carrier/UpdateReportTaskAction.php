<?php

namespace App\Models\Chain\Scorpio\Carrier;

use App\Models\Chain\AbstractHandler;
use App\Models\Factory\PaymentFactory;
use App\Strategies\ScorpioStrategy;

class UpdateReportTaskAction extends AbstractHandler
{

    private $params = [];
    private $status = '';
    protected $error = ['error' => '更新报告任务失败！', 'code' => 1003];

    public function __construct($params, $status)
    {
        $this->params = $params;
        $this->status = $status;
    }

    /**
     * 第三步:同步 user_report_task 表中的数据
     *
     * @return array
     */
    public function handleRequest()
    {
        if ($this->updateReport($this->status, $this->params)) {
            return true;
        } else {
            return $this->error;
        }
    }

    /**
     * 更新报告任务数据
     *
     * @param       $status
     * @param array $params
     *
     * @return mixed|string
     */
    private function updateReport($status, $params = [])
    {
        return ScorpioStrategy::getScorpioAsynStatus($params, $status);
    }
}
