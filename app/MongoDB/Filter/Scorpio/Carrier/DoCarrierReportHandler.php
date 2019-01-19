<?php

namespace App\MongoDB\Filter\Scorpio\Carrier;

use App\MongoDB\Filter\AbstractHandler;
use App\MongoDB\Filter\Scorpio\CreateCarrierMongoAction;
use DB;
use App\Helpers\Logger\SLogger;

class DoCarrierReportHandler extends AbstractHandler
{
    private $params = array();
    private $mark = '';
    private $label = '';

    public function __construct($params, $mark, $label)
    {
        $this->params = $params;
        $this->mark = $mark;
        $this->label = $label;
        $this->setSuccessor($this);
    }

    /**
     * 1.将获取魔杖数据，存入mongodb
     * 2.根据业务需求，将数据存入mysql
     * 3.存入report表中
     * fail
     * 存数据失败
     *
     * @return array
     * @throws \Exception
     */
    public function handleRequest()
    {
        $result = ['error' => '运营商数据过滤失败!', 'code' => 10001];

        DB::beginTransaction();
        try {
            $this->setSuccessor(new CreateCarrierMongoAction($this->params, $this->mark, $this->label));
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

            SLogger::getStream()->error('运营商过滤失败-catch');
            SLogger::getStream()->error($e->getMessage());
        }
        return $result;
    }
}
