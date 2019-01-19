<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ScorpioResponseFactory;
use App\Models\Chain\Order\DoCreateOrderHandler;
use App\Models\Chain\Scorpio\Carrier\DoCarrierHandler;
use App\Models\Factory\Saas\Channel\ChannelFactory;
use App\Models\Factory\User\UserReportFactory;
use App\MongoDB\Filter\Scorpio\Credit\DoCreditReportHandler;
use App\Services\Core\Credit\Scorpio\ScorpioService;
use App\Services\Core\Credit\Scorpio\ScorpioUtils;
use App\Strategies\ScorpioStrategy;
use App\Strategies\UserReportStrategy;
use Illuminate\Http\Request;
use Log;

/**
 * Class ScorpioController
 *
 * @package App\Http\Controllers\Api
 */
class ScorpioController extends ApiController
{
    /**
     * 运营商回调
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function carrierTaskCallBack(Request $request)
    {
        $params = $request->all();

        $event = $request->header(ScorpioUtils::SCORPIO_BACK_HEADER_EVENT);
        $type = $request->header(ScorpioUtils::SCORPIO_BACK_HEADER_TYPE);
        $sign = $request->header(ScorpioUtils::SCORPIO_BACK_HEADER_SIGN);
        $payload = json_encode($params, JSON_UNESCAPED_UNICODE);

        //验证签名
        if (!isset($sign) || $sign != ScorpioUtils::signCallback($payload)) {
            Log::info('签名认证失败', ['params' => $params]);
            return ScorpioResponseFactory::error('10001', '签名认证失败，参数body不正确!');
        }

        //验证头部信息
        if (!isset($event) || !isset($type) || strtolower($type) != ScorpioUtils::SCORPIO_CARRIER_TYPE) {
            return ScorpioResponseFactory::error('10004', 'header缺少!');
        }

        //检查自定义参数 user_id 是否合法和有效
        $result = ScorpioStrategy::isValidUserId($params['user_id']);
        if ($result !== true) {
            Log::info('user_id参数不合法', ['params' => $params]);
            return ScorpioResponseFactory::error('10005', $result);
        }

        //解析自定义参数 user_id
        $params = ScorpioStrategy::dealWithUserId($params);

        //判断任务是否完成
        $finished = ScorpioStrategy::isTaskStepOver($params);
        if ($finished) {
            return ScorpioResponseFactory::error('10006', '任务已经接收完毕!');
        }

        $event = strtolower($event);
        $status = array_flip(ScorpioUtils::SCORPIO_EVENT_MAP)[$event];

        //判断事件是否合法
        if (!in_array($event, ScorpioUtils::SCORPIO_EVENT_MAP)) {
            return ScorpioResponseFactory::error('10002', '事件名称不存在!');
        }

        //更新任务状态
        $ch = new DoCarrierHandler($params, $status);
        $result = $ch->handleRequest();
        if (isset($result['error'])) {
            return ScorpioResponseFactory::error($result['code'], $result['error']);
        }

        //创建订单
        if ($event == ScorpioUtils::SCORPIO_EVENT_CREATE_ALERT) {
            if ($params['result'] == false) {
                Log::info('创建订单失败', ['params' => $params, 'message' => $params['message']]);
                return ScorpioResponseFactory::error('100013', $params['message']);
            }

            $params['channel'] = ChannelFactory::getInfoByHashId($params['channel_hash_id']);
            $params['idcard'] = UserReportFactory::getById($params['report_id'])->id_card;
            $order = new DoCreateOrderHandler($params);
            $orderResult = $order->handleRequest();
            if (isset($orderResult['error'])) {
                Log::info('创建订单失败', ['params' => $params, 'result' => $orderResult]);
                return ScorpioResponseFactory::error('100013', $orderResult);
            }
        }

        //报告通知
        if (($event == ScorpioUtils::SCORPIO_EVENT_REPORT_ALERT)) {
            //获取运营商数据
            $contact = UserReportStrategy::generateContact($params['report_id']);
            $query = [
                'name' => $params['name'],
                'idcard' => $params['idcard'],
                'task_id' => $params['task_id'],
            ];
            $query = $contact ? array_merge($query, ['contact' => $contact]) : $query;
            $reportData = ScorpioService::getCarrierUserReport($params['mobile'], $query);

            //更新报告+更新状态，统一处理
            $res = new DoCreditReportHandler($reportData, $params);
            $reportResult = $res->handleRequest();
            if (isset($reportResult['error'])) {
                Log::info('报告入库失败', ['query' => $query, 'result' => $reportResult]);
                return ScorpioResponseFactory::error('100013', $reportResult);
            }
        }

        return ScorpioResponseFactory::ok('200', '回调成功!');
    }
}
