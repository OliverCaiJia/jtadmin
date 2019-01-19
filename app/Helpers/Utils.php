<?php

namespace App\Helpers;

class Utils
{
    /**
     * 获取浏览器名称
     *
     * @return string
     */
    public static function getBrowser()
    {
        $agent = $_SERVER["HTTP_USER_AGENT"];
        //ie11判断
        if (strpos($agent, 'MSIE') !== false || strpos($agent, 'rv:11.0')) {
            return "ie";
        } else if (strpos($agent, 'Firefox') !== false) {
            return "firefox";
        } else if (strpos($agent, 'Chrome') !== false) {
            return "chrome";
        } else if (strpos($agent, 'Opera') !== false) {
            return 'opera';
        } else if ((strpos($agent, 'Chrome') == false) && strpos($agent, 'Safari') !== false) {
            return 'safari';
        } else if (strpos($agent, 'MicroMessenger') !== false) {
            return 'wechat';
        } else {
            return 'unknown';
        }
    }

    /**
     * 获取浏览器版本
     *
     * @return string
     */
    public static function getBrowserVer()
    {
        $agent = $_SERVER['HTTP_USER_AGENT'];
        if (preg_match('/MSIE\s(\d+)\..*/i', $agent, $regs)) {
            return $regs[1];
        } elseif (preg_match('/FireFox\/(\d+)\..*/i', $agent, $regs)) {
            return $regs[1];
        } elseif (preg_match('/Opera[\s|\/](\d+)\..*/i', $agent, $regs)) {
            return $regs[1];
        } elseif (preg_match('/Chrome\/(\d+)\..*/i', $agent, $regs)) {
            return $regs[1];
        } elseif ((strpos($agent, 'Chrome') == false) && preg_match('/Safari\/(\d+)\..*$/i', $agent, $regs)) {
            return $regs[1];
        } elseif (preg_match('/MicroMessenger\/(\d+)\..*/i', $agent, $regs)) {
            return $regs[1];
        } else {
            return 'unknown';
        }
    }

    /**
     * 判断是否微信浏览器
     *
     * @return bool
     */
    public static function isWechatBrowser()
    {
        $agent = $_SERVER['HTTP_USER_AGENT'];
        return (strpos($agent, "MicroMessenger") !== false);
    }

    /**
     * 获取访问域名
     *
     * @param $request_url
     *
     * @return type
     */
    public static function getHostUrl($request_url = null)
    {
        $request_url = empty($request_url) ? 'http://localhost' : $request_url;
        $parsed_url = parse_url($request_url);
        $scheme = isset($parsed_url['scheme']) ? $parsed_url['scheme'] . '://' : '';
        $host = isset($parsed_url['host']) ? $parsed_url['host'] : '';
        $port = isset($parsed_url['port']) ? ':' . $parsed_url['port'] : '';
        return "$scheme$host$port";
    }

    /**
     * 获取IP地址
     */
    public static function ipAddress()
    {
        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $cip = $_SERVER["HTTP_CLIENT_IP"];
        } elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif (!empty($_SERVER["REMOTE_ADDR"])) {
            $cip = $_SERVER["REMOTE_ADDR"];
        } else {
            $cip = "127.0.0.1";
        }
        return $cip;
    }

    /**
     * @param $html
     *
     * @abstract 获取html代码中的img的src
     *
     * @return array|mixed
     */
    public static function getHtmlImageSrc($html)
    {
        if (!$html) {
            return array();
        }

        $preg_partern = '/<img.+?src=\"?(.+?\.(jpg|gif|bmp|bnp|png))\"?.+?>/i';
        $match = array();
        preg_match_all($preg_partern, $html, $match);
        return $match[1];
    }

    /**
     * @abstract 替换html代码里面的img标签
     *
     * @param string $html
     * @param string $replace default ''
     *
     * @return string
     */
    public static function replaceHtmlImage($html, $replace = '')
    {
        if (!$html) {
            return '';
        }

        $preg_partern = '/<img.+src=\"?(.+\.(jpg|gif|bmp|bnp|png))\"?.+?>/i';
        return preg_replace($preg_partern, $replace, $html);
    }

    /**
     * 生成随机密码
     *
     * @param int $pw_length
     *
     * @return string
     */
    public static function createPassword($pw_length = 8)
    {
        $randpwd = '';
        for ($i = 0; $i < $pw_length; $i++) {
            $randpwd .= chr(mt_rand(48, 122));
        }
        return $randpwd;
    }

    /**
     * 计算字utf8符长度
     *
     * @param string $str
     *
     * @return int
     */
    public static function utf8StrLen($str)
    {
        $count = 0;
        for ($i = 0; $i < strlen($str); $i++) {
            $value = ord($str[$i]);
            if ($value > 127) {
                $count++;
                if ($value >= 192 && $value <= 223) {
                    $i++;
                } elseif ($value >= 224 && $value <= 239) {
                    $i = $i + 2;
                } elseif ($value >= 240 && $value <= 247) {
                    $i = $i + 3;
                }
            }
            $count++;
        }
        return $count;
    }

    /**
     * 去除特殊符号
     *
     * @param string $string
     *
     * @return mixed
     */
    public static function removeSpe($string = "")
    {
        $string = htmlspecialchars_decode($string);
        $search = array("\\\"");
        $replace = array("\"");
        return str_replace($search, $replace, $string);
    }

    /**
     * 删除HTML标签
     *
     * @param string $string
     *
     * @return mixed
     */
    public static function removeHTML($string = "")
    {
        $string = html_entity_decode($string, ENT_COMPAT, 'UTF-8');
        $string = stripslashes($string);
        $string = strip_tags($string);
        $search = array(" ", "　", "\t", "\n", "\r");
        $replace = array("", "", "", "", "");
        return str_replace($search, $replace, $string);
    }

    /**
     * @param $data 需要排序的数组
     * @param $sort 排序的字段以及排序类型
     *              $sort = [
     *              'direction' => 'SORT_ASC', //排序顺序标志 SORT_DESC 降序；SORT_ASC 升序
     *              'field' => 'x',       //排序字段
     *              ];
     *
     * @return mixed 返回结果数组
     */
    public static function sortArray($data, $sort)
    {
        $arrSort = [];
        foreach ($data as $uniqid => $row) {
            foreach ($row as $key => $value) {
                $arrSort[$key][$uniqid] = $value;
            }
        }
        if ($sort['direction']) {
            array_multisort($arrSort[$sort['field']], constant($sort['direction']), $data);
        }
        return $data;
    }


    /**
     *
     * 将数组按照某个键名去重
     *
     * @param $arr
     * @param $key
     *
     * @return array
     */
    public static function arrayUnsetTt($arr, $key)
    {
        //建立一个目标数组
        $res = array();
        foreach ($arr as $value) {
            //查看有没有重复项
            if (isset($res[$value[$key]])) {
                //有：销毁
                unset($value[$key]);
            } else {
                $res[$value[$key]] = $value;
            }
        }
        return $res;
    }

    /**
     *
     * 生成随机x位数字
     *
     * @param $num
     *
     * @return array
     */
    public static function randomNumber($num = 6)
    {
        return str_pad(mt_rand(0, 999999), $num, "0", STR_PAD_BOTH);
    }
}
