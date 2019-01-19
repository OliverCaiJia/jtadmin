<?php

namespace App\Strategies;

use App\Models\Factory\User\UserReportFactory;

class UserReportStrategy extends AppStrategy
{
    /**
     * 组装用户基本信息数据 user_basic
     *
     * @param string $params
     *
     * @return array
     */
    public static function getUserBasic($params)
    {
        $userBasic = [];
        foreach ($params['user_basic'] as $item) {
            $userBasic[$item['key']] = $item['value'];
            $userBasic['source_name_zh'] = $params['report']['2']['value'];
            $userBasic['update_time'] = $params['report']['5']['value'];
        }

        foreach ($params['cell_phone'] as $cellPhone) {
            $userBasic[$cellPhone['key']] = $cellPhone['value'];
        }

        return json_encode($userBasic);
    }

    /**
     * 组装基本信息校验 basic_check_items
     *
     * @param string $params
     *
     * @return array
     */
    public static function getBasicCheckItems($params)
    {
        $basicCheckItems = [];
        foreach ($params['basic_check_items'] as $item) {
            $basicCheckItems[$item['check_item']] = [
                'result' => $item['result'],
                'comment' => $item['comment']
            ];
        }

        return json_encode($basicCheckItems);
    }

    /**
     * 组装联系人信息核对 application_check
     *
     * @param string $params
     *
     * @return array
     */
    public static function getApplicationCheck($params)
    {
        if (!isset($params['application_check'])) {
            return json_encode([]);
        }

        $applicationCheck = [];
        foreach ($params['application_check'] as $item) {
            $applicationCheck[] = $item['check_points'];
        }

        return json_encode($applicationCheck);
    }

    /**
     * 组装指定联系人联系情况 collection_contact
     *
     * @param string $params
     *
     * @return array
     */
    public static function getCollectionContact($params)
    {
        if (!isset($params['collection_contact'])) {
            return json_encode([]);
        }

        $collectionContact = [];
        foreach ($params['collection_contact'] as $item) {
            $collectionContact[] = $item;
        }

        return json_encode($collectionContact);
    }

    /**
     * 组装用户信息监测 user_info_check
     *
     * @param string $params
     *
     * @return array
     */
    public static function getUserInfoCheck($params)
    {
        $searchInfo = [];
        $blackInfo = [];
        foreach ($params['user_info_check'] as $item) {
            $searchInfo = $item['check_search_info'];
            $blackInfo = $item['check_black_info'];
        }

        return json_encode(array_merge($searchInfo, $blackInfo));
    }


    /**
     * 组装行为分析 cell_behavior
     *
     * @param string $params
     *
     * @return array
     */
    public static function getCellBehavior($params)
    {
        $cellBehavior = [];
        foreach ($params['cell_behavior'] as $item) {
            $cellBehavior = $item['behavior'];
        }

        return json_encode($cellBehavior);
    }

    /**
     * 组装通话详单 call_contact_detail
     *
     * @param string $params
     *
     * @return array
     */
    public static function getCallContactDetail($params)
    {
        $callContactDetail = [];
        foreach ($params['call_contact_detail'] as $key => $item) {
            $callContactDetail[] = [
                'peer_num' => $item['peer_num'],
                'p_relation' => $item['p_relation'],
                'city' => $item['city'],
                'company_name' => $item['company_name'],
                'call_cnt_1w' => $item['call_cnt_1w'],
                'call_cnt_1m' => $item['call_cnt_1m'],
                'call_cnt_3m' => $item['call_cnt_3m'],
                'call_cnt_6m' => $item['call_cnt_6m']
            ];
        }

        return json_encode($callContactDetail);
    }

    /**
     * 组装联系人区域汇总 contact_region
     *
     * @param string $params
     *
     * @return array
     */
    public static function getContactRegion($params)
    {
        $contactRegion = [];
        foreach ($params['contact_region'] as $item) {
            $contactRegion = $item['region_list'];
        }

        return json_encode($contactRegion);
    }

    /**
     * 组装亲情号通话详单 call_family_detail
     *
     * @param string $params
     *
     * @return array
     */
    public static function getCallFamilyDetail($params)
    {
        $callFamilyDetail = [];
        foreach ($params['call_family_detail'] as $item) {
            $callFamilyDetail[$item['app_point']] = $item['item']['item_1m'];
            unset($callFamilyDetail['call_cnt_more']);
        }

        return json_encode($callFamilyDetail);
    }

    /**
     * 组装行为监测 behavior_check
     *
     * @param string $params
     *
     * @return array
     */
    public static function getBehaviorCheck($params)
    {
        $behaviorCheck = [];
        foreach ($params['behavior_check'] as $item) {
            $behaviorCheck[$item['check_point']] = [
                'result' => $item['result'],
                'evidence' => $item['evidence']
            ];
        }

        return json_encode($behaviorCheck);
    }

    /**
     * 组装通话风险分析 call_risk_analysis
     *
     * @param string $params
     *
     * @return array
     */
    public static function getCallRiskAnalysis($params)
    {
        $callRiskAnalysis = [];
        foreach ($params['call_risk_analysis'] as $item) {
            $callRiskAnalysis[$item['analysis_desc']] = $item['analysis_point'];
        }

        return json_encode($callRiskAnalysis);
    }

    /**
     * 获取联系人文本供接口使用
     *
     * @param $reportId
     *
     * @return bool|string
     */
    public static function generateContact($reportId)
    {
        $contacts = UserReportFactory::getById($reportId)->contacts;
        if (!$contacts) {
            return false;
        }

        $text = [];

        foreach ($contacts as $contact) {
            $text[] = $contact['mobile'] . ':' . $contact['name'] . ':' . $contact['relationship'];
        }

        return implode(',', $text);
    }
}
