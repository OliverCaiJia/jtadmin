<div class="bjui-pageFooter">
    <div class="pages">
        <span>每页&nbsp;</span>
        <div class="selectPagesize">
            <select data-toggle="selectpicker" data-toggle-change="changepagesize">
                <option value="15">15</option>
                <option value="30">30</option>
                <option value="60">60</option>
                <option value="120">120</option>
                <option value="150">150</option>
                <option value="200">200</option>
                <option value="400">400</option>
            </select>
        </div>
        <span>&nbsp;条，共 {{$total or 0}} 条</span>
    </div>
    <div class="pagination-box" data-toggle="pagination" data-total="{{$total or 0}}" data-page-size="{{$pageSize or 30}}" data-page-current="{{$pageCurrent or 1}}">
    </div>
</div>