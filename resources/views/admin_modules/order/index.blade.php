<div class="bjui-pageHeader">
    <form id="pagerForm" data-toggle="ajaxsearch" action="{{ Request::url() }}" method="post">
        <!-- 页面分页排序项-->
        @include('vendor.bjui.partials.pageregion')
        <div class="bjui-searchBar">
            <label>申请日期：</label>
            <input type="text" value="{{Request::input('created_at_from') ?: date('Y-m-d')  . ' 00:00:00'}}" name="created_at_from" data-toggle="datepicker"
                   size="18" data-pattern="yyyy-MM-dd HH:mm:ss">
            -
            <input type="text" value="{{Request::input('created_at_to') ?: date('Y-m-d')  . ' 23:59:59'}}" name="created_at_to" data-toggle="datepicker"
                   size="18" data-pattern="yyyy-MM-dd HH:mm:ss">
            <label>手机号：</label><input type="text" value="{{Request::input('mobile')}}" name="mobile" maxlength="85" min="0">&nbsp;
            <label>状态：</label>
            <select name="status" data-toggle="selectpicker" class="show-tick" style="display: none;" data-width="80px">
                <option value="">请选择</option>
                <option @if(Request::input('status') == '1')selected @endif value="1">待处理</option>
                <option @if(Request::input('status') == '2')selected @endif value="2">已通过</option>
                <option @if(Request::input('status') == '3')selected @endif value="3">已拒绝</option>
            </select>
            <label>合作方：</label>
            <select name="saas_auth_id" data-toggle="selectpicker" class="show-tick" style="display: none;" data-width="80px">
                <option value="" selected>请选择</option>
                @foreach(App\Models\Factory\Saas\User\UserFactory::getShortNames() as $item)
                    <option @if(Request::input('saas_auth_id') == $item->id)selected @endif value="{{ $item->id }}"> {{ $item->short_company_name }}</option>
                @endforeach
            </select>
            <button type="submit" class="btn-default" data-icon="search">查询</button>&nbsp;
            <a class="btn btn-orange" href="javascript:;" data-toggle="reloadsearch" data-clear-query="true" data-icon="undo"><i class="fa fa-undo"></i>清空</a>
        </div>
    </form>
</div>
<div class="bjui-pageContent tableContent">
    <table class="table table-bordered table-hover table-striped table-top" data-selected-multi="false">
        <thead>
        <tr>
            <th>ID</th>
            <th data-order-field="mobile">手机号</th>
            <th>姓名</th>
            <th>证件号</th>
            <th>申请日期</th>
            <th data-order-field="amount">金额</th>
            <th data-order-field="cycle">周期</th>
            <th data-order-field="repayment_method">还款方式</th>
            <th data-order-field="channel_id">渠道</th>
            <th data-order-field="status">状态</th>
            <th>所有者</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($items as $item)
            <?php $userInfo = App\Models\Factory\User\UserReportFactory::getUserInfoById($item->user_report_id); ?>
            <tr>
                <td style="word-break:break-all;max-width:350px;">{{ $item->id }}</td>
                <td style="word-break:break-all;max-width:350px;">{{ $userInfo['mobile'] }}</td>
                <td style="word-break:break-all;max-width:350px;">{{ $userInfo['name'] }}</td>
                <td style="word-break:break-all;max-width:350px;">{{ $userInfo['id_card'] }}</td>
                <td style="word-break:break-all;max-width:350px;">{{ $item->created_at }}</td>
                <td style="word-break:break-all;max-width:350px;">{{ $item->amount }}元</td>
                <td style="word-break:break-all;max-width:350px;">
                    {{ $item->cycle }} 天
                </td>
                <td style="word-break:break-all;max-width:350px;">
                    @if($item->repayment_method == 1)
                        一次还
                    @elseif($item->repayment_method == 2)
                        分期还
                    @endif
                </td>
                <td style="word-break:break-all;max-width:350px;">
                    {{ \App\Models\Factory\Saas\Channel\ChannelFactory::getNameById($item->channel_id) }}
                </td>
                <td style="word-break:break-all;max-width:350px;">
                    @if($item->status == 1)
                        待处理
                    @elseif($item->status == 2)
                        已通过
                    @elseif($item->status == 3)
                        已拒绝
                    @endif
                </td>
                <td style="word-break:break-all;max-width:350px;">
                    {{ \App\Models\Factory\Admin\Order\OrderFactory::getOwnerNameById($item->id) }}
                </td>
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
</div>

<!-- 分页控件 -->
@include('vendor.bjui.partials.pagination')
