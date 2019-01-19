<div class="bjui-pageContent">
    <form action="{{ route('admin.view.order.assign') }}" method="post" id="order-assign-dialog-append" data-toggle="validate" data-alertmsg="false">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
        <input type="hidden" name="order_id" value="{{ Request::input('id') }}" />
        <input type="hidden" name="ids" value="{{ Request::input('ids') }}">
        <label>合作方：</label>
        <select name="saas_user_id" data-toggle="selectpicker" class="show-tick" style="display: none;" data-width="80px">
            <option value="" selected>请选择</option>
            @foreach(App\Models\Factory\Saas\User\UserFactory::getShortNames() as $item)
                <option @if(Request::input('saas_user_id') == $item->id)selected @endif value="{{ $item->id }}"> {{ $item->short_company_name }}</option>
            @endforeach
        </select>
    </form>
</div>
<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close">取消</button></li>
        <li><button type="submit" class="btn-default" data-icon="save">保存</button></li>
    </ul>
</div>
