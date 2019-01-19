<input type="hidden" name="_token" value="{{ csrf_token() }}" />
<table class="table table-condensed table-hover" width="100%">
    <tbody>
    <tr>
        <td><label for="club_username" class="control-label">公司名称</label></td>
        <td>{{$item['full_company_name'] or ''}}</td>
    </tr>
    <tr>
        <td><label for="club_user_id" class="control-label">公司简称</label></td>
        <td>{{$item['short_company_name'] or ''}}</td>
    </tr>
    <tr>
        <td><label for="club_user_id" class="control-label">帐户名</label></td>
        <td>{{$item['account_name'] or ''}}</td>
    </tr>
    <tr>
        <td><label for="user_id" class="control-label">充值金额（元）：</label></td>
        <td>
            <input type="text" name="money" value="" data-rule="required" size="20">
        </td>
    </tr>
    </tbody>
</table>
