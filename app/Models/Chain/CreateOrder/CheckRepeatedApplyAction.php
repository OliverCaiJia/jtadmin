<?php

namespace App\Models\Chain\Order;

use App\Constants\ChannelConstant;
use App\Constants\UserOrderConstant;
use App\Models\Chain\AbstractHandler;
use App\Models\Factory\Admin\Order\OrderFactory;
use App\Models\Factory\Saas\Channel\ChannelFactory;
use App\Models\Factory\Saas\User\UserAccountFactory;
use App\Models\Factory\Saas\User\UserFactory;
use App\Models\Factory\User\UserReportFactory;
use App\Models\Orm\SaasChannelSaas;
use App\Strategies\SaasAuthStrategy;

class CheckRepeatedApplyAction extends AbstractHandler
{
    private $params = [];
    protected $error = ['error' => '重复申请订单！', 'code' => 7152];

    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * 第一步:检查重复申请
     *
     * @return array
     */
    public function handleRequest()
    {
        if ($this->check($this->params)) {
            $this->setSuccessor(new CreateOrderAction($this->params));
            return $this->getSuccessor()->handleRequest();
        } else {
            return $this->error;
        }
    }

    /**
     * 检查重复申请
     *
     * @param $params
     *
     * @return bool
     */
    private function check($params)
    {
        $channel = ChannelFactory::getInfoByHashId($params['channel_hash_id']);

        if (!$channel) {
            $this->error['error'] = '无效的渠道' . $params['channel_hash_id'];
            return false;
        }

        //是否为重复申请订单, 条件：身份证号和渠道id和订单状态
        $reportIds = UserReportFactory::getIdsByIdCard($params['idcard']);
        $order = OrderFactory::getByWhereAndReportIds([
            ['channel_id', $channel->id],
            ['status', '!=', UserOrderConstant::PASSED]
        ], $reportIds);

        if ($channel->type == ChannelConstant::SAAS_CHANNEL_TYPE_DIRECTED) {
            //检查和作方是否有效
            $saasUserId = SaasChannelSaas::where('channel_id', $channel->id)->first()->saas_user_id;
            $this->params['saas_user_id'] = $saasUserId;
            //账户余额要可用
            $user = UserFactory::getById($saasUserId);
            $balance = UserAccountFactory::getBalanceById($saasUserId);
            if (!$user || $balance <= 0) {
                $this->error['error'] = '无效的合作方' . $saasUserId;
                return false;
            }

            //检查过滤条件：xx天不能重复申请
            if ($order) {
                $check = SaasAuthStrategy::checkRepeatedApply($user->id, $order->created_at);
                if (!$check) {
                    $this->error['error'] = '不能重复申请' . $params['channel_hash_id'];
                    return false;
                }
            }
        }

        return !$order ? true : false;
    }
}
