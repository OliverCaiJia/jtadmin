<div class="bjui-pageContent">
    <form action="{{ Request::url()}}" id="credit-usercredit-dialog-append" data-toggle="validate" data-alertmsg="true" enctype="multipart/form-data" method="post">
        <input type="hidden" name="_token" value="{{ csrf_token() }}" /><!--token-->
        <p>温馨提示，渠道ID、合作方式和所属合作方均属于渠道核心信息，请务必谨慎填写，提交后不可修改。</p>
        <p>温馨提示，请确保图片上传成功后再点击保存，否则新建渠道会失败。</p>
        <table class="table table-condensed table-hover" width="100%">
            <tr>
                <td>
                    <label for="name" class="control-label"><span style="color:#FF0000;">*</span>渠道名称</label>
                </td>
                <td>
                    <input type="text" name="name" data-rule="required" class="input-sg">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="nid" class="control-label"><span style="color:#FF0000;">*</span>渠道ID</label>
                </td>
                <td>
                    <input type="text" name="nid" data-rule="required;nid" class="input-sg" data-rule-nid="[/^[a-zA-Z_0-9]{4,23}$/, '请输入4-23位字母数字下划线']">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="product_name" class="control-label"><span style="color:#FF0000;">*</span>产品名称</label>
                </td>
                <td>
                    <input type="text" name="product_name" data-rule="required" class="input-sg">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="type" class="control-label"><span style="color:#FF0000;">*</span>合作方式</label>
                </td>
                <td>
                    <div class="row-input required">
                        <input type="radio" name="type" id="directed" data-toggle="icheck" value=1
                               data-rule="checked" data-label="定向  ">
                        <input type="radio" name="type" id="undirected" data-toggle="icheck" value=2
                               data-label="非定向">
                    </div>
                </td>
            </tr>
            <tr style="display: none;" id="suoshuhezuofang">
                <td>
                    <label for="saas_id" class="control-label"><span style="color:#FF0000;">*</span>所属合作方</label>
                </td>
                <td>
                    <select name="saas_id" id="saas_id" data-toggle="selectpicker">
                        @foreach($saasInfo as $item)
                        <option value="{{$item['id']}}">{{$item['short_company_name']}}({{$item['account_name']}})</option>
                        @endforeach
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="promotion_cost" class="control-label"><span style="color:#FF0000;">*</span>单价</label>
                </td>
                <td>
                    <input type="number" name="promotion_cost"  min="0" data-max="1000000000"
                           step="1" data-rule="required;range[0~1000000000]" class="input-sg">元
                </td>
            </tr>
            <tr>
                <td>
                    <label for="borrowing_balance" class="control-label"><span style="color:#FF0000;">*</span>借款金额</label>
                </td>
                <td>
                    <input type="number" name="borrowing_balance"  min="0" data-max="1000000000"
                           step="1" data-rule="required;range[0~1000000000]" class="input-sg">元
                </td>
            </tr>
            <tr>
                <td>
                    <label for="repayment_method" class="control-label"><span style="color:#FF0000;">*</span>还款方式</label>
                </td>
                <td>
                    <div class="row-input required">
                        <input type="radio" name="repayment_method" id="once" data-toggle="icheck" value=1
                               data-rule="checked" data-label="一次还">
                        <input type="radio" name="repayment_method" id="instalment" data-toggle="icheck" value=2
                               data-label="分期还">
                    </div>
                </td>
            </tr>
            <tr>
                <td>
                    <label for="cycle" class="control-label"><span style="color:#FF0000;">*</span>借款周期</label>
                </td>
                <td>
                    <input type="number" name="cycle"  min="0" data-max="1000000000" step="1"
                           data-rule="required;digits" class="input-sg">天
                </td>
            </tr>
            <tr>
                <td>
                    <label for="contact_person" class="control-label">联系人</label>
                </td>
                <td>
                    <input type="text" name="contact_person" class="input-sg">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="contact_mobile" class="control-label">联系电话</label>
                </td>
                <td>
                    <input type="text" name="contact_mobile" data-rule="length[11~15]">
                </td>
            </tr>
            <tr>
                <td>
                    <label for="picture" class="control-label"><span style="color:#FF0000;">*</span>图片</label>
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
    $('#directed').on('ifChecked', function(event){
        $("#suoshuhezuofang").css('display','table-row');
    });
    $('#undirected').on('ifChecked', function(event){
        $("#suoshuhezuofang").css('display','none');
    });
    function pic_upload_success(file, data) {
        var json = $.parseJSON(data)
        $(this).bjuiajax('ajaxDone', json)
        if (json[BJUI.keys.statusCode] == BJUI.statusCode.ok) {
            $('#picture').val(json.filename).trigger('validate')
            $('#src_span_pic').html('<img src="' + json.filename + '"id="logo-src-pic" style="width:auto;height: 100px"/>')
        }
    }
</script>
