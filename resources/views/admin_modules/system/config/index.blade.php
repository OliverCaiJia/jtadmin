<div class="bjui-pageHeader">
    <form id="pagerForm" data-toggle="ajaxsearch" action="{{ Request::url() }}" method="post">
        <!-- 页面分页排序项-->
        @include('vendor.bjui.partials.pageregion')
        <!-- 页面添加的额外按钮 -->
        <div class="bjui-searchBar">
            <label>参数名:</label><input type="text" value="{{Request::input('nid')}}" name="nid" maxlength="85">&nbsp;
            <button type="submit" class="btn-default" data-icon="search">查询</button>&nbsp;
            <a class="btn btn-orange" href="javascript:;" data-toggle="reloadsearch" data-clear-query="true" data-icon="undo"><i class="fa fa-undo"></i>刷新</a>
            <div class="pull-right">
                <a href="{{ Request::url() }}/create" data-toggle="dialog" data-width="1024" data-height="720" data-id="dialog-mask" data-mask="true" data-id="form-create" data-title="添加-系统配置"><button type="button pull-right" class="btn btn-blue" data-icon="plus">添加数据</button></a>
            </div>
        </div>


    </form>
</div>
<div class="bjui-pageContent tableContent">
    <table class="table table-bordered table-hover table-striped table-top" data-selected-multi="false">
        <thead>
            <tr>
                <th data-order-field="remark">配置描述</th>
                <th data-order-field="nid">系统参数名</th>
                <th data-order-field="value">系统参数值</th>
                <th data-order-field="status">状态</th>
                <th data-order-field="create_at">创建时间</th>
                <th data-order-field="update_at" data-order-direction="asc">更新时间</th>
                <th data-order-field="update_user_id">最后修改人</th>
                <th width="110">操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
            <tr data-id="{{$item['id']}}">
                <td>{{$item['remark']}}</td>
                <td>{{$item['nid']}}</td>
                <td style="word-break:break-all;max-width:350px;">{{$item['value']}}</td>
                <td>                    
                    @if ($item['status'] === 1)
                    <p style="color:green;font-weight: bold;">使用中<p>
                    @elseif ($item['status'] === 0)
                    <p style="font-weight: bold;">未使用<p>
                    @endif
                </td>
                <td style="word-break:break-all;max-width:300px;">{{$item['create_at']}}</td>
                <td>{{$item['update_at']}}</td>
                <td>{{\App\Models\Factory\Admin\AdminUser\AdminUserFactory::getAdminNameById($item['update_user_id'])}}</td>
                <td align="center">
                    <a href="{{ Request::url() }}/edit?id={{$item['id']}}" data-toggle="dialog" data-width="1024" data-height="720" data-id="sconfig-dialog-edit-{{$item['id']}}" data-mask="true" class="btn btn-green" data-reload-warn="本页已有打开的内容，确定将刷新本页内容，是否继续？" data-title="编辑-{{$item['nid']}}">编辑</a>
                    <a href="{{ Request::url() }}/delete?id={{$item['id']}}" class="btn btn-red" data-toggle="doajax" data-confirm-msg="确定要删除该行信息吗？">删除</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<!-- 分页控件 -->
@include('vendor.bjui.partials.pagination')