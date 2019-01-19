<div class="bjui-pageContent">
    <form action="{{ Request::url() }}" id="j_custom_form" data-toggle="validate" data-alertmsg="false">
         @include('admin_modules.system.config._form')
    </form>
</div>
<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close">取消</button></li>
        <li><button type="submit" class="btn-default" data-icon="save">保存</button></li>
    </ul>
</div>