<div class="bjui-pageHeader">
    <form id="pagerForm" data-toggle="ajaxsearch" action="{{ Request::url() }}" method="post">
        <!-- 页面分页排序项-->
        @include('vendor.bjui.partials.pageregion')
        <div class="bjui-searchBar">
            <label>渠道名称：</label><input type="text" value="{{Request::input('name')}}" name="name" maxlength="255" min="0" >&nbsp;
            <label>渠道ID:</label><input type="text" value="{{Request::input('channel_nid')}}" name="channel_nid" maxlength="255">&nbsp;
            <button type="submit" class="btn-default" data-icon="search">查询</button>&nbsp;
            <a class="btn btn-orange" href="javascript:;" data-toggle="reloadsearch" data-clear-query="true" data-icon="undo"><i class="fa fa-undo"></i>清空</a>
            <div class="pull-right">
                <a href="{{ Request::url() }}/append" data-toggle="dialog" data-width="1000" data-height="800" data-id="dialog-saas-creation" data-mask="true" class="btn btn-default" data-reload-warn="本页已有打开的内容，确定将刷新本页内容，是否继续？" data-icon="plus" data-title="新增渠道">新增渠道</a>
            </div>
        </div>
    </form>
</div>
<div class="bjui-pageContent tableContent">
    <table class="table table-bordered table-hover table-striped table-top" data-selected-multi="false">
        <thead>
        <tr>
            <th data-order-field="id">序号</th>
            <th data-order-field="name">渠道名称</th>
            <th data-order-field="product_name">产品名称</th>
            <th data-order-field="url">URL</th>
            <th data-order-field="nid">渠道ID</th>
            <th data-order-field="type">合作方式</th>
            <th>所属合作方</th>
            <th data-order-field="created_at">创建时间</th>
            <th data-order-field="create_id">创建者</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($items))
            @foreach ($items as $item)
                <tr>
                    <td>{{$item['id']}}</td>
                    <td>{{$item['name']}}</td>
                    <td>{{$item['product_name']}}</td>
                    <td style="word-break:break-all;max-width:150px;">{{$item['url']}}</td>
                    <td>{{$item['nid']}}</td>
                    <td>{{\App\Constants\ChannelConstant::SAAS_CHANNEL_TYPE_MAP[$item['type']]}}</td>
                    <td>{{\App\Models\Factory\Saas\Channel\ChannelFactory::getSaasInfoByChannelId($item['id'])}}</td>
                    <td>{{$item['created_at']}}</td>
                    <td>{{\App\Models\Factory\Admin\AdminUser\AdminUserFactory::getAdminNameById($item['create_id'])}}</td>
                    <td>
                        <a href="{{ Request::url() }}/detail?id={{$item['id']}}" data-toggle="dialog" data-width="1000" data-height="800" data-id="dialog-detail" data-mask="true" class="btn btn-green" data-reload-warn="本页已有打开的内容，确定将刷新本页内容，是否继续？" data-title="渠道详情">详情</a>
                        <a href="{{ Request::url() }}/edit?id={{$item['id']}}" data-toggle="dialog" data-width="1000" data-height="800" data-id="dialog-detail" data-mask="true" class="btn btn-green" data-reload-warn="本页已有打开的内容，确定将刷新本页内容，是否继续？" data-title="渠道编辑-{{$item['name']}}">编辑</a>
                        <a href="{{ Request::url() }}/delete?id={{$item['id']}}" data-toggle="doajax" data-id="dialog-detail" data-mask="true" class="btn btn-red" data-reload-warn="本页已有打开的内容，确定将刷新本页内容，是否继续？" data-confirm-msg="删除渠道后将不能恢复，确定删除吗？">删除</a>
                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>
</div>

<!-- 分页控件 -->
@include('vendor.bjui.partials.pagination')
