<?php

namespace App\Models\Factory\User;

use App\Models\AbsModelFactory;
use App\Models\Orm\UserReport;
use App\Strategies\UserReportStrategy;

class UserReportFactory extends AbsModelFactory
{
    /**
     * 通过id获取report信息
     *
     * @param $id
     *
     * @return mixed|static
     */
    public static function getById($id)
    {
        return UserReport::find($id);
    }

    /**
     * 更新报告表
     *
     * @param $reportTaskInfo
     * @param $params
     * @param $attributes
     *
     * @return bool
     */
    public static function update($reportTaskInfo, $params, $attributes)
    {
        return UserReport::where('id', $params['report_id'])->update([
            'task_id' => $reportTaskInfo->id ?? '',
            'front_serial_num' => $reportTaskInfo->front_serial_num ?? '',
            'serial_num' => $reportTaskInfo->serial_num ?? '',
            'application_check' => UserReportStrategy::getApplicationCheck($attributes),
            'collection_contact' => UserReportStrategy::getCollectionContact($attributes),
            'user_basic' => UserReportStrategy::getUserBasic($attributes),
            'basic_check_items' => UserReportStrategy::getBasicCheckItems($attributes),
            'cell_behavior' => UserReportStrategy::getCellBehavior($attributes),
            'call_contact_detail' => UserReportStrategy::getCallContactDetail($attributes),
            'contact_region' => UserReportStrategy::getContactRegion($attributes),
            'behavior_check' => UserReportStrategy::getBehaviorCheck($attributes),
            'call_family_detail' => UserReportStrategy::getCallFamilyDetail($attributes),
            'user_info_check' => UserReportStrategy::getUserInfoCheck($attributes),
            'call_risk_analysis' => UserReportStrategy::getCallRiskAnalysis($attributes),
            'name' => $params['name'],
            'id_card' => $params['idcard'],
            'mobile' => $params['mobile'],
        ]);
    }


    /**
     * 获取报告表的最后一个ID
     *
     * @return mixed
     */
    public static function getReportLastId()
    {
        $report = UserReport::orderBy('id', 'desc')->select('id')->first();

        return $report->id;
    }

    /**
     * 通过身份证号码获取报告
     *
     * @param $idCard
     *
     * @return \Illuminate\Support\Collection
     */
    public static function getIdsByIdCard($idCard)
    {
        return UserReport::where('id_card', $idCard)
            ->orderBy('id', 'desc')
            ->select('id')
            ->get();
    }

    /**
     * 通过report_id获取报告用户信息
     * @param $reportId
     * @return \Illuminate\Database\Eloquent\Model|null|object|static
     */
    public static function getUserInfoById($reportId)
    {
        return UserReport::whereKey($reportId)->select([
            'id',
            'name',
            'mobile',
            'id_card',
            'location',
            'address',
            'contacts',
            'user_id'
        ])->first();
    }
}
