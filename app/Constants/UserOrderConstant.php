<?php

namespace App\Constants;

class UserOrderConstant extends AppConstant
{
    //订单状态: 1、待处理 2、已通过 3、已拒绝
    const PENDING = 1;
    const PASSED = 2;
    const REFUSED = 3;

    //还款方式: 1、一次还款 2、分期还款
    const LUMP_SUM = 1;
    const INSTALLMENT = 2;

    // 订单还款方式常量
    const ORDER_PAYMENT_METHOD = [
        self::LUMP_SUM => '一次还',
        self::INSTALLMENT => '分期还',
    ];

    // 订单状态展示MAP
    const ORDER_STATUS_MAP = [
        self::PENDING => '待处理',
        self::PASSED => '已通过',
        self::REFUSED => '已拒绝'
    ];
}
