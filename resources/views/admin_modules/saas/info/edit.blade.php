<div class="bjui-pageContent">
    <form action="{{ Request::url()}}" id="credit-usercredit-dialog-append" data-toggle="validate" data-alertmsg="false" enctype="multipart/form-data" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" /><!--token-->
        <input type="hidden" name="id" value="{{$item['id']}}" />
        <table class="table table-condensed table-hover" width="100%">
            <tr>
                <td>
                    <label for="account_name" class="control-label">账号</label>
                </td>
                <td>
                    <input type="text" name="account_name" size="25" data-rule="required"
                           class="input-sg" value="{{$item['account_name']}}" disabled="disabled">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="full_company_name" class="control-label">公司名称</label>
                </td>
                <td>
                    <input type="text" name="full_company_name"  size="25" data-rule="required"
                           class="input-sg" value="{{$item['full_company_name']}}">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="short_company_name" class="control-label">公司简称</label>
                </td>
                <td>
                    <input type="text" name="short_company_name" size="25" data-rule="required"
                           class="input-sg" value="{{$item['short_company_name']}}">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="valid_deadline" class="control-label">账户有效期</label>
                </td>
                <td>
                    <input type="text" data-toggle="datepicker" placeholder="点击选择日期" data-rule="required"
                           name="valid_deadline" value="{{date("Y-m-d", strtotime($item['valid_deadline']))}}"
                           size="25">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="max_order_num" class="control-label">订单数量</label>
                </td>
                <td>
                    <input type="number" min="0" data-max="100" step="1" name="max_order_num"
                           data-rule="required;digits" class="input-sg" value="{{$item['max_order_num']}}">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="order_price" class="control-label">订单单价</label>
                </td>
                <td>
                    <input type="number" name="order_price"  min="0" data-max="1000000000" step="1"
                           data-rule="required;range[0~]" class="input-sg" value="{{App\Helpers\Formater\NumberFormater::formatAmount($item['order_price'])}}">元
                </td>
            </tr>
            <tr>
                <td>
                    <label for="order_deadline" class="control-label">订单截止时间</label>
                </td>
                <td>
                    <input type="text" data-toggle="datepicker" placeholder="点击选择日期" data-rule="required"
                           name="order_deadline" value="{{date("Y-m-d", strtotime($item['order_deadline']))}}"
                           size="25">
                </td>
            </tr>
        </table>
    </form>
</div>
<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close" >取消</button></li>
        <li><button type="submit" class="btn-default" data-icon="save">保存</button></li>
    </ul>
</div>
