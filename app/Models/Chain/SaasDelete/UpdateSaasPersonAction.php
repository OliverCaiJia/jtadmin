<?php

namespace App\Models\Chain\SaasDelete;

use App\Models\Chain\AbstractHandler;
use App\Models\Factory\Saas\User\UserFactory;

class UpdateSaasPersonAction extends AbstractHandler
{
    private $params = [];
    protected $error = ['error' => '删除合作方出错,更新saas_person表失败', 'code' => 7352];

    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * 第二步:更新saas_person表
     *
     * @return bool
     */
    public function handleRequest()
    {
        if ($this->updateAuthRecord($this->params)) {
            $this->setSuccessor(new InitFilterAction($this->params));
            return $this->getSuccessor()->handleRequest();
        } else {
            return $this->error;
        }
    }

    private function updateAuthRecord($params)
    {
        return UserFactory::deleteSaasPerson($params['id']);
    }
}
