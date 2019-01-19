<?php

namespace App\Services\Core\Credit\Scorpio;

use App\Helpers\Http\HttpClient;
use App\Services\Core\Credit\CreditService;

/**
 * Class ScorpioService
 * @package App\Services\Core\Credit\Scorpio
 */
class ScorpioService extends CreditService
{

    public function doFilter()
    {
    }

    /**
     * 用户报告API文档
     * $params = [
     *      'name' => '' 可选  姓名
     *      'idcard' => '' 可选  身份证号
     *      'task_id' => '' 必传  任务ID
     *      'contact' => '' 可选(类型:文本) 指定联系人（包括联系号码、姓名、关系），
     * 各联系人之间以‘,’分隔，联系人号码、姓名、关系之间以‘:’分隔，姓名和关系可以为空    1800000000:李四:朋友
     * ]
     *
     * @param       $mobile
     * @param array $params
     *
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public static function getCarrierUserReport($mobile, $params = [])
    {
        $url = CreditService::SCORPIO_API_URL .'/carrier/v3/mobiles/'.$mobile.'/mxreport';
        $request = [
            'query' => $params,
            'headers' => [
                'Authorization' => CreditService::getScorpioToken(),
            ]
        ];
        $response = HttpClient::i()->request('GET', $url, $request);
        $result = $response->getBody()->getContents();
        $res = json_decode($result, true);

        return $res;
    }
}
