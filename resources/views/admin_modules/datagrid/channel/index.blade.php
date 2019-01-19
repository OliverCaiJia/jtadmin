<div class="bjui-pageHeader">
    <form id="pagerForm" data-toggle="ajaxsearch" action="{{ Request::url() }}" method="post">
        <!-- 页面分页排序项-->
        @include('vendor.bjui.partials.pageregion')
        <div class="bjui-searchBar">
            <label>申请时间：</label>
            <input type="text" value="{{Request::input('start') ?: date('Y-m-d')  . ' 00:00:00'}}"
                   name="start" data-toggle="datepicker" size="18" data-pattern="yyyy-MM-dd HH:mm:ss">
            -
            <input type="text" value="{{Request::input('end') ?: date('Y-m-d')  . ' 23:59:59'}}" name="end"
                   data-toggle="datepicker" size="18" data-pattern="yyyy-MM-dd HH:mm:ss">
            <label>渠道名称：</label><input type="text" value="{{Request::input('channel_name')}}" name="channel_name" maxlength="255" min="0" >&nbsp;
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
            <th data-order-field="channel_id">渠道</th>
            <th data-order-field="order_price">单价</th>
            <th>手机号</th>
            <th data-order-field="created_at">申请时间</th>
            <th data-order-field="amount">申请金额</th>
            <th data-order-field="status">状态</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($items as $item)
            <?php $userInfo = App\Models\Factory\User\UserFactory::getUserInfoById($item->user_id);?>
            <tr>
                <td style="word-break:break-all;max-width:350px;">{{$item->id}}</td>
                <td style="word-break:break-all;max-width:350px;">{{App\Models\Factory\Saas\Channel\ChannelFactory::getNameById($item->channel_id)}}</td>
                <td style="word-break:break-all;max-width:350px;">{{$item->order_price}}元</td>
                <td style="word-break:break-all;max-width:350px;">{{$userInfo['mobile']}}</td>
                <td style="word-break:break-all;max-width:350px;">{{$item->created_at}}</td>
                <td style="word-break:break-all;max-width:350px;">{{App\Helpers\Formater\NumberFormater::formatAmount($item->amount)}}元</td>
                <td style="word-break:break-all;max-width:350px;">{{App\Constants\UserOrderConstant::ORDER_STATUS_MAP[$item->status]}}</td>
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
