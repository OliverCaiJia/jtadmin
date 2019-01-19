<?php

namespace App\Models\Chain\SaasDelete;

use App\Models\Chain\AbstractHandler;
use App\Helpers\Logger\SLogger;
use DB;

/**
 * 删除合作方责任链
 * Class DoDeleteHandler
 * @package App\Models\Chain\SaasDelete
 */
class DoDeleteHandler extends AbstractHandler
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
     * 1.更新saas_auth表
     * 2.更新saas_person表
     */

    /**
     * 入口
     *
     * @return array
     * @throws \Exception
     */
    public function handleRequest()
    {
        $result = ['error' => '删除合作方出错', 'code' => 7350];

        DB::beginTransaction();
        try {
            $this->setSuccessor(new UpdateSaasAuthAction($this->params));
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
            SLogger::getStream()->error('删除合作方, 事务异常-catch');
            SLogger::getStream()->error($e->getMessage());
        }

        return $result;
    }
}
