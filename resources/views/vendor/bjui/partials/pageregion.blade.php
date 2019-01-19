<input type="hidden" name="pageSize" value="{{Request::input('pageSize')}}">
<input type="hidden" name="pageCurrent" value="{{Request::input('pageCurrent')}}">
<input type="hidden" name="orderField" value="{{Request::input('orderField')}}">
<input type="hidden" name="orderDirection" value="{{Request::input('orderDirection')}}">
<input type="hidden" name="type" value="{{Request::input('type')}}">
<input type="hidden" name="_token" value="{{csrf_token()}}">
<!-- csrf-token -->
<script type="text/javascript">
    $('input[name="_token"]').val($('meta[name="csrf-token"]').attr('content'));
</script>