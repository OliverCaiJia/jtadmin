<script type="text/javascript">
</script>

<input type="hidden" name="_token" value="{{ csrf_token() }}" />
<table class="table table-condensed table-hover" width="100%">
    <tbody>
        <tr>
            <td><label for="nid" class="input-sg">系统参数名：</label></td>
            <td>
                <input type="text" name="nid" id="nid" value="{{$data['nid'] or ''}}" data-rule="required" size="30" class="input-sg" @isset($data['nid']) readonly @endisset>
            </td>
        </tr>
        <tr>
            <td><label for="value" class="input-sg">系统参数值：</label></td>
            <td>
                <input type="text" name="value" id="value" value="{{$data['value'] or ''}}" data-rule="required" size="50" class="input-sg">
            </td>
        </tr>
        <tr>
            <td> <label for="remark" class="input-sg">配置的描述：</label></td>
            <td>
                <textarea name="remark" id="remark" data-toggle="autoheight" cols="60" rows="5" data-rule="required">{{$data['remark'] or ''}}</textarea>
            </td>
        </tr>
        <tr>
            <td> <label for="status" class="input-sg">是否使用：</label></td>
            <td>
                <input type="radio" name="status" id="status_1" data-toggle="icheck" value="1" data-rule="checked" data-label="使用中" @isset($data['status']) @if($data['status'] == 1)checked @endif @endisset>
                <input type="radio" name="status" id="status_2" data-toggle="icheck" value="0" data-rule="checked" data-label="不使用" @isset($data['status']) @if($data['status'] == 0)checked @endif @endisset>
            </td>
        </tr>
    </tbody>
</table>
