<div class="bjui-pageContent">
    <form action="{{ Request::url()}}" id="dialog-saas-creation" data-toggle="validate" data-alertmsg="false" enctype="multipart/form-data" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" /><!--token-->
        <input type="hidden" name="id" value="{{ $item['id'] }}" />
        <input type="hidden" name="nid" value="{{ $item['nid'] }}" />
        <table class="table table-condensed table-hover" width="100%">
            <tr>
                <td>
                    <label for="nid" class="control-label">渠道ID</label>
                </td>
                <td>
                    <input type="text" name="nid" value="{{$item['nid']}}" size="25" data-rule="required"
                           class="input-sg" disabled="disabled">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="type" class="control-label">合作方式</label>
                </td>
                <td>
                    <input type="text" name="type" size="25"
                           value="{{\App\Constants\ChannelConstant::SAAS_CHANNEL_TYPE_MAP[$item['type']]}}"
                           class="input-sg" data-rule="required" disabled="disabled">
                </td>
            </tr>
            @if($item['type'] == \App\Constants\ChannelConstant::SAAS_CHANNEL_TYPE_DIRECTED)
                <tr>
                    <td>
                        <label for="saas_name" class="control-label">所属合作方</label>
                    </td>
                    <td>
                        <input type="text" name="saas_name" size="25" class="input-sg"
                               value="{{\App\Models\Factory\Saas\Channel\ChannelFactory::getSaasInfoByChannelId($item['id'])}}"
                               data-rule="required" disabled="disabled">
                    </td>
                </tr>
            @endif
            <tr>
                <td>
                    <label for="name" class="control-label">渠道名称</label>
                </td>
                <td>
                    <input type="text" name="name" size="25" value="{{$item['name']}}" size="25"
                           data-rule="required" class="input-sg">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="product_name" class="control-label">产品名称</label>
                </td>
                <td>
                    <input type="text" name="product_name" value="{{$item['product_name']}}" size="25"
                           data-rule="required" class="input-sg">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="promotion_cost" class="control-label">单价</label>
                </td>
                <td>
                    <input type="number" name="promotion_cost"  min="0" data-max="1000000000" step="1"
                           data-rule="required;range[0~1000000000]" class="input-sg"
                           value="{{App\Helpers\Formater\NumberFormater::roundedAmount($item['promotion_cost'])}}">元
                </td>
            </tr>
            <tr>
                <td>
                    <label for="borrowing_balance" class="control-label">借款金额</label>
                </td>
                <td>
                    <input type="number" name="borrowing_balance"  min="0" data-max="1000000000" step="1"
                           data-rule="required;range[0~1000000000]" class="input-sg"
                           value="{{App\Helpers\Formater\NumberFormater::roundedAmount($item['borrowing_balance'])}}">元
                </td>
            </tr>
            <tr>
                <td>
                    <label for="repayment_method" class="control-label">还款方式</label>
                </td>
                <td>
                    <div class="row-input required">
                        @if($item['repayment_method'] == \App\Constants\UserOrderConstant::LUMP_SUM)
                            <input type="radio" name="repayment_method" id="once" data-toggle="icheck"
                                   value={{\App\Constants\UserOrderConstant::LUMP_SUM}}
                                   data-label="一次还" checked="checked">
                            <input type="radio" name="repayment_method" id="instalment" data-toggle="icheck"
                                   value={{\App\Constants\UserOrderConstant::INSTALLMENT}}
                                   data-label="分期还" data-rule="checked">
                        @else
                            <input type="radio" name="repayment_method" id="once" data-toggle="icheck"
                                   value={{\App\Constants\UserOrderConstant::LUMP_SUM}}
                                   data-label="一次还">
                            <input type="radio" name="repayment_method" id="instalment" data-toggle="icheck"
                                   value={{\App\Constants\UserOrderConstant::INSTALLMENT}}
                                   data-label="分期还" data-rule="checked" checked="checked">
                        @endif
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="cycle" class="control-label">借款周期</label>
                </td>
                <td>
                    <input type="number" name="cycle"  min="0" data-max="1000000000" step="1"
                           data-rule="required;digits" class="input-sg" value="{{$item['cycle']}}">天
                </td>
            </tr>
            <tr>
                <td>
                    <label for="contact_person" class="control-label">联系人</label>
                </td>
                <td>
                    <input type="text" name="contact_person" value="{{$item['contact_person']}}" size="25" class="input-sg">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="contact_mobile" class="control-label">联系电话</label>
                </td>
                <td>
                    <input type="text" size="25" name="contact_mobile" value="{{$item['contact_mobile']}}" class="input-sg" data-rule="length[11~15]">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="picture" class="control-label">图片</label>
                </td>
                <td>
                    <div style="display: inline-block; vertical-align: middle;">
                        <div id="src_pic_up" data-toggle="upload" data-uploader="/admin/serve/upload?prefix=channel"
                             data-file-size-limit="3000000"
                             data-file-type-exts="*.jpg;*.png;*.gif;*.mpg"
                             data-multi="false"
                             data-on-upload-success="pic_upload_success"
                             data-icon="cloud-upload"
                             data-auto="true"
                        >
                        </div>
                        <input type="hidden" name="picture" value="{{ $item['picture'] or '' }}" id="picture">
                        <span id="src_span_pic">
                            @if(!empty($item['picture']))
                                <img id="src-pic" style="width:auto;height: 100px" src="{{ $item['picture'] }}" />
                            @endif
                        </span>
                    </div>
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
<script type="text/javascript">
    function pic_upload_success(file, data) {
        var json = $.parseJSON(data)
        $(this).bjuiajax('ajaxDone', json)
        if (json[BJUI.keys.statusCode] == BJUI.statusCode.ok) {
            $('#picture').val(json.filename).trigger('validate')
            $('#src_span_pic').html('<img src="' + json.filename + '"id="logo-src-pic" style="width:auto;height: 100px"/>')
        }
    }
</script>
