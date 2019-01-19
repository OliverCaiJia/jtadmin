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