<div class="bjui-pageHeader">
    <form id="pagerForm" data-toggle="ajaxsearch" action="{{ Request::url() }}" method="post">
        <!-- 页面分页排序项-->
        @include('vendor.bjui.partials.pageregion')
        <div class="bjui-searchBar">
            <label>申请日期：</label>
            <input type="text" value="{{Request::input('created_at_from') ?: date('Y-m-d')  . ' 00:00:00'}}"
                   name="created_at_from" data-toggle="datepicker"
                   size="18" data-pattern="yyyy-MM-dd HH:mm:ss">
            -
            <input type="text" value="{{Request::input('created_at_to') ?: date('Y-m-d')  . ' 23:59:59'}}"
                   name="created_at_to" data-toggle="datepicker"
                   size="18" data-pattern="yyyy-MM-dd HH:mm:ss">
            <label>手机号：</label><input type="text" value="{{Request::input('mobile')}}" name="mobile" maxlength="85"
                                      min="0">&nbsp;
            <button type="submit" class="btn-default" data-icon="search">查询</button>&nbsp;
            <a class="btn btn-orange" href="javascript:;" data-toggle="reloadsearch" data-clear-query="true"
               data-icon="undo"><i class="fa fa-undo"></i>清空</a>
            <div class="pull-right">
                <button type="button" class="btn-default dropdown-toggle" data-toggle="dropdown" data-icon="copy">批量操作<span class="caret"></span></button>
                <ul class="dropdown-menu right" role="menu">
                    <li id="batch-assign">
                        <a href="{{ route('admin.view.order.assign') }}"
                           data-toggle="dialog"
                           data-confirm-msg="确定要分配选中项吗？"
                           data-idname="ids"
                           data-group="ids">
                            分配
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </form>
</div>
<div class="bjui-pageContent tableContent">
    <table class="table table-bordered table-hover table-striped table-top" data-selected-multi="false" id="batch-checked">
        <thead>
            <tr>
                <th width="26">
                    <input type="checkbox" class="checkboxCtrl" data-group="ids" data-toggle="icheck">
                </th>
                <th>ID</th>
                <th>手机号</th>
                <th>姓名</th>
                <th>证件号</th>
                <th data-order-field="created_at">申请日期</th>
                <th data-order-field="amount">金额</th>
                <th data-order-field="cycle">周期</th>
                <th data-order-field="repayment_method">还款方式</th>
                <th data-order-field="channel_id">渠道</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($items as $item)
            <?php $userInfo = App\Models\Factory\User\UserReportFactory::getUserInfoById($item->user_report_id); ?>
            <tr>
                <td><input type="checkbox" name="ids" data-toggle="icheck" value="{{ $item->id }}"></td>
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
                    <a href="{{ route('admin.view.order.assign', ['id' => $item->id]) }}" data-toggle="dialog" data-width="500" data-height="300" data-id="dialog-edit"
                       data-mask="true" class="btn btn-default" data-reload-warn="本页已有打开的内容，确定将刷新本页内容，是否继续？"
                       data-title="分配">分配</a>
                    <a href="{{ route('admin.view.order.detail', ['id' => $item->id]) }}" data-toggle="dialog"
                       data-width="1124" data-height="720" data-max="ture" data-id="users-orders-view"
                       data-mask="true" class="btn btn-green" data-reload-warn="本页已有打开的内容，确定将刷新本页内容，是否继续？"
                       data-title="详情">详情</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<!-- 分页控件 -->
@include('vendor.bjui.partials.pagination')

<script>
    $("#batch-assign").click(function () {
        var ids      = [],
        $checks  = $("#batch-checked").find(':checkbox[name=ids]:checked')
        $checks.each(function() {
            ids.push($(this).val())
        })
        ids = ids.join(',')
        BJUI.dialog({
            id:'batch-assign-order',
            url: '/admin/view/order/assign',
            title:'分配',
            data: {
                ids:ids
            }
        })
    });
</script>
