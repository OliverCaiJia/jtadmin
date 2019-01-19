<?php

namespace App\Services\Core\Credit\Scorpio;

use App\Services\Core\Credit\CreditService;

class ScorpioUtils
{
    //事件名称和对应的状态
    const SCORPIO_EVENT_SUBMIT_ALERT = 'task.submit'; //任务创建通知
    const SCORPIO_EVENT_SUBMIT_STATUS = 0;

    const SCORPIO_EVENT_CREATE_ALERT = 'task'; //任务登录状态通知
    const SCORPIO_EVENT_LOGIN_STATUS = 1;

    const SCORPIO_EVENT_FAIL_ALERT = 'task.fail';
    const SCORPIO_EVENT_FAIL_STATUS = 2; //任务采集失败通知

    const SCORPIO_EVENT_BILL_ALERT = 'bill'; //账单通知
    const SCORPIO_EVENT_ALL_BILLS_ALERT = 'allbill';  //账单通知
    const SCORPIO_EVENT_BILL_STATUS = 3;

    const SCORPIO_EVENT_REPORT_ALERT = 'report';
    const SCORPIO_EVENT_REPORT_STATUS = 4;  //报告通知

    const SCORPIO_EVENT_SNS_ALERT = 'sns';

    //运营商
    const SCORPIO_CARRIER_TYPE = 'carrier';

    //header信息
    const SCORPIO_BACK_HEADER_EVENT = 'X-Moxie-Event';
    const SCORPIO_BACK_HEADER_TYPE = 'X-Moxie-Type';
    const SCORPIO_BACK_HEADER_SIGN = 'X-Moxie-Signature';
    const SCORPIO_BACK_HEADER_UID = 'X-Moxie-Uid';

    const SCORPIO_REPORT_LENGTH = 32;

    const SCORPIO_EVENT_MAP = [
        self::SCORPIO_EVENT_SUBMIT_ALERT,
        self::SCORPIO_EVENT_CREATE_ALERT,
        self::SCORPIO_EVENT_FAIL_ALERT,
        self::SCORPIO_EVENT_BILL_ALERT,
        self::SCORPIO_EVENT_REPORT_ALERT
    ];

    /**
     * 生成回调签名
     *
     * @param string $params 将传过来的参数转化成json字符串
     *
     * @return string
     */
    public static function signCallback($params)
    {
        return base64_encode(hash_hmac('sha256', $params, CreditService::getScorpioCallBackSecret(), true));
    }

    /**
     * 获取编号
     *
     * @param int    $lastId 最后一个ID
     * @param string $prefix 前缀
     * @param string $name   名称
     * @param int    $num    编号数字
     *
     * @return string
     */
    public static function generateId($lastId, $name = 'VIP', $prefix = 'SD', $num = 8)
    {
        //获取毫秒时间
        list($usec, $sec) = explode(" ", microtime());
        $msec = round($usec * 1000);
        $millisecond = str_pad($msec, 3, '0', STR_PAD_RIGHT);
        $timeLength = date("YmdHis") . $millisecond;

        $length = ScorpioUtils::SCORPIO_REPORT_LENGTH - strlen(trim($prefix)) - strlen(trim($name)) - strlen(trim($timeLength)) - $num - 2;

        //如果还有多余的长度获取随机字符串
        $str = '';
        if ($length > 0) {
            $str = PaymentService::i()->getRandString($length);
        } else {
            $name = substr($name, 0, $length);
        }

        //获取数字
        $strNum = sprintf("%0" . $num . "d", ($lastId + 1)); //UserVipFactory::getVipLastId()

        return $prefix . '-' . $name . '-' . $str . $timeLength . $strNum;
    }

    /**
     * 生成前端报告编号
     *
     * @param        $lastId
     * @param string $prefix
     * @param int    $num
     *
     * @return string
     */
    public static function generateFrontId($lastId, $prefix = 'SD', $num = 7)
    {
        $timeLenght = date('ymd', time());

        //获取数字
        $strNum = sprintf("%0" . $num . "d", ($lastId + 1)); //UserVipFactory::getVipLastId()

        return $prefix . $timeLenght . $strNum;
    }
}
