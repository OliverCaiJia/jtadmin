<div class="bjui-pageHeader">
    <form id="pagerForm" data-toggle="ajaxsearch" action="{{ Request::url() }}" method="post">
        <!-- 页面分页排序项-->
        @include('vendor.bjui.partials.pageregion')
        <div class="bjui-searchBar">
            <label>分单时间：</label>
            <input type="text" value="{{Request::input('start') ?: date('Y-m-d')  . ' 00:00:00'}}"
                   name="start" data-toggle="datepicker" size="18" data-pattern="yyyy-MM-dd HH:mm:ss">
            -
            <input type="text" value="{{Request::input('end') ?: date('Y-m-d')  . ' 23:59:59'}}" name="end"
                   data-toggle="datepicker" size="18" data-pattern="yyyy-MM-dd HH:mm:ss">
            <label>公司简称：</label><input type="text" value="{{Request::input('short_company_name')}}" name="short_company_name" maxlength="255" min="0" >&nbsp;
            <button type="submit" class="btn-default" data-icon="search">查询</button>&nbsp;
            <a class="btn btn-orange" href="javascript:;" data-toggle="reloadsearch" data-clear-query="true" data-icon="undo"><i class="fa fa-undo"></i>清空</a>
        </div>
    </form>
</div>
<div class="bjui-pageContent tableContent">
    <table class="table table-bordered table-hover table-striped table-top" data-selected-multi="false">
        <thead>
        <tr>
            <th data-order-field="id">ID</th>
            <th>手机号</th>
            <th>姓名</th>
            <th>证件号</th>
            <th data-order-field="channel_id">渠道</th>
            <th>账户名</th>
            <th data-order-field="order_price">单价</th>
            <th>合作方</th>
            <th data-order-field="assigned_at">分单时间</th>
            <th data-order-field="operator_id">操作者</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($items as $item)
            <?php $userInfo = App\Models\Factory\User\UserReportFactory::getUserInfoById($item->user_report_id); ?>
            <tr>
                <td style="word-break:break-all;max-width:350px;">{{$item->id}}</td>
                <td style="word-break:break-all;max-width:350px;">{{$userInfo['mobile']}}</td>
                <td style="word-break:break-all;max-width:350px;">{{$userInfo['name']}}</td>
                <td style="word-break:break-all;max-width:350px;">{{$userInfo['id_card']}}</td>
                <td style="word-break:break-all;max-width:350px;">{{App\Models\Factory\Saas\Channel\ChannelFactory::getNameById($item->channel_id)}}</td>
                <td style="word-break:break-all;max-width:350px;">{{ \App\Models\Factory\User\UserFactory::getUserInfoById($userInfo['user_id'])['account_name']}}</td>
                <td style="word-break:break-all;max-width:350px;">{{App\Helpers\Formater\NumberFormater::formatAmount($item->order_price)}}元</td>
                <td style="word-break:break-all;max-width:350px;">{{App\Models\Factory\Admin\Order\OrderFactory::getSaasInfoByOrderId($item->id) }}</td>
                <td style="word-break:break-all;max-width:350px;">{{$item->assigned_at}}</td>
                <td style="word-break:break-all;max-width:350px;">{{ \App\Models\Factory\Admin\AdminUser\AdminUserFactory::getAdminNameById($item->operator_id)}}</td>
                <td style="word-break:break-all;max-width:350px;">
                    <a href="{{ route('admin.view.order.detail', ['id' => $item->id]) }}" data-toggle="dialog"
                       data-width="1124" data-height="720" data-max="ture" data-id="users-bills-account-bill-view"
                       data-mask="true" class="btn btn-green" data-reload-warn="本页已有打开的内容，确定将刷新本页内容，是否继续？"
                       data-title="详情">详情
                    </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <h3 class="pull-right" style="margin-right: 10px"><span>总计金额：{{$sum}}元</span></h3>
</div>

<!-- 分页控件 -->
@include('vendor.bjui.partials.pagination')
