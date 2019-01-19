<?php

namespace App\Models\Chain\SaasCreation;

use App\Models\Chain\AbstractHandler;
use App\Helpers\Logger\SLogger;
use DB;

/**
 * 创建合作方责任链
 * Class DoCreationHandler
 * @package App\Models\Chain\SaasCreation
 */
class DoCreationHandler extends AbstractHandler
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
     * 1.插入saas_auth表
     * 2.插入saas_person表
     * 3.初始化合作方审查条件
     */

    /**
     * 入口
     *
     * @return array
     * @throws \Exception
     */
    public function handleRequest()
    {
        $result = ['error' => '创建合作方出错,传入信息不合法', 'code' => 7250];

        DB::beginTransaction();
        try {
            $this->setSuccessor(new InsertAuthAction($this->params));
            $result = $this->getSuccessor()->handleRequest();
            if (isset($result['error'])) {
                DB::rollback();
                SLogger::getStream()->error('创建合作方, 事务异常-try');
                SLogger::getStream()->error($result['error']);
            } else {
                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            SLogger::getStream()->error('创建合作方, 事务异常-catch');
            SLogger::getStream()->error($e->getMessage());
        }

        return $result;
    }
}
