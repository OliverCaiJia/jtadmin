<div class="bjui-pageContent">
    <form action="{{ Request::url() }}/append" method="post" id="dialog-recharge-append" data-toggle="validate" data-alertmsg="false">
        <input type="hidden" name="user_id" value="{{$item['id']}}">
        @include('admin_modules.account.recharge._form')
    </form>
</div>
<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close">取消</button></li>
        <li><button type="submit" class="btn-default" data-icon="save">保存</button></li>
    </ul>
</div>
