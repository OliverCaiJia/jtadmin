<?php

namespace App\Models\Chain\Order;

use App\Models\Chain\AbstractHandler;
use App\Helpers\Logger\SLogger;
use DB;

/**
 * 充值
 */
class DoAssignHandler extends AbstractHandler
{
    #外部传参

    private $params = array();

    public function __construct($params)
    {
        $this->params = $params;
        $this->setSuccessor($this);
    }

    /**
     * 思路：
     * 1.检查订单状态是否为待处理
     * 2.记录消费流水saas_account_log
     * 3.更新用户账户信息saas_account
     * 4.保存订单中合作方ID
     * 5.更新操作人ID
     * 6.更新jt_saas_authes的remaining_order_num字段
     */

    /**
     * 入口
     *
     * @return array
     * @throws \Exception
     */
    public function handleRequest()
    {
        $result = ['error' => '分配出错啦'. $this->params['order_id'], 'code' => 7150];

        DB::beginTransaction();
        try {
            $this->setSuccessor(new CheckStatusAction($this->params));
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
            SLogger::getStream()->error('分配合作方, 事务异常-catch');
            SLogger::getStream()->error($e->getMessage());
        }

        return $result;
    }
}
