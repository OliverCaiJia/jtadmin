<div class="bjui-pageHeader">
    <form id="pagerForm" data-toggle="ajaxsearch" action="{{ Request::url() }}?id={{$id}}" method="post">
        <!-- 页面分页排序项-->
        @include('vendor.bjui.partials.pageregion')
        <div class="bjui-searchBar">
            <label>类别：</label><input type="text" value="{{Request::input('filter_name')}}" name="filter_name" maxlength="255" min="0" >&nbsp;
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
            <th>类别</th>
            <th>规则详情</th>
            <th>性质</th>
            <th>占比</th>
            <th>备注</th>
            <th>操作</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($items as $item)
            <tr>
                <td style="word-break:break-all;max-width:350px;">{{$item['id']}}</td>
                <td style="word-break:break-all;max-width:350px;">{{\App\Models\Factory\Saas\Filter\FilterFactory::getNameByFilterId($item['filter_id'], 'name')}}</td>
                <td style="word-break:break-all;max-width:350px;">{{\App\Models\Factory\Saas\Filter\FilterFactory::getTypeNameBySaasIdFilterId($id, $item['filter_id']) }}</td>
                <td style="word-break:break-all;max-width:350px;">
                    {{App\Constants\SaasConstant::SAAS_FILTER_KIND_MAP
                    [\App\Models\Factory\Saas\Filter\FilterFactory::getNameByFilterId($item['filter_id'], 'character')]}}
                </td>
                <td style="word-break:break-all;max-width:350px;">{{$item['partition']}}</td>
                <td style="word-break:break-all;max-width:350px;">{{$item['comment']}}</td>
                <td style="word-break:break-all;max-width:350px;">
                    @if(\App\Models\Factory\Saas\Filter\FilterFactory::getHasTypeByFilterId($item['filter_id']))
                        <a href="{{ Request::url() }}/edit?id={{$item['id']}}" data-toggle="dialog" data-width="500" data-height="300" data-id="dialog-filter-edit" data-mask="true" class="btn btn-green" data-reload-warn="本页已有打开的内容，确定将刷新本页内容，是否继续？" data-title="修改审查条件-{{\App\Models\Factory\Saas\Filter\FilterFactory::getNameByFilterId($item['filter_id'], 'name')}}">修改</a>
                    @else
                        <a href="javascript:;" class="btn btn-default" disabled="disabled">修改</a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

<!-- 分页控件 -->
@include('vendor.bjui.partials.pagination')
