<?php

namespace App\MongoDB\Factory\Credit;

use App\Helpers\Utils;
use App\Models\Factory\User\UserReportFactory;
use App\Models\Orm\UserDatasourceTask;
use App\Models\Orm\UserDatasocureType;
use App\Models\Orm\UserReportTask;
use App\MongoDB\Factory\MongoModelFactory;
use App\Services\Core\Credit\Scorpio\ScorpioUtils;
use App\Strategies\ScorpioStrategy;
use Carbon\Carbon;
use Log;

class ScorpioFactory extends MongoModelFactory
{
    const REPORT_TASK_STATUS = 1; //报告任务表,报告的有效值
    const REPORT_TASK_STEP_START = 99; //任务初始状态
    const REPORT_TASK_STEP_ZHIMA = 1; //芝麻完毕
    const REPORT_TASK_STEP_CARRIER = 2;  //运营商处理完毕
    const REPORT_TASK_STEP_PROCESS = 3;  //报告任务处理中
    const REPORT_TASK_STEP_OVER = 4;  //报告生成完毕
    const SCORPIO_WAND_MARK = 'credit'; //魔杖在mongo中的标签
    const DATA_SOCURE_TYPE_NID = 'scorpio'; //魔蝎数据源类型的nid
    const DATA_SOCURE_MAGIC_WAND_TYPE_NID = 'scorpio_wand';//魔杖数据源类型的nid
    const USER_BILL_BANK_TYPE_NID = 'bill_platform'; // 账单银行配置类型nid
    const USER_BILL_TASK_EXPIRED_TIME = 1; //账单过期时间:单位（天）
    const SCORPIO_NO_BILL_STATUS = 6;//网银和信用卡没有账单状态
    const USER_BILL_DETAILS_TYPE_EMAIL_NID = 'scorpio_email_bill'; //邮箱账单明细类型
    const USER_BILL_DETAILS_TYPE_EBANK_NID = 'scorpio_ebank_bill'; //网银账单明细类型

    /**
     * 获取魔蝎数据源类型ID
     *
     * @return int
     */
    public static function getDatasocureType()
    {
        $id = UserDatasocureType::where([
            'type_nid' => ScorpioFactory::DATA_SOCURE_TYPE_NID,
            'status' => 1
        ])->value('id');

        return $id ? $id : 1;
    }

    /**
     * 获取任务信息
     *
     * @param array $params
     *
     * @return array
     */
    public static function getDatasourceTask($params = [])
    {
        $message = UserDatasourceTask::where([
            'task_id' => $params['task_id'],
            'user_id' => $params['user_id']
        ])->first();

        return $message ? $message : [];
    }

    /**
     * 更新事件状态
     *
     * @param array  $params 回调返回的结果
     * @param int    $status 不同阶段的状态
     * @param string $type   数据类型
     *
     * @return $this|\Illuminate\Database\Eloquent\Model
     */
    public static function updateOrCreateDatasourceTask($params, $status, $type = 'carrier')
    {
        $task = UserDatasourceTask::where([
            'task_id' => $params['task_id'],
            'user_id' => $params['user_id']
        ])->first();

        if (!$task) {
            return UserDatasourceTask::create([
                'query_type' => $type,
                'user_id' => $params['user_id'] ?? 0,
                'task_id' => $params['task_id'] ?? '',
                'type_id' => ScorpioFactory::getDatasocureType(),
                'status' => $status,
                'status_bool' => $params['result'] ?? 1,
                'mobile' => $params['mobile'] ?? '',
                'email_id' => $params['email_id'] ?? '',
                'email' => $params['email'] ?? '',
                'message' => $params['message'] ?? '',
                'expired_at' => Carbon::now()->addDay(),
            ]);
        }

        return $task->update(['status' => $status]);
    }

    /**
     * 更新报告任务状态
     *
     * @param $params
     * @param $step
     *
     * @return mixed
     */
    public static function updateReportTaskStatus($params, $step)
    {
        $carrierTaskId = ScorpioFactory::getCarrierId($params['task_id']);
        $timeRange = ScorpioStrategy::getReportStepTimeRange($step);//获取任务开始和失效时间
        $now = Carbon::now();

        Log::info('params', ['params' => $params, 'code' => 92011]);

        $task = UserReportTask::where([
            'user_id' => $params['user_id'],
            'status' => ScorpioFactory::REPORT_TASK_STATUS,
            'pay_type' => $params['pay_type'] ?? 1
        ])
            ->where('start_time', '<=', $now)
            ->where('end_time', '>=', $now)
            ->where('step', '!=', ScorpioFactory::REPORT_TASK_STEP_OVER)
            ->first();

        Log::info('message', ['message' => $task, 'code' => 00111]);

        if (!$task) {
            return UserReportTask::create([
                'serial_num' => ScorpioUtils::generateId(UserReportFactory::getReportLastId(), 'REPORT'),
                'front_serial_num' => ScorpioUtils::generateFrontId(UserReportFactory::getReportLastId()),
                'created_ip' => Utils::ipAddress(),
                'user_id' => $params['user_id'],
                'carrier_task_id' => $carrierTaskId,
                'pay_type' => $params['pay_type'] ?? 1,
                'status' => ScorpioFactory::REPORT_TASK_STATUS,
                'step' => $step,
                'start_time' => $timeRange['start_time'],
                'end_time' => $timeRange['end_time']
            ]);
        }

        return $task->update([
            'start_time' => $timeRange['start_time'],
            'end_time' => $timeRange['end_time'],
            'updated_ip' => Utils::ipAddress(),
            'step' => $step,
        ]);
    }

    /**
     * 获取报告任务表信息
     *
     * @param int $userId        用户id
     * @param int $carrierTaskId 运营商任务id
     *
     * @return array
     */
    public static function getReportTaskInfo($userId, $carrierTaskId)
    {
        $res = UserReportTask::where([
            'user_id' => $userId,
            'carrier_task_id' => $carrierTaskId,
            'status' => ScorpioFactory::REPORT_TASK_STATUS
        ])->first();

        return $res ? $res : [];
    }

    /**
     * 获取运营商任务表的ID
     *
     * @param $taskId
     *
     * @return int
     */
    public static function getCarrierId($taskId)
    {
        $res = UserDatasourceTask::where('task_id', $taskId)->first();

        return $res ? $res->id : 0;
    }

    /**
     * 检查魔蝎的任务ID是否存在
     *
     * @param $taskId
     *
     * @return bool
     */
    public static function isValidTaskId($taskId)
    {
        $res = UserDatasourceTask::where('task_id', $taskId)->first();

        return $res ? true : false;
    }
}
