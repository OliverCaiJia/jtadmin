<div class="bjui-pageContent">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" /><!--token-->
    <table class="table table-condensed table-hover" width="100%">
        <tr>
            <td>
                <label for="full_company_name" class="control-label">公司名称</label>
            </td>
            <td colspan="2">{{$item['full_company_name'] or ''}}</td>
        </tr>
        <tr>
            <td>
                <label for="short_company_name" class="control-label">公司简称</label>
            </td>
            <td colspan="2">{{$item['short_company_name'] or ''}}</td>
        </tr>
        <tr>
            <td>
                <label for="valid_deadline" class="control-label">账户有效期</label>
            </td>
            <td colspan="2">{{$item['valid_deadline'] or ''}}</td>
        </tr>
        <tr>
            <td>
                <label for="max_order_num" class="control-label">订单数量</label>
            </td>
            <td colspan="2">{{$item['max_order_num'] or ''}}</td>
        </tr>
        <tr>
            <td>
                <label for="order_price" class="control-label">订单单价</label>
            </td>
            <td colspan="2">{{App\Helpers\Formater\NumberFormater::formatAmount($item['order_price'])}}元</td>
        </tr>
        <tr>
            <td>
                <label for="order_deadline" class="control-label">订单截止时间</label>
            </td>
            <td colspan="2">{{$item['order_deadline'] or ''}}</td>
        </tr>
        <tr>
            <td>
                <label for="account_name" class="control-label">账号</label>
            </td>
            <td colspan="2">{{$item['account_name'] or ''}}</td>
        </tr>
        <tr>
            <td>
                <label for="created_at" class="control-label">创建时间</label>
            </td>
            <td colspan="2">{{$item['created_at'] or ''}}</td>
        </tr>
        <tr>
            <td>
                <label for="create_id" class="control-label">创建者</label>
            </td>
            <td colspan="2">{{\App\Models\Factory\Admin\AdminUser\AdminUserFactory::getAdminNameById($item['create_id'])}}</td>
        </tr>
    </table>
</div>
<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close" >关闭</button></li>
        <li><a href="{{ Request::url() }}/resetpsw?id={{$item['id']}}" data-toggle="doajax" class="btn btn-blue"
               data-reload-warn="本页已有打开的内容，确定将刷新本页内容，是否继续？"
               data-confirm-msg="合作方登录密码将被重置为{{\App\Constants\SaasConstant::SAAS_USER_DEFAULT_PASSWORD}}，确定重置账户密码吗？">重置密码</a></li>
        <li><a href="{{ Request::url() }}/edit?id={{$item['id']}}" data-toggle="dialog" data-id="dialog-edit"
               data-mask="true" class="btn btn-green" data-reload-warn="本页已有打开的内容，确定将刷新本页内容，是否继续？"
               data-title="编辑合作方资料">编辑</a></li>
    </ul>
</div>