<ul id="bjui-hnav-navbar">
    <!--------------------------------------  账户中心------------------------>
    <li class="active"><a href="javascript:;" data-toggle="slidebar"><i class="glyphicon glyphicon-user"></i> 账户中心 </a>
        <div class="items hide" data-noinit="true">
            <ul id="bjui-account-base" class="ztree ztree_main" data-toggle="ztree" data-on-click="MainMenuClick" data-expand-all="true" data-faicon="asterisk" data-tit="账户管理">
                <li data-id="1" data-pid="0" data-faicon="folder-open-o" data-faicon-close="folder-o">用户账户</li>
                <li data-id="11" data-pid="1" data-url="/admin/view/account" data-tabid="users-account" data-faicon="asterisk">账户列表</li>
            </ul>
        </div>
    </li>
    <!-------------------------------------- 订单管理 ------------------------>
    <li><a href="javascript:;" data-toggle="slidebar"><i class="fa fa-cart-plus"></i> 订单管理 </a>
        <div class="items hide" data-noinit="true">
            <ul id="bjui-order-bases" class="ztree ztree_main" data-toggle="ztree" data-on-click="MainMenuClick" data-expand-all="true" data-faicon="asterisk" data-tit="订单管理">
                <li data-id="1" data-pid="0" data-url="{{ route('admin.view.order.pending') }}" data-faicon="folder-open-o" data-tabid="order-pending" data-faicon-close="folder-o">待处理订单</li>
                <li data-id="2" data-pid="0" data-url="{{ route('admin.view.order.index') }}" data-faicon="folder-open-o" data-tabid="users-account" data-faicon="asterisk">订单池</li>
            </ul>
        </div>
    </li>
    <!--------------------------------------  友商管理 ------------------------>
    <li><a href="javascript:;" data-toggle="slidebar"><i class="fa fa-comments"></i> 友商管理 </a>
        <div class="items hide" data-noinit="true">
            <ul id="bjui-saas-base" class="ztree ztree_main" data-toggle="ztree" data-on-click="MainMenuClick" data-expand-all="true" data-faicon="asterisk" data-tit="合作方管理">
                <li data-id="1" data-pid="0" data-faicon="folder-open-o" data-faicon-close="folder-o">合作方管理</li>
                <li data-id="11" data-pid="1" data-url="/admin/view/saas" data-tabid="saas-list" data-faicon="asterisk">合作方列表</li>
            </ul>
        </div>
    </li>
    <!--------------------------------------  渠道管理  ------------------------>
    <li><a href="javascript:;" data-toggle="slidebar"><i class="fa fa-cogs"></i> 渠道管理 </a>
        <div class="items hide" data-noinit="true">
            <ul id="bjui-channel-base" class="ztree ztree_main" data-toggle="ztree" data-on-click="MainMenuClick" data-expand-all="true" data-faicon="asterisk" data-tit="渠道管理">
                <li data-id="1" data-pid="0" data-faicon="folder-open-o" data-faicon-close="folder-o">渠道管理</li>
                <li data-id="11" data-pid="1" data-url="/admin/view/channel" data-tabid="channel-list" data-faicon="asterisk">渠道列表</li>
            </ul>
        </div>
    </li>
    <!--------------------------------------  数据报表  ------------------------>
    <li><a href="javascript:;" data-toggle="slidebar"><i class="fa fa-bar-chart"></i> 数据报表 </a>
        <div class="items hide" data-noinit="true">
            <ul id="bjui-datagrid-base" class="ztree ztree_main" data-toggle="ztree" data-on-click="MainMenuClick" data-expand-all="true" data-faicon="asterisk" data-tit="数据报表">
                <li data-id="1" data-pid="0" data-url="/admin/view/datagrid/saas" data-tabid="datagrid-saas" data-faicon="asterisk">合作方报表</li>
                <li data-id="2" data-pid="0" data-url="/admin/view/datagrid/channel" data-tabid="datagrid-channel" data-faicon="asterisk">渠道报表</li>
            </ul>
        </div>
    </li>
    <!--------------------------------------  管理中心  ------------------------>
    <li><a href="javascript:;" data-toggle="slidebar"><i class="glyphicon glyphicon-cog"></i> 管理中心 </a>
        <div class="items hide" data-noinit="true">
            <ul id="bjui-config-base" class="ztree ztree_main" data-toggle="ztree" data-on-click="MainMenuClick" data-expand-all="true" data-faicon="asterisk" data-tit="系统配置">
                <li data-id="1" data-pid="0" data-url="/admin/view/system/config" data-tabid="system-config" data-faicon="caret-right">全局配置</li>
            </ul>
        </div>
    </li>
    <!-------------------------------------- 系统权限 -------------------------------------------------->
    <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-lock"></i> 系统权限 <span class="caret"></span></a>
        <ul class="dropdown-menu" role="menu">
            <li><a target="_blank" href="{{ route('permission.index') }}">权限列表</a></li>
            <li><a target="_blank" href="{{ route('role.index') }}">角色列表</a></li>
            <li><a target="_blank" href="{{ route('user.index') }}">系统用户</a></li>
            <li><a target="_blank" href="{{ route('department.index') }}">部门管理</a></li>
            <li class="divider"></li>
            <li><a target="_blank" href="{{ route('log-viewer::dashboard') }}">LogViewer</a></li>
        </ul>
    </li>
</ul>
