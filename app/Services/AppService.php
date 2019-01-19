<?php

namespace App\Services;

/**
 * 外部Http Service服务调用
 */
class AppService
{
    // alioss存储根目录
    const ENV_ALIOSS_PATH = PRODUCTION_ENV ? 'production/' : 'test/';
}
