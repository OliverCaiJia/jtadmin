<?php

namespace App\MongoDB\Filter\Scorpio;

use App\MongoDB\Filter\AbstractHandler;

class CreateCarrierMysqlAction extends AbstractHandler
{
    protected $error = array('error' => '操作mysql错误！', 'code' => 1003);
    private $params = array();
    private $mark = '';

    public function __construct($params, $mark)
    {
        $this->params = $params;
        $this->mark = $mark;
    }

    /**
     * 第三步:mysql记录
     *
     * @return array|bool
     */
    public function handleRequest()
    {
        if ($this->createCreditLog($this->params, $this->mark)) {
            return true;
        } else {
            return $this->error;
        }
    }

    /**
     * @param        $params
     * @param string $mark 标识（咱们自己的平台）
     *
     * @return bool
     */
    private function createCreditLog($params, $mark)
    {


        return true;
    }
}
