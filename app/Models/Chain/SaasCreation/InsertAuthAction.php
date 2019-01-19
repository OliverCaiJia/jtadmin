<?php


namespace App\Models\Chain\SaasCreation;

use App\Models\Chain\AbstractHandler;
use App\Models\Factory\Saas\User\UserFactory;

class InsertAuthAction extends AbstractHandler
{
    private $params = [];
    protected $error = ['error' => '创建合作方出错,插入saas_auth表失败', 'code' => 7251];

    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * 第一步:插入saas_auth表
     *
     * @return array
     */
    public function handleRequest()
    {
        if ($this->insertAuthRecord($this->params)) {
            $this->setSuccessor(new InsertSaasPersonAction($this->params));
            return $this->getSuccessor()->handleRequest();
        } else {
            return $this->error;
        }
    }

    private function insertAuthRecord($params)
    {
        $res = UserFactory::insertAuthRecord($params);
        return $res;
    }
}
