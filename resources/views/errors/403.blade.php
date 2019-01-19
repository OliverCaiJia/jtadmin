<style>
    .container {
        text-align: center;
        display: table-cell;
        vertical-align: middle;
    }
</style>
<div class="container">
    <div class="error-page">
        <div class="error-content" style="padding-top: 30px">
            <h3><i class="fa fa-warning text-yellow"></i>  403 没有权限.</h3>

            <p>
                没有权限.
                此时你可以返回<a href="{{route('admin.view.login')}}"> 登录 </a> 或者重新 <a href="{{$previousUrl}}"> 刷新 </a>.
            </p>

        </div>
        <!-- /.error-content -->
    </div>
</div>

