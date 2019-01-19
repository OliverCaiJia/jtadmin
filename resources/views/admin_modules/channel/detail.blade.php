<div class="bjui-pageContent">
    <input type="hidden" name="_token" value="{{ csrf_token() }}" /><!--token-->
    <table class="table table-condensed table-hover" width="100%">
        <tr>
            <td>
                <label for="full_company_name" class="control-label">渠道名称</label>
            </td>
            <td colspan="2">{{$item['name'] or ''}}</td>
        </tr>
        <tr>
            <td>
                <label for="short_company_name" class="control-label">渠道ID</label>
            </td>
            <td colspan="2">{{$item['nid'] or ''}}</td>
        </tr>
        <tr>
            <td>
                <label for="valid_deadline" class="control-label">产品名称</label>
            </td>
            <td colspan="2">{{$item['product_name'] or ''}}</td>
        </tr>
        <tr>
            <td>
                <label for="max_order_num" class="control-label">合作方式</label>
            </td>
            <td colspan="2">{{\App\Constants\ChannelConstant::SAAS_CHANNEL_TYPE_MAP[$item['type']]}}</td>
        </tr>
        <tr>
            <td>
                <label for="order_price" class="control-lab el">所属合作方</label>
            </td>
            <td colspan="2">{{\App\Models\Factory\Saas\Channel\ChannelFactory::getSaasInfoByChannelId($item['id'])}}</td>
        </tr>
        <tr>
            <td>
                <label for="promotion_cost" class="control-label">单价</label>
            </td>
            <td colspan="2">{{App\Helpers\Formater\NumberFormater::formatAmount($item['promotion_cost'])}}元</td>
        </tr>
        <tr>
            <td>
                <label for="borrowing_balance" class="control-label">借款金额</label>
            </td>
            <td colspan="2">{{App\Helpers\Formater\NumberFormater::formatAmount($item['borrowing_balance'])}}元</td>
        </tr>
        <tr>
            <td>
                <label for="repayment_method" class="control-label">还款方式</label>
            </td>
            <td colspan="2">{{\App\Constants\UserOrderConstant::ORDER_PAYMENT_METHOD[$item['repayment_method']]}}</td>
        </tr>
        <tr>
            <td>
                <label for="cycle" class="control-label">借款周期</label>
            </td>
            <td colspan="2">{{$item['cycle'] or ''}}天</td>
        </tr>
        <tr>
            <td>
                <label for="order_deadline" class="control-label">联系人</label>
            </td>
            <td colspan="2">{{$item['contact_person'] or ''}}</td>
        </tr>
        <tr>
            <td>
                <label for="account_name" class="control-label">联系人电话</label>
            </td>
            <td colspan="2">{{$item['contact_mobile'] or ''}}</td>
        </tr>
        <tr>
            <td>
                <label for="picture" class="control-label">图片</label>
            </td>
            <td>
                <div style="display: inline-block; vertical-align: middle;">
                    <span id="src_span_pic">
                        @if(!empty($item['picture']))
                            <img id="src-pic" style="width:auto;height: 100px" src="{{ $item['picture'] }}" />
                        @endif
                        </span>
                </div>
            </td>
        </tr>
    </table>
</div>
<div class="bjui-pageFooter">
    <ul>
        <li><button type="button" class="btn-close" data-icon="close" >关闭</button></li>
    </ul>
</div>