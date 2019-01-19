<?php

namespace App\Models\Chain\Scorpio\Carrier;

use App\Helpers\Logger\SLogger;
use App\Models\Chain\AbstractHandler;
use DB;
use Log;

/**
 * 魔蝎异步通知,状态更新
 */
class DoCarrierHandler extends AbstractHandler
{
    private $params = [];
    private $status = '';

    public function __construct($params, $status)
    {
        $this->params = $params;
        $this->status = $status;
        $this->setSuccessor($this);
    }

    /**
     * 思路：
     * 1.检查回调参数
     * 2.更新或创建task任务, 数据表user_datasource_task
     * 3.更新 user_report_task 表数据
     */
    public function handleRequest()
    {
        $result = ['error' => '同步失败', 'code' => 1000];

        DB::beginTransaction();
        try {
            $this->setSuccessor(new CarrierCallBackParamsAction($this->params, $this->status));
            $result = $this->getSuccessor()->handleRequest();
            if (isset($result['error'])) {
                DB::rollback();

                SLogger::getStream()->error('报告状态, 事务异常-try');
                SLogger::getStream()->error($result['error']);
            } else {
                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            SLogger::getStream()->error('报告状态, 事务异常-catch');
            SLogger::getStream()->error($e->getMessage());
        }

        return $result;
    }
}
