<div class="form-group">
    <label for="tag" class="col-md-3 control-label">角色名称</label>
    <div class="col-md-5">
        <input type="text" class="form-control" name="name" id="tag" value="{{ $name }}" autofocus required="required">
    </div>
</div>
<div class="form-group">
    <label for="tag" class="col-md-3 control-label">角色概述</label>
    <div class="col-md-5">
        <textarea name="description" class="form-control" rows="3" required="required">{{ $description }}</textarea>
    </div>
</div>

<div class="form-group">
    <label for="tag" class="col-md-3 control-label">部门</label>
    <div class="col-md-5">
        <select name="dpm_id" class="form-control department" required="required">
            <option value="" selected>--请选择--</option>
            @foreach ($departments as $dpm)
                @if($dpm_id == $dpm->id)
                    <option value="{{ $dpm->id }}" selected>{{ $dpm->name }}</option>
                @else
                    <option value="{{ $dpm->id }}">{{ $dpm->name }}</option>
                @endif
            @endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <div class="form-group" id="container">
        @if($permissionAll)
            @foreach($permissionAll[0] as $v)
                @if(isset($permissionAll[$v['id']]))
                    <div class="form-group">
                        <label class="control-label col-md-3 all-check">
                            {{$v['label']}}：
                        </label>
                        <div class="col-md-6">
                            @foreach($permissionAll[$v['id']] as $vv)
                                <div class="col-md-6" style="float:left;padding-left:20px;margin-top:8px;">
                            <span class="checkbox-custom checkbox-default">
                                <i class="fa"></i>
                                <input class="form-actions"
                                       @if(in_array($vv['id'],$permissions))
                                       checked
                                       @endif
                                       id="inputChekbox{{$vv['id']}}" type="Checkbox" value="{{$vv['id']}}"
                                       name="permissions[]"> <label for="inputChekbox{{$vv['id']}}">
                                    {{$vv['label']}}
                                </label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            @endforeach
        @endif
    </div>
</div>
<script>
    $('.department').change(function () {
        var id = $(this).children('option:selected').val();
        if (id < 0) {
            return false;
        }
        $.ajax({
            url: "/admin/department/" + id + "/permissions",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            dataType: 'text',
            success: function (data) {
                $('#container').empty().append(data);
            }
        });
    });
</script>

