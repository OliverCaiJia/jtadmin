<div class="bjui-pageHeader">
    <form id="pagerForm" data-toggle="ajaxsearch" action="{{ Request::url() }}" method="post">
        <!-- 页面分页排序项-->
        @include('vendor.bjui.partials.pageregion')
        <div class="bjui-searchBar">
            <label>公司简称：</label><input type="text" value="{{Request::input('short_company_name')}}" name="short_company_name" maxlength="255" min="0" >&nbsp;
            <label>账户名：</label><input type="text" value="{{Request::input('account_name')}}" name="account_name" maxlength="255">&nbsp;
            <button type="submit" class="btn-default" data-icon="search">查询</button>&nbsp;
            <a class="btn btn-orange" href="javascript:;" data-toggle="reloadsearch" data-clear-query="true" data-icon="undo"><i class="fa fa-undo"></i>清空</a>
            <div class="pull-right">
                <a href="{{ Request::url() }}/append" data-toggle="dialog" data-width="800" data-height="500" data-id="dialog-saas-creation" data-mask="true" class="btn btn-default" data-reload-warn="本页已有打开的内容，确定将刷新本页内容，是否继续？" data-icon="plus" data-title="新增合作方">新增合作方</a>
            </div>
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
            <th data-order-field="max_order_num">订单数量</th>
            <th data-order-field="order_deadline">订单截止时间</th>
            <th data-order-field="valid_deadline">账户有效时间</th>
            <th data-order-field="created_at">创建时间</th>
            <th data-order-field="create_id">创建者</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($items as $item)
            <tr>
                <td style="word-break:break-all;max-width:350px;">{{$item['id']}}</td>
                <td style="word-break:break-all;max-width:350px;">{{$item['short_company_name']}}</td>
                <td style="word-break:break-all;max-width:350px;">{{$item['account_name']}}</td>
                <td style="word-break:break-all;max-width:150px;">{{$item['max_order_num']}}</td>
                <td style="word-break:break-all;max-width:350px;">{{$item['order_deadline']}}</td>
                <td style="word-break:break-all;max-width:350px;">{{$item['valid_deadline']}}</td>
                <td style="word-break:break-all;max-width:350px;">{{$item['created_at']}}</td>
                <td style="word-break:break-all;max-width:350px;">{{ \App\Models\Factory\Admin\AdminUser\AdminUserFactory::getAdminNameById($item['create_id'])}}</td>
                <td style="word-break:break-all;max-width:350px;">
                    @if($item['is_locked'] == App\Constants\SaasConstant::SAAS_USER_LOCKED_FALSE)
                        <a href="{{ Request::url() }}/freeze?id={{$item['id']}}" data-toggle="doajax" data-id="dialog-edit" data-mask="true" class="btn btn-blue" data-reload-warn="本页已有打开的内容，确定将刷新本页内容，是否继续？" data-confirm-msg="您确定冻结该帐户吗？">冻结</a>
                    @elseif($item['is_locked'] == App\Constants\SaasConstant::SAAS_USER_LOCKED_TRUE)
                        <a href="{{ Request::url() }}/unfreeze?id={{$item['id']}}" data-toggle="doajax" data-id="dialog-edit" data-mask="true" class="btn btn-blue" data-reload-warn="本页已有打开的内容，确定将刷新本页内容，是否继续？" data-confirm-msg="您确定解冻该帐户吗？">解冻</a>
                    @else
                        <a href="javascript:;" class="btn btn-red">状态异常</a>
                    @endif
                    <a href="{{ Request::url() }}/detail?id={{$item['id']}}" data-toggle="dialog" data-width="500" data-height="320" data-id="dialog-detail" data-mask="true" class="btn btn-green" data-reload-warn="本页已有打开的内容，确定将刷新本页内容，是否继续？" data-title="合作方详情">详情</a>
                    <a href="{{ Request::url() }}/filter?id={{$item['id']}}" data-toggle="dialog" data-width="700" data-height="400" data-id="dialog-filter" data-mask="true" class="btn btn-green" data-reload-warn="本页已有打开的内容，确定将刷新本页内容，是否继续？" data-title="审批条件-{{$item['full_company_name']}}">审批条件</a>
                    <a href="{{ Request::url() }}/delete?id={{$item['id']}}" data-toggle="doajax" data-mask="true" class="btn btn-red" data-reload-warn="本页已有打开的内容，确定将刷新本页内容，是否继续？" data-confirm-msg="删除帐户后将不能恢复，确定删除吗？">删除</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<!-- 分页控件 -->
@include('vendor.bjui.partials.pagination')