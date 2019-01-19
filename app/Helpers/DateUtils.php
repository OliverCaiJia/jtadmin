<?php

namespace App\Helpers;

class DateUtils
{
    public static function toInt($obj)
    {
        return is_numeric($obj) ? intval($obj) : 0;
    }

    //转时间   今天 昨天 2016-8-3
    public static function formatToDay($param)
    {
        $time = strtotime(date('Y-m-d', time()));
        $create_time = explode(' ', $param);
        $createTimeStr = strtotime($param);
        if ($time - $createTimeStr == 0) {
            $createTime = '今天';
        } else if ($time - $createTimeStr == 86400) {
            $createTime = '昨天';
        } else {
            $createTime = $create_time[0];
        }
        return $createTime;
    }

    //转时间  2016-12-25
    public static function formatDate($string = '')
    {
        if (empty($string)) {
            return '';
        }

        $formatDate = explode(' ', $string);
        return $formatDate[0];
    }

    //转时间  2016/12/25 14:58
    public static function formatDateToMin($string = '')
    {
        $strtotime = strtotime($string);
        return date('Y/m/d H:i', $strtotime);
    }

    /**
     * @param $money
     *
     * @return string
     * 万为单位的转换
     */
    public static function formatMoney($money)
    {
        //贷款成功数
        if ($money >= 10000) {
            return sprintf("%.1f", $money / 10000) . '万';
        } else {
            return $money;
        }
    }

    ///////////////////////// 分页/////////////////////////////
    /*
     * @desc    分页
     * @param   array   $data   所有数据
     * @param   num     $page   每页显示条数
     * @desc    num     $num    页码
     * */
    public static function pageInfo($data, $page = 1, $num = 5)
    {
        //总条数
        $total = count($data);
        //总页数
        $pageTotal = ceil($total / $num);
        //偏移量
        $offset = ($page - 1) * $num;
        //分页显示
        $new_data = [];
        $total_num = $num * $page;
        for ($i = $offset; $i < $total_num; $i++) {
            if (isset($data[$i])) {
                $new_data[] = $data[$i];
            }
        }
        $newData['list'] = $new_data;
        $newData['pageCount'] = $pageTotal;

        return $newData;
    }
}
