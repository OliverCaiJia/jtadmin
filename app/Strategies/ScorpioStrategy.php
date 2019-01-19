<?php

namespace App\Strategies;

use App\Models\Factory\Saas\Channel\ChannelFactory;
use App\Models\Factory\User\UserFactory;
use App\Models\Factory\User\UserReportFactory;
use App\MongoDB\Factory\Credit\ScorpioFactory;
use App\Services\Core\Credit\Scorpio\ScorpioUtils;
use Carbon\Carbon;

/**
 *
 * @package App\Strategies
 */
class ScorpioStrategy extends AppStrategy
{

    /**
     * 判断任务是否report
     *
     * @param $params
     *
     * @return bool
     */
    public static function isTaskStepOver($params)
    {
        $data = ScorpioFactory::getDatasourceTask($params);

        return ($data && $data->status == ScorpioUtils::SCORPIO_EVENT_REPORT_STATUS) ? true : false;
    }

    /**
     * 处理魔蝎异步返回参数user_id, 包含内容: user_id,hash_id,report_id
     *
     * @param $params
     *
     * @return mixed
     */
    public static function dealWithUserId($params)
    {
        $dealUserId = explode(',', $params['user_id']);
        $params['user_id'] = $dealUserId[0];
        $params['channel_hash_id'] = $dealUserId[1];
        $params['report_id'] = $dealUserId[2];

        return $params;
    }

    /**
     * 检查 user_id 参数值是否合法和有效, 包含内容: user_id,hash_id,report_id
     *
     * @param $userId
     *
     * @return bool|string
     */
    public static function isValidUserId($userId)
    {
        if (substr_count($userId, ',') != 2) {
            return '参数user_id不合法!';
        }

        $dealUserId = explode(',', $userId);
        $user = UserFactory::getUserInfoById($dealUserId[0]);
        if (!$user) {
            return '无效的用户';
        }

        $report = UserReportFactory::getById($dealUserId[2]);
        if (!$report) {
            return '无效的报告';
        }

        return true;
    }

    /**
     * 获取回调状态不同的处理情况
     *
     * @param array $params 返回数组
     * @param int   $status 回调状态值
     *
     * @return mixed|string
     */
    public static function getScorpioAsynStatus($params, $status = 0)
    {
        switch ($status) {
            case ScorpioUtils::SCORPIO_EVENT_SUBMIT_STATUS: // 创建状态
                $res = ScorpioFactory::updateReportTaskStatus($params, ScorpioFactory::REPORT_TASK_STEP_START);
                break;
            case ScorpioUtils::SCORPIO_EVENT_LOGIN_STATUS:  //登录状态
                $res = ScorpioFactory::updateReportTaskStatus($params, ScorpioFactory::REPORT_TASK_STEP_CARRIER);
                break;
            case ScorpioUtils::SCORPIO_EVENT_FAIL_STATUS:  //采集中状态
                $res = ScorpioFactory::updateReportTaskStatus($params, ScorpioFactory::REPORT_TASK_STEP_PROCESS);
                break;
            case ScorpioUtils::SCORPIO_EVENT_REPORT_STATUS:  //报告完成状态
                $res = ScorpioFactory::updateReportTaskStatus($params, ScorpioFactory::REPORT_TASK_STEP_OVER);
                break;
            default: //运营商完毕
                $res = ScorpioFactory::updateReportTaskStatus($params, ScorpioFactory::REPORT_TASK_STEP_CARRIER);
        }

        return $res ?? '';
    }

    /**
     * 获取时间设置
     *
     * @param int $step
     *
     * @return mixed
     */
    public static function getReportStepTimeRange($step = 0)
    {
        $day = 3; //报告有效期为一天
        $data['start_time'] = Carbon::now();
        switch ($step) {
            case ScorpioFactory::REPORT_TASK_STEP_START: //任务开始
                $data['end_time'] = Carbon::now()->addYears(100);
                break;
            case ScorpioFactory::REPORT_TASK_STEP_CARRIER: //运营商完毕
                $data['end_time'] = Carbon::now()->addDays($day);
                break;
            case ScorpioFactory::REPORT_TASK_STEP_PROCESS: //处理中
                $data['end_time'] = Carbon::now()->addDays($day);
                break;
            case ScorpioFactory::REPORT_TASK_STEP_OVER: //报告完毕
                $data['end_time'] = Carbon::now()->addDays($day);
                break;
            default:
                $data['end_time'] = Carbon::now()->addYears(100);
        }

        return $data;
    }

    /**
     * 对存入mongoDB的数据进行封装
     *
     * @param string|array $label 标签
     * @param string|array $mark  标识（跟MySQL有关联的数据）=> user_id或手机号
     * @param array        $data  接口返回的数据
     *
     * @return array
     */
    public static function dataStruct($mark, $label, $data = [])
    {
        return [
            'time' => date('Y-m-d H:i:s', time()),
            'label' => $label,
            'mark' => $mark,
            'data' => $data,
        ];
    }
}
