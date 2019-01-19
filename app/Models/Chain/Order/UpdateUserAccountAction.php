<?php

namespace App\Models\Chain\Order;

use App\Helpers\Utils;
use App\Models\Chain\AbstractHandler;
use App\Models\Factory\Saas\User\UserFactory;
use App\Models\Orm\SaasAccount;
use Carbon\Carbon;

class UpdateUserAccountAction extends AbstractHandler
{
    private $params = [];
    protected $error = ['error' => '更新Saas用户账户信息失败！', 'code' => 7153];

    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * 第三步:更新用户账户信息
     *
     * @return array
     */
    public function handleRequest()
    {
        if ($this->updateAccount($this->params)) {
            $this->setSuccessor(new UpdateSaasAuthIdAction($this->params));
            return $this->getSuccessor()->handleRequest();
        } else {
            return $this->error;
        }
    }

    /**
     * 修改用户账户信息
     * 2、账号出去钱（money）
     * (1)frost_cash +=money;
     * 或frost_other +=money;
     * frost = frost_cash + frost_other;
     * (2)balance_cash -=money;
     * balance = balance_cash + balance_frost;
     * （3）expend += money;
     * total = income - expend;
     *
     * total，balance，frost 不直接操作，通过公式计算得出
     *
     * @param $params
     *
     * @return bool
     */
    private function updateAccount($params)
    {
        $userId = $params['saas_user_id'];
        $saasInfo = UserFactory::getUserInfoById($userId);
        $expend = $saasInfo['order_price'];

        //锁行
        $userAccountObj = SaasAccount::where('user_id', $userId)->lockForUpdate()->first();
        //修改
        $userAccountObj->user_id = $userId;
        $userAccountObj->expend += $expend;
        $userAccountObj->balance_cash -= $expend;
        $userAccountObj->balance = $userAccountObj->balance_frost + $userAccountObj->balance_cash;
        $userAccountObj->total = $userAccountObj->income - $userAccountObj->expend;
        $userAccountObj->updated_at = Carbon::now();
        $userAccountObj->updated_ip = Utils::ipAddress();

        return $userAccountObj->save();
    }
}
