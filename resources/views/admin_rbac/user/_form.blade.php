<div class="form-group">
    <label for="tag" class="col-md-3 control-label">最高权限:</label>
    <div class="col-md-4" style="float:left;padding-left:20px;margin-top:6px;">
        <span class="checkbox-custom checkbox-default">
            <i class="fa"></i>
            <input class="form-actions" id="super_user" type="Checkbox" name="super_user"
                   @if((isset($id) && $id == 1) || (isset($super_user) && $super_user == 1))
                   checked value="1" onclick="return false"
                   @endif
                   @if((isset($id) && $id == 1))
                   readonly
                    @endif
            >
            (超级管理员)
        </span>
    </div>
</div>

<div class="form-group">
    <label for="name" class="col-md-3 control-label">姓名 *:</label>
    <div class="col-md-5">
        <input type="text" class="form-control" name="name" id="name" value="{{ old('name') ?: $name }}" autofocus>
    </div>
</div>

<div class="form-group">
    <label for="username" class="col-md-3 control-label">用户名*:</label>
    <div class="col-md-5">
        <input type="text" class="form-control" name="username" id="username" value="{{ old('username') ?: $username }}" autofocus>
    </div>
</div>

<div class="form-group">
    <label for="email" class="col-md-3 control-label">邮箱 *:</label>
    <div class="col-md-5">
        <input type="text" class="form-control" name="email" id="email" value="{{ old('email') ?: $email }}" autofocus>
    </div>
</div>

<div class="form-group">
    <label for="password" class="col-md-3 control-label">密码 *:</label>
    <div class="col-md-5">
        <input type="password" class="form-control" name="password" id="password" value="" autofocus>
    </div>
</div>

<div class="form-group">
    <label for="password_confirmation" class="col-md-3 control-label">密码确认</label>
    <div class="col-md-5">
        <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" value="" autofocus>
    </div>
</div>

<div class="form-group">
    <label for="tag" class="col-md-3 control-label">部门:</label>
    <div class="col-md-5">
        <select name="departments[]" class="form-control department">
            @foreach ($departments as $dpm)
                @if($department == $dpm->id)
                    <option value="{{ $dpm->id }}" selected>{{ $dpm->name }}</option>
                @else
                    <option value="{{ $dpm->id }}">{{ $dpm->name }}</option>
                @endif
            @endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <label for="role" class="col-md-3 control-label">角色列表:</label>
    @if((isset($id) && $id == 1) || (isset($super_user) && $super_user == 1))
        <div class="col-md-4" style="float:left;padding-left:20px;"><h4>超级管理员</h4></div>
    @else
        <div class="col-md-6" id="container">
            @foreach($rolesAll as $v)
                <div class="col-md-4" style="float:left;padding-left:20px;margin-top:8px;">
                    <span class="checkbox-custom checkbox-default">
                        <i class="fa"></i>
                        <input class="form-actions"
                               @if(in_array($v['id'],$roles))
                               checked
                               @endif
                               id="inputChekbox{{$v['id']}}" type="radio" value="{{$v['id']}}"
                               name="roles[]"> <label for="inputChekbox{{$v['id']}}">
                            {{$v['name']}}
                        </label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    </span>
                </div>
            @endforeach
        </div>
    @endif
</div>
<script>
    $('.department').change(function () {
        var id = $(this).children('option:selected').val();
        $.ajax({
            url: "/admin/department/" + id + "/roles",
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

