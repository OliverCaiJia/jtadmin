<?php
/**
 *
 * 用于微信媒体上传的处理类
 */

namespace App\Helpers\Http;

class HttpRequest
{
    private static $request;
    private $ch;

    private function __construct($url, $data = null)
    {
        $this->ch = curl_init();
        curl_setopt($this->ch, CURLOPT_URL, $url);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, false);
        if (!empty($data)) {
            curl_setopt($this->ch, CURLOPT_POST, 1);
            curl_setopt($this->ch, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
    }

    /**
     * @param      $url  string 路径
     * @param null $data 数据
     *
     * @return HttpRequest
     */
    public static function i($url, $data = null)
    {
        if (!(self::$request instanceof HttpRequest)) {
            self::$request = new HttpRequest($url, $data);
        }

        return self::$request;
    }

    public function call()
    {
        $output = curl_exec($this->ch);
        curl_close($this->ch);
        return $output;
    }
}
