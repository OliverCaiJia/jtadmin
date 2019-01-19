<?php

namespace App\MongoDB\Filter\Scorpio\Credit;

use App\MongoDB\Filter\AbstractHandler;
use DB;
use App\Helpers\Logger\SLogger;
use Log;

class DoCreditReportHandler extends AbstractHandler
{
    private $reportData;
    private $params = [];

    public function __construct($reportData, $params)
    {
        $this->reportData = $reportData;
        $this->params = $params;

        $this->setSuccessor($this);
    }

    /**
     * 1.更新report表
     *
     * @return array
     * @throws \Exception
     */
    public function handleRequest()
    {
        $result = ['error' => '魔蝎入库失败!', 'code' => 10001];
        DB::beginTransaction();
        try {
            $this->setSuccessor(new UpdateUserReportAction($this->reportData, $this->params));
            $result = $this->getSuccessor()->handleRequest();
            if (isset($result['error'])) {
                DB::rollback();

                SLogger::getStream()->error('魔蝎过滤失败-try');
                SLogger::getStream()->error($result['error']);
            } else {
                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            SLogger::getStream()->error('魔蝎过滤失败-catch');
            SLogger::getStream()->error($e->getMessage());
        }
        return $result;
    }
}
