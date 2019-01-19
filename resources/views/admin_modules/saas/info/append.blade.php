<div class="bjui-pageContent">
    <form action="{{ Request::url()}}" id="dialog-saas-creation" data-toggle="validate" data-alertmsg='false' enctype="multipart/form-data" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" /><!--token-->
        <table class="table table-condensed table-hover" width="100%">
            <tr>
                <td>
                    <label for="full_company_name" class="control-label"><span style="color:#FF0000;">*</span>公司名称</label>
                </td>
                <td>
                    <input type="text" name="full_company_name" size="25" data-rule="required" class="input-sg">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="short_company_name" class="control-label"><span style="color:#FF0000;">*</span>公司简称</label>
                </td>
                <td>
                    <input type="text" name="short_company_name" size="25" data-rule="required" class="input-sg">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="valid_deadline" class="control-label"><span style="color:#FF0000;">*</span>账户有效期</label>
                </td>
                <td>
                    <input type="text" value="" data-toggle="datepicker" data-rule="required" size="25"
                           placeholder="点击选择日期" name="valid_deadline">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="max_order_num" class="control-label"><span style="color:#FF0000;">*</span>订单数量</label>
                </td>
                <td>
                    <input type="number" min="0" data-max="100" step="1" data-rule="required;digits"
                           name="max_order_num" class="input-sg" size="25">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="order_deadline" class="control-label"><span style="color:#FF0000;">*</span>订单截止时间</label>
                </td>
                <td>
                    <input type="text" value="" data-toggle="datepicker" data-rule="required" size="25"
                           placeholder="点击选择日期" name="order_deadline">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="order_price" class="control-label"><span style="color:#FF0000;">*</span>订单单价</label>
                </td>
                <td>
                    <input type="number" name="order_price"  min="0" data-max="1000000000" step="1"
                           data-rule="required;range[0~]" class="input-sg" size="25">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="account_name" class="control-label"><span style="color:#FF0000;">*</span>账号</label>
                </td>
                <td>
                    <input type="text" name="account_name" size="25" data-rule="required" class="input-sg">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="password" class="control-label"><span style="color:#FF0000;">*</span>密码</label>
                </td>
                <td>
                    <input type="text" name="password"  size="25" data-rule="required;psw" class="input-sg"
                           data-rule-psw="[/^[a-zA-Z_0-9]{8,16}$/, '请输入8-16位字母数字下划线']">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="password_confirmation" class="control-label"><span style="color:#FF0000;">*</span>确认密码</label>
                </td>
                <td>
                    <input type="text" name="password_confirmation" size="25" data-rule="required" class="input-sg">
                </td>
            </tr>
        </table>
    </form>
</div>
<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close">取消</button></li>
        <li><button type="submit" class="btn-default" data-icon="save">保存</button></li>
    </ul>
</div>
