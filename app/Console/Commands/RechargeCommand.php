<?php

namespace App\Console\Commands;

use App\Constants\RechargeConstant;
use App\Models\Chain\Recharge\DoRechargeHandler;
use App\Models\Orm\SaasAccountRecharge;
use Log;

class RechargeCommand extends AppCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'RechargeCommand';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '定期将充值中的状态置为充值成功（充值后48小时）';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        Log::info('[recharge]开始检查充值状态', ['code' => 5565]);

        $allData = SaasAccountRecharge::where('created_at', '<=', date("Y-m-d H:i:s", strtotime("48 hours ago")))
            ->where(['status' => RechargeConstant::RECHARGE_STATUS_HANDLING])->get()->toArray();

        if (empty($allData)) {
            echo "[recharge]无待处理的充值记录，退出\n";
            Log::info('[recharge]无待处理的充值记录，退出', ['code' => 5566]);
            return;
        }

        foreach ($allData as $item) {
            $data = [
                'record_id' => $item["id"],
                'user_id' => $item["saas_user_id"]
            ];
            $rechargeHandle = new DoRechargeHandler($data);
            $res = $rechargeHandle->handleRequest();
            if (isset($res['error'])) {
                echo date("Y-m-d H:i:s, ") . "ERROR：" . $res['error'] . ",原始信息：" . json_encode($item) . "\n";
                Log::error("[recharge]ERROR：" . $res['error'] . ",原始信息：" . json_encode($item) . "\n", ['code' => 5568]);
            } else {
                echo date("Y-m-d H:i:s, ") . "SUCCESS：" . json_encode($item) . "\n";
            }
        }
        Log::info('[recharge]检查充值状态结束', ['code' => 5567]);
    }
}
