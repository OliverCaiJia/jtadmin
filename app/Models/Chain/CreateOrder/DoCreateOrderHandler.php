<?php

namespace App\Models\Chain\Order;

use App\Models\Chain\AbstractHandler;
use App\Helpers\Logger\SLogger;
use DB;
use Log;

class DoCreateOrderHandler extends AbstractHandler
{
    private $params = array();

    public function __construct($params)
    {
        $this->params = $params;
        $this->setSuccessor($this);
    }

    /**
     * 思路：
     * 1.检查重复申请
     * 2.创建订单
     * 3.分配订单
     */

    /**
     * 入口
     *
     * @return array
     * @throws \Exception
     */
    public function handleRequest()
    {
        $result = ['error' => '订单创建出错啦', 'code' => 7150];

        DB::beginTransaction();
        try {
            $this->setSuccessor(new CheckRepeatedApplyAction($this->params));
            $result = $this->getSuccessor()->handleRequest();
            if (isset($result['error'])) {
                DB::rollback();
                SLogger::getStream()->error(', 事务异常-try');
                SLogger::getStream()->error($result['error']);
            } else {
                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e);
            SLogger::getStream()->error('分配合作方, 事务异常-catch');
            SLogger::getStream()->error($e->getMessage());
        }

        return $result;
    }
}
