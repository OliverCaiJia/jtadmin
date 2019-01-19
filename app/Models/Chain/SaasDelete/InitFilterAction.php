<?php

namespace App\Models\Chain\SaasDelete;

use App\Events\OperationLogEvent;
use App\Models\Chain\AbstractHandler;
use App\Models\Factory\Saas\Filter\FilterFactory;

class InitFilterAction extends AbstractHandler
{
    private $params = [];
    protected $error = ['error' => '删除合作方出错,初始化合作方审查条件失败！', 'code' => 7353];

    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * 第三步:初始化合作方审查条件
     *
     * @return array
     */
    public function handleRequest()
    {
        if ($this->initFilter($this->params)) {
            return true;
        } else {
            return $this->error;
        }
    }

    private function initFilter($params)
    {
        $res = FilterFactory::deleteFilter($params['id']);
        $this->triggerEvent($params);
        return $res;
    }

    private function triggerEvent($params)
    {
        event(new OperationLogEvent(2, $this->params['origin']));
    }
}
