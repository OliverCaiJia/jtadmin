<?php

namespace App\Helpers;

class ColorUtils
{
    /**
     * 16进制颜色转换为RGB色值
     *
     * @param $hexColor
     * @method hex2rgb
     *
     * @return array
     */
    public static function hex2rgb($hexColor)
    {
        if (strpos($hexColor, '#') !== false) {
            $color = str_replace('#', '', $hexColor);
            if (strlen($color) > 3) {
                $rgb = array(
                    hexdec(substr($color, 0, 2)),
                    hexdec(substr($color, 2, 2)),
                    hexdec(substr($color, 4, 2))
                );
            } else {
                $color = str_replace('#', '', $hexColor);
                $r = substr($color, 0, 1) . substr($color, 0, 1);
                $g = substr($color, 1, 1) . substr($color, 1, 1);
                $b = substr($color, 2, 1) . substr($color, 2, 1);
                $rgb = array(
                    hexdec($r),
                    hexdec($g),
                    hexdec($b)
                );
            }

            return $rgb;
        }
        return $hexColor;
    }

    /**
     * 16进制颜色转换为RGB色值
     *
     * @param $hexColor
     * @method hex2rgba
     *
     * @return array
     */
    public static function hex2rgba($hexColor)
    {
        if (strpos($hexColor, '#') !== false) {
            $color = str_replace('#', '', $hexColor);
            if (strlen($color) > 3) {
                $rgba = array(
                    hexdec(substr($color, 0, 2)),
                    hexdec(substr($color, 2, 2)),
                    hexdec(substr($color, 4, 2)),
                    1
                );
            } else {
                $color = str_replace('#', '', $hexColor);
                $r = substr($color, 0, 1) . substr($color, 0, 1);
                $g = substr($color, 1, 1) . substr($color, 1, 1);
                $b = substr($color, 2, 1) . substr($color, 2, 1);
                $rgba = array(
                    hexdec($r),
                    hexdec($g),
                    hexdec($b),
                    1
                );
            }

            return $rgba;
        }
        return $hexColor;
    }

    /**
     * RGB转 十六进制
     *
     * @param $rgb RGB颜色的字符串 如：rgb(255,255,255);
     *
     * @return string 十六进制颜色值 如：#FFFFFF
     */
    public static function rgb2hex($rgb)
    {
        if (strpos($rgb, 'rgb') !== false) {
            $regexp = "/^rgb\(([0-9]{0,3})\,\s*([0-9]{0,3})\,\s*([0-9]{0,3})\)/";
            preg_match($regexp, $rgb, $match);
            array_shift($match);
            $hexColor = "#";
            $hex = array('0', '1', '2', '3', '4', '5', '6', '7', '8', '9', 'A', 'B', 'C', 'D', 'E', 'F');
            for ($i = 0; $i < 3; $i++) {
                $r = null;
                $c = $match[$i];
                $hexAr = array();
                while ($c > 16) {
                    $r = $c % 16;
                    $c = ($c / 16) >> 0;
                    array_push($hexAr, $hex[$r]);
                }
                array_push($hexAr, $hex[$c]);
                $ret = array_reverse($hexAr);
                $item = implode('', $ret);
                $item = str_pad($item, 2, '0', STR_PAD_LEFT);
                $hexColor .= $item;
            }
            return $hexColor;
        }
        return $rgb;
    }
}
