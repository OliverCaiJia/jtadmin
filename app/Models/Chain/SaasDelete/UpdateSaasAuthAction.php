<?php

namespace App\Models\Chain\SaasDelete;

use App\Models\Chain\AbstractHandler;
use App\Models\Factory\Saas\User\UserFactory;

class UpdateSaasAuthAction extends AbstractHandler
{
    private $params = [];
    protected $error = ['error' => '删除合作方出错,更新saas_auth表失败', 'code' => 7351];

    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * 第一步:更新saas_auth表
     *
     * @return array
     */
    public function handleRequest()
    {
        if ($this->updateAuthRecord($this->params)) {
            $this->setSuccessor(new UpdateSaasPersonAction($this->params));
            return $this->getSuccessor()->handleRequest();
        } else {
            return $this->error;
        }
    }

    private function updateAuthRecord($params)
    {
        // 记录原有信息
        $origin = UserFactory::getUserInfoById($params['id']);
        $this->params['origin'] = json_encode($origin);
        return UserFactory::deleteSaasAuth($params['id']);
    }
}
