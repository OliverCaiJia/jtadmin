<div class="bjui-pageContent">
    <form action="{{ Request::url()}}" id="credit-usercredit-dialog-append" data-toggle="validate" data-alertmsg="false" enctype="multipart/form-data" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" /><!--token-->
        <input type="hidden" name="saas_type_relation_id" value="{{$info['id']}}" />
        <input type="hidden" name="saas_user_id" value="{{$info['saas_user_id']}}" />
        <table class="table table-condensed table-hover" width="100%">
            @foreach ($items as $item)
                <tr>
                    <td>
                        @if($info['type_id'] == $item['id'])
                            <input type="radio" name="type_id" value="{{$item['id']}}" checked="">
                        @else
                            <input type="radio" name="type_id" value="{{$item['id']}}">
                        @endif
                    </td>
                    <td>
                        @if($item['has_param'])
                            <label for="type_id" class="control-label"><input type='text' size="5" name='param' data-rule="required;digits" value='{{$item['param']}}'/>{{str_replace(\App\Constants\SaasConstant::SAAS_FILTER_PREFIX, '', $item['name'])}}</label>
                        @else
                            <label for="type_id" class="control-label">{{$item['name']}}</label>
                        @endif
                    </td>
                </tr>
            @endforeach
        </table>
    </form>
</div>
<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close" >取消</button></li>
        <li><button type="submit" class="btn-default" data-icon="save">保存</button></li>
    </ul>
</div>