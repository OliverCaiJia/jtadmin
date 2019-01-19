<div class="bjui-pageHeader">
    <form id="pagerForm" data-toggle="ajaxsearch" action="{{ Request::url() }}" method="post">
        <!-- 页面分页排序项-->
        @include('vendor.bjui.partials.pageregion')
        <div class="bjui-searchBar">
            <label>公司简称：</label><input type="text" value="{{ Request::input('short_username') }}" name="short_username" maxlength="11" min="0">&nbsp;
            <label>账户名：</label><input type="text" value="{{ Request::input('account_name') }}" name="account_name" maxlength="85" min="0">&nbsp;
            <button type="submit" class="btn-default" data-icon="search">查询</button>&nbsp;
            <a class="btn btn-orange" href="javascript:;" data-toggle="reloadsearch" data-clear-query="true" data-icon="undo"><i class="fa fa-undo"></i>清空</a>
        </div>
    </form>
</div>
<div class="bjui-pageContent tableContent">
    <table class="table table-bordered table-hover table-striped table-top" data-selected-multi="false">
        <thead>
            <tr>
                <th data-order-field="id">序号</th>
                <th data-order-field="short_company_name">公司简称</th>
                <th data-order-field="account_name">账户名</th>
                <th>累计充值</th>
                <th>账户余额</th>
                <th>充值时间</th>
                <th>充值人员</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($items as $item)
            <tr>
                <td style="word-break:break-all;max-width:350px;">{{ $item->id }}</td>
                <td style="word-break:break-all;max-width:350px;">{{ $item->short_company_name }}</td>
                <td style="word-break:break-all;max-width:350px;">{{ $item->account_name }}</td>
                <td style="word-break:break-all;max-width:350px;">{{ App\Helpers\Formater\NumberFormater::formatAmount
                    (\App\Models\Factory\Admin\Account\AccountFactory::getUserAccChargeById($item->id)) }}元</td>
                <td style="word-break:break-all;max-width:350px;">{{ App\Helpers\Formater\NumberFormater::formatAmount
                    (\App\Models\Factory\Admin\Account\AccountFactory::getUserBalanceById($item->id)) }}元</td>
                <td style="word-break:break-all;max-width:350px;">{{ App\Models\Factory\Admin\Account\RechargeFactory::getRechargeTime($item->id) }}</td>
                <td style="word-break:break-all;max-width:350px;">{{ \App\Models\Factory\Admin\AdminUser\AdminUserFactory::getAdminNameById($item->create_id) }}</td>
                <td style="word-break:break-all;max-width:350px;">
                    <a href="{{ Request::url() }}/recharge?id={{ $item->id }}" data-toggle="dialog" data-width="500" data-height="300" data-id="dialog-edit" data-mask="true" class="btn btn-green" data-reload-warn="本页已有打开的内容，确定将刷新本页内容，是否继续？" data-title="充值">充值</a>
                    <a href="{{ Request::url() }}/recharge/records?id={{ $item->id }}" data-toggle="dialog" data-width="1024" data-height="720" data-id="dialog-edit" data-mask="true" class="btn btn-green" data-reload-warn="本页已有打开的内容，确定将刷新本页内容，是否继续？" data-title="充值记录-{{ $item->full_company_name}}">充值记录</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<!-- 分页控件 -->
@include('vendor.bjui.partials.pagination')
