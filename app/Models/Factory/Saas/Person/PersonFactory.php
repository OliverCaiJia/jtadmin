<?php

namespace App\Models\Factory\Saas\Filter;

use App\Constants\SaasConstant;
use App\Models\AbsModelFactory;
use App\Models\Orm\SaasPerson;

class PersonFactory extends AbsModelFactory
{
    /**
     * 通过saas_user_id获取该合作方手下所有的person_id
     * @param $saasId
     * @return array
     */
    public static function getPersonIdsBySaasId($saasId)
    {
        if (!is_array($saasId)) {
            $saasId = [$saasId];
        }

        $personIds = SaasPerson::whereIn('saas_auth_id', $saasId)->pluck('id');

        return $personIds ? $personIds->toArray() : [];
    }

    /**
     * 通过saas_user_id获取主用户的person_id
     *
     * @param integer $saasId
     *
     * @return mixed|string
     */
    public static function getPersonIdBySaasId($saasId)
    {
        $person = SaasPerson::where([
            'saas_auth_id' => $saasId,
            'create_id' => 0,
            'is_deleted' => SaasConstant::SAAS_USER_DELETED_FALSE,
            'super_user' => 1
        ])->select('id')->first();

        return $person ? $person->id : '';
    }
}
