<div class="bjui-pageHeader">
    <form id="pagerForm" data-toggle="ajaxsearch" action="{{ Request::url() . '?id=' . $user_id }}" method="post">
        <!-- 页面分页排序项-->
        @include('vendor.bjui.partials.pageregion')
        <div class="bjui-searchBar">
            <label>充值时间：</label>
            <input type="text" value="{{Request::input('reg_start') ?: date('Y-m-d')  . ' 00:00:00'}}" name="reg_start" data-toggle="datepicker"
                   size="18" data-pattern="yyyy-MM-dd HH:mm:ss">
            -
            <input type="text" value="{{Request::input('reg_end') ?: date('Y-m-d')  . ' 23:59:59'}}" name="reg_end" data-toggle="datepicker"
                   size="18" data-pattern="yyyy-MM-dd HH:mm:ss">
            <button type="submit" class="btn-default" data-icon="search">查询</button>&nbsp;
            <a class="btn btn-orange" href="javascript:;" data-toggle="reloadsearch" data-clear-query="true" data-icon="undo"><i class="fa fa-undo"></i>清空</a>
        </div>
    </form>
</div>

<div class="bjui-pageContent tableContent">
    <table class="table table-bordered table-hover table-striped table-top" data-selected-multi="false">
        <thead>
        <tr>
            <th>公司名称</th>
            <th>账户名</th>
            <th>累计充值金额</th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td style="word-break:break-all;">
                {{ \App\Models\Factory\Saas\User\UserFactory::getUserNameById($user_id) }}
            </td>
            <td style="word-break:break-all;">
                {{ \App\Models\Factory\Saas\User\UserFactory::getAccountNameById($user_id) }}
            </td>
            <td style="word-break:break-all;">
                {{ App\Helpers\Formater\NumberFormater::formatAmount(\App\Models\Factory\Admin\Account\AccountFactory::getUserAccChargeById($user_id)) }}元
            </td>
        </tr>
        </tbody>
    </table>
    <table class="table table-bordered table-hover table-striped table-top" data-selected-multi="false">
        <thead>
        <tr>
            <th data-order-field="id">序号</th>
            <th data-order-field="money">充值金额</th>
            <th data-order-field="created_at">充值时间</th>
            <th data-order-field="blc_for_display">账户余额</th>
            <th data-order-field="status">状态</th>
            <th data-order-field="create_id">充值人员</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($items as $item)
            <tr>
                <td style="word-break:break-all;max-width:350px;">{{ $loop->index + 1 }}</td>
                <td style="word-break:break-all;max-width:350px;">{{ App\Helpers\Formater\NumberFormater::formatAmount($item->money) }}元</td>
                <td style="word-break:break-all;max-width:350px;">{{ $item->created_at }}</td>
                <td style="word-break:break-all;max-width:350px;">{{ App\Helpers\Formater\NumberFormater::formatAmount($item->blc_for_display) }}元</td>
                <td style="word-break:break-all;max-width:350px;">
                    @if($item->status == App\Constants\RechargeConstant::RECHARGE_STATUS_FAIL)
                        <span style="color: red; font-weight: bold;">充值失败</span>
                    @elseif($item->status == App\Constants\RechargeConstant::RECHARGE_STATUS_FINISHED)
                        <span style="color: green; font-weight: bold;">充值成功</span>
                    @elseif($item->status == App\Constants\RechargeConstant::RECHARGE_STATUS_HANDLING)
                        <span style="color: blue; font-weight: bold;">充值中</span>
                    @elseif($item->status == App\Constants\RechargeConstant::RECHARGE_STATUS_WITHDRAW)
                        <span style="color: black; font-weight: bold;">已撤销</span>
                    @else
                        <span style="color: black; font-weight: bold;">未知</span>
                    @endif
                </td>
                <td style="word-break:break-all;max-width:350px;">{{ \App\Models\Factory\Admin\AdminUser\AdminUserFactory::getAdminNameById($item->create_id) }}</td>
                <td style="word-break:break-all;max-width:350px;">
                    @if($item['status'] == App\Constants\RechargeConstant::RECHARGE_STATUS_HANDLING)
                        <a href="{{ Request::url() }}/withdraw?id={{ $item->id }}" data-id="dialog-recharge-withdraw-{{ $item->id }}" data-mask="true" class="btn btn-red" data-toggle="doajax" data-confirm-msg="您确定撤销此次充值吗？" data-reload-warn="本页已有打开的内容，确定将刷新本页内容，是否继续？" data-title="充值">撤销</a>
                        <a href="{{ Request::url() }}/pass?record_id={{ $item->id }}&user_id={{ $item->saas_user_id }}" data-id="dialog-recharge-pass-{{ $item->id }}" data-mask="true" class="btn btn-green" data-toggle="doajax" data-reload-warn="本页已有打开的内容，确定将刷新本页内容，是否继续？" data-title="充值记录">充值完成</a>
                    @else
                        <a href ="javascript:void(0);" data-id="dialog-edit" data-mask="true" class="btn btn-default" data-toggle="validate" data-confirm-msg="您确定撤销此次充值吗？" data-reload-warn="本页已有打开的内容，确定将刷新本页内容，是否继续？" data-title="充值" disabled="disabled">撤销</a>
                        <a href="javascript:void(0);" data-id="dialog-pass" data-mask="true" class="btn btn-default" data-toggle="validate" data-reload-warn="本页已有打开的内容，确定将刷新本页内容，是否继续？" data-title="充值记录" disabled="disabled">充值完成</a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<!-- 分页控件 -->
@include('vendor.bjui.partials.pagination')
