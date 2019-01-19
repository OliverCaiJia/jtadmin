<?php

namespace App\Models\Chain\SaasCreation;

use App\Models\Chain\AbstractHandler;
use App\Models\Factory\Saas\User\UserFactory;

class InsertSaasPersonAction extends AbstractHandler
{
    private $params = [];
    protected $error = ['error' => '创建合作方出错,插入saas_person表失败', 'code' => 7252];

    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * 第二步:插入saas_person表
     *
     * @return array
     */
    public function handleRequest()
    {
        if ($this->insertPersonRecord($this->params)) {
            $this->setSuccessor(new InitFilterAction($this->params));
            return $this->getSuccessor()->handleRequest();
        } else {
            return $this->error;
        }
    }

    private function insertPersonRecord($params)
    {
        $res = UserFactory::insertPersonRecord($params, 1);
        return $res;
    }
}
