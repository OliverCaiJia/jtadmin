<?php

namespace App\Models\Chain\ChannelCreation;

use App\Models\Chain\AbstractHandler;
use App\Helpers\Logger\SLogger;
use DB;

/**
 * 渠道创建责任链
 * Class DoCreateChannelHandler
 * @package App\Models\Chain\ChannelCreation
 */
class DoCreateChannelHandler extends AbstractHandler
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
     * 1.检查输入渠道信息是否合法
     * 2.插入渠道表
     * 3.插入hash_id
     * 4.插入URL
     * 5.插入合作方渠道对应关系表
     */

    /**
     * 入口
     *
     * @return array
     * @throws \Exception
     */
    public function handleRequest()
    {
        $result = ['error' => '新增渠道出错啦', 'code' => 7450];
        DB::beginTransaction();
        try {
            $this->setSuccessor(new CheckChannelInfoAction($this->params));
            $result = $this->getSuccessor()->handleRequest();
            if (isset($result['error'])) {
                DB::rollback();
                SLogger::getStream()->error('新增渠道, 事务异常-try');
                SLogger::getStream()->error($result['error']);
            } else {
                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            SLogger::getStream()->error('新增渠道, 事务异常-catch');
            SLogger::getStream()->error($e->getMessage());
        }

        return $result;
    }
}
