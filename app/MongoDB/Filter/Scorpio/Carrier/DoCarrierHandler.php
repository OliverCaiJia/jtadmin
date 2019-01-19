<?php

namespace App\MongoDB\Filter\Scorpio\Carrier;

use App\MongoDB\Filter\AbstractHandler;
use App\MongoDB\Filter\Scorpio\CreateCarrierMysqlAction;
use DB;
use App\Helpers\Logger\SLogger;
use Log;

/**
 *  运营商过滤
 */
class DoCarrierHandler extends AbstractHandler
{
    private $params = array();
    private $mark = '';
    private $type;

    public function __construct($params, $type)
    {
        $this->params = $params;
        $this->type = $type;
        $this->setSuccessor($this);
    }

    /**
     * 1.将获取的运营商数据，存入mongodb
     * 2.根据业务需求，将数据存入mysql
     *
     * @return array
     * @throws \Exception
     */
    public function handleRequest()
    {
        $result = ['error' => '运营商数据过滤失败!', 'code' => 10001];

        DB::beginTransaction();
        try {
            $this->setSuccessor(new CreateCarrierMysqlAction($this->params, $this->mark));
            $result = $this->getSuccessor()->handleRequest();
            if (isset($result['error'])) {
                DB::rollback();

                SLogger::getStream()->error('运营商过滤失败-try');
                SLogger::getStream()->error($result['error']);
            } else {
                DB::commit();
            }
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error($e);
            SLogger::getStream()->error('运营商过滤失败-catch');
            SLogger::getStream()->error($e->getMessage());
        }
        return $result;
    }
}
