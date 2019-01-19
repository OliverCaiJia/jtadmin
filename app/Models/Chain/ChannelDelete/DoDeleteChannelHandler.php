<?php

namespace App\Models\Chain\ChannelDelete;

use App\Models\Chain\AbstractHandler;
use App\Helpers\Logger\SLogger;
use DB;

/**
 * 渠道删除责任链
 * Class DoDeleteChannelHandler
 *
 * @package App\Models\Chain\ChannelDelete
 */
class DoDeleteChannelHandler extends AbstractHandler
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
     * 1.更新渠道表
     * 2.更新合作方渠道对应关系表
     */

    /**
     * 入口
     *
     * @return array
     * @throws \Exception
     */
    public function handleRequest()
    {
        $result = ['error' => '删除渠道出错啦', 'code' => 7550];
        DB::beginTransaction();
        try {
            $this->setSuccessor(new UpdateChannelsAction($this->params));
            $result = $this->getSuccessor()->handleRequest();
            if (isset($result['error'])) {
                DB::rollback();
                SLogger::getStream()->error('删除渠道, 事务异常-try');
                SLogger::getStream()->error($result['error']);
            } else {
                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollBack();
            SLogger::getStream()->error('删除渠道, 事务异常-catch');
            SLogger::getStream()->error($e->getMessage());
        }

        return $result;
    }
}
