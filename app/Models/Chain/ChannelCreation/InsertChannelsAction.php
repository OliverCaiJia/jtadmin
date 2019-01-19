<?php

namespace App\Models\Chain\ChannelCreation;

use App\Constants\ChannelConstant;
use App\Models\Chain\AbstractHandler;
use App\Models\Factory\Saas\Channel\ChannelFactory;
use App\Strategies\ChannelStrategy;
use Illuminate\Support\Facades\Auth;

class InsertChannelsAction extends AbstractHandler
{
    private $params = [];
    protected $error = ['error' => '插入渠道表失败！', 'code' => 7451];

    public function __construct($params)
    {
        $this->params = $params;
    }

    /**
     * 第二步:插入渠道表
     *
     * @return array
     */
    public function handleRequest()
    {
        if ($this->insertChannels($this->params)) {
            $this->setSuccessor(new InsertHashIdAction($this->params));
            return $this->getSuccessor()->handleRequest();
        } else {
            return $this->error;
        }
    }

    /**
     * 渠道表插入数据
     *
     * @param $params
     *
     * @return bool
     */
    private function insertChannels($params)
    {
        $insert = $this->getInsertData($params);
        return ChannelFactory::insertChannel($insert);
    }

    /**
     * 获取带插入的数据
     * @param $params
     * @return array
     */
    private function getInsertData($params)
    {
        //数组处理
        $insert = [
            'nid' => $params['nid'],
            'is_deleted' => ChannelConstant::SAAS_CHANNEL_DELETED_FALSE,
            'name' => $params['name'],
            'type' => $params['type'],
            'product_name' => $params['product_name'],
            'create_id' => Auth::user()->id,
            'picture' => $params['picture'],
            'promotion_cost' => $params['promotion_cost'],
            'borrowing_balance' => $params['borrowing_balance'],
            'repayment_method' => $params['repayment_method'],
            'cycle' => $params['cycle'],
        ];

        if (!empty($params['contact_person'])) {
            $insert['contact_person'] = $params['contact_person'];
        }

        if (!empty($params['contact_mobile'])) {
            $insert['contact_mobile'] = $params['contact_mobile'];
        }

        return $insert;
    }
}
