<?php

namespace App\Strategies;

use App\Models\Orm\EventHeap;

/**
 * 事件公共策略
 *
 * @package App\Strategies
 */
class EventStrategy extends AppStrategy
{
    /**
     * 添加事件的各类下拉选项
     *
     * @param array $eventHeaps
     *
     * @return array
     */
    public static function transferList($eventHeaps = [])
    {
        $list = [];
        $list['noticeList'][0] = $list['smsList'][0] = $list['scoreList'][0] = $list['alertList'][0] = '请选择';
        if (!empty($eventHeaps)) {
            foreach ($eventHeaps as $key => $val) {
                switch ($val['type']) {
                    case 1:
                        $list['noticeList'][$val['id']] = $val['title'];
                        break;
                    case 2:
                        $list['smsList'][$val['id']] = $val['title'];
                        break;
                    case 3:
                        $list['scoreList'][$val['id']] = $val['title'];
                        break;
                    case 4:
                        $list['alertList'][$val['id']] = $val['title'];
                        break;
                }
            }
        }
        return $list;
    }

    /**
     * 定义事件类型
     *
     * @return array
     */
    public static function getEventType()
    {
        $types = [
            0 => '可控类事件',
            1 => '埋点类事件'
        ];
        return $types;
    }

    public static function getEventTypeString($type)
    {
        $types = [
            0 => '可控类事件',
            1 => '埋点类事件'
        ];
        return isset($types[$type]) ? $types[$type] : '未知类型';
    }

    /**
     *
     * @param $type
     *
     * @return mixed|string
     */
    public static function getEventHeapsType($type)
    {
        $types = [
            1 => '通知',
            2 => '短信',
            3 => '积分',
            4 => '弹窗'
        ];
        return isset($types[$type]) ? $types[$type] : '未知';
    }


    /**
     * @param $type
     *
     * @return mixed|string
     */
    public static function getEventHeapsTypeMsg($type)
    {
        $messages = [
            1 => 'notice',
            2 => 'sms',
            3 => 'score',
            4 => 'alert'
        ];
        return isset($messages[$type]) ? $messages[$type] : '未知';
    }

    /**
     *
     * @param $type
     *
     * @return mixed|string
     */
    public static function getEventProperty($type)
    {
        $types = [
            1 => 'notice_id',
            2 => 'sms_id',
            3 => 'score_id',
            4 => 'alert_id'
        ];
        return isset($types[$type]) ? $types[$type] : '未知';
    }
}
