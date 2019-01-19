@foreach($roles as $v)
    <div class="col-md-4" style="float:left;padding-left:20px;margin-top:8px;">
        <span class="checkbox-custom checkbox-default">
            <i class="fa"></i>
            <input class="form-actions"
                   id="inputChekbox{{$v['id']}}" type="radio" value="{{$v['id']}}"
                   name="roles[]"> <label for="inputChekbox{{$v['id']}}">
                {{$v['name']}}
            </label> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        </span>
    </div>
@endforeach
