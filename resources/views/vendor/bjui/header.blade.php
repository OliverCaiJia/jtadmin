<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="utf-8">
<meta name="renderer" content="webkit|ie-stand" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="csrf-token" content="{{ csrf_token() }}" />
<title>借条之家|业务系统</title>
<meta name="keywords" content="借条之家-业务&数据系统"/>
<meta name="description" content="借条之家-业务&数据系统"/>

@verbatim
<!-- bootstrap - css -->
<link href="/vendor/bjui/BJUI/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
<!-- core - css -->
<link href="/vendor/bjui/BJUI/themes/css/style.css" rel="stylesheet">
<link href="/vendor/bjui/BJUI/themes/blue/core.css" id="bjui-link-theme" rel="stylesheet">
<!-- plug - css -->
<link href="/vendor/bjui/BJUI/plugins/kindeditor_4.1.10/themes/default/default.css" rel="stylesheet">
<link href="/vendor/bjui/BJUI/plugins/colorpicker/css/bootstrap-colorpicker.min.css" rel="stylesheet">
<link href="/vendor/bjui/BJUI/plugins/niceValidator/jquery.validator.css" rel="stylesheet">
<link href="/vendor/bjui/BJUI/plugins/bootstrapSelect/bootstrap-select.css" rel="stylesheet">
<link href="/vendor/bjui/BJUI/themes/css/FA/css/font-awesome.min.css" rel="stylesheet">
<!--[if lte IE 7]>
<link href="/vendor/bjui/BJUI/themes/css/ie7.css" rel="stylesheet">
<![endif]-->
<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!--[if lte IE 9]>
    <script src="/vendor/bjui/BJUI/other/html5shiv.min.js"></script>
    <script src="/vendor/bjui/BJUI/other/respond.min.js"></script>
<![endif]-->
<!-- jquery -->
<script src="/vendor/bjui/BJUI/js/jquery-1.12.4.min.js"></script>
<script src="/vendor/bjui/BJUI/js/jquery.cookie.js"></script>
<script src="/vendor/bjui/BJUI/js/jquery.md5.js"></script>

<!--[if lte IE 9]>
<script src="/vendor/bjui/BJUI/other/jquery.iframe-transport.js"></script>    
<![endif]-->

<!-- BJUI.all 分模块压缩版 -->
<script src="/vendor/bjui/BJUI/js/bjui-all.js"></script>

<!-- plugins -->
<!-- swfupload for uploadify && kindeditor -->
<script src="/vendor/bjui/BJUI/plugins/swfupload/swfupload.js"></script>
<!-- kindeditor -->
<script src="/vendor/bjui/BJUI/plugins/kindeditor_4.1.10/kindeditor-all.min.js"></script>
<script src="/vendor/bjui/BJUI/plugins/kindeditor_4.1.10/lang/zh_CN.js"></script>
<!-- colorpicker -->
<script src="/vendor/bjui/BJUI/plugins/colorpicker/js/bootstrap-colorpicker.min.js"></script>
<!-- ztree -->
<script src="/vendor/bjui/BJUI/plugins/ztree/jquery.ztree.all-3.5.js"></script>
<!-- nice validate -->
<script src="/vendor/bjui/BJUI/plugins/niceValidator/jquery.validator.js"></script>
<script src="/vendor/bjui/BJUI/plugins/niceValidator/jquery.validator.themes.js"></script>
<!-- bootstrap plugins -->
<script src="/vendor/bjui/BJUI/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="/vendor/bjui/BJUI/plugins/bootstrapSelect/bootstrap-select.min.js"></script>
<script src="/vendor/bjui/BJUI/plugins/bootstrapSelect/defaults-zh_CN.min.js"></script>
<!-- icheck -->
<script src="/vendor/bjui/BJUI/plugins/icheck/icheck.min.js"></script>
<!-- dragsort -->
<script src="/vendor/bjui/BJUI/plugins/dragsort/jquery.dragsort-0.5.2.min.js"></script>
<!-- HighCharts -->
<script src="/vendor/bjui/BJUI/plugins/highcharts/highcharts.js"></script>
<script src="/vendor/bjui/BJUI/plugins/highcharts/highcharts-3d.js"></script>
<script src="/vendor/bjui/BJUI/plugins/highcharts/themes/gray.js"></script>
<!-- ECharts -->
<script src="/vendor/bjui/BJUI/plugins/echarts/echarts.js"></script>
<script src="/vendor/bjui/BJUI/js/require.js"></script>
<!-- other plugins -->
<script src="/vendor/bjui/BJUI/other/ie10-viewport-bug-workaround.js"></script>
<script src="/vendor/bjui/BJUI/plugins/other/jquery.autosize.js"></script>
<link href="/vendor/bjui/BJUI/plugins/uploadify/css/uploadify.css" rel="stylesheet">
<script src="/vendor/bjui/BJUI/plugins/uploadify/scripts/jquery.uploadify.min.js"></script>
<script src="/vendor/bjui/BJUI/plugins/download/jquery.fileDownload.js"></script>
<!-- init -->
<script type="text/javascript">
$(function() {
    BJUI.init({
        JSPATH       : '/vendor/bjui/BJUI/',         //[可选]框架路径
        PLUGINPATH   : '/vendor/bjui/BJUI/plugins/', //[可选]插件路径
        loginInfo    : {url:'/admin/view/timeout', title:'登录', width:400, height:200}, // 会话超时后弹出登录对话框
        statusCode   : {ok:200, error:500, timeout:301}, //[可选]
        ajaxTimeout  : 50000, //[可选]全局Ajax请求超时时间(毫秒)
        pageInfo     : {total:'total', pageCurrent:'pageCurrent', pageSize:'pageSize', orderField:'orderField', orderDirection:'orderDirection'}, //[可选]分页参数
        alertMsg     : {displayPosition:'topcenter', displayMode:'slide', alertTimeout:3000}, //[可选]信息提示的显示位置，显隐方式，及[info/correct]方式时自动关闭延时(毫秒)
        keys         : {statusCode:'statusCode', message:'message'}, //[可选]
        ui           : {
                         windowWidth      : 0,    //框架可视宽度，0=100%宽，> 600为则居中显示
                         showSlidebar     : true, //[可选]左侧导航栏锁定/隐藏
                         clientPaging     : true, //[可选]是否在客户端响应分页及排序参数
                         overwriteHomeTab : false //[可选]当打开一个未定义id的navtab时，是否可以覆盖主navtab(我的主页)
                       },
        debug        : true,    // [可选]调试模式 [true|false，默认false]
        theme        : 'sky' // 若有Cookie['bjui_theme'],优先选择Cookie['bjui_theme']。皮肤[五种皮肤:default, orange, purple, blue, red, green]
    })
    
    // main - menu
    $('#bjui-accordionmenu')
        .collapse()
        .on('hidden.bs.collapse', function(e) {
            $(this).find('> .panel > .panel-heading').each(function() {
                var $heading = $(this), $a = $heading.find('> h4 > a')
                
                if ($a.hasClass('collapsed')) $heading.removeClass('active')
            })
        })
        .on('shown.bs.collapse', function (e) {
            $(this).find('> .panel > .panel-heading').each(function() {
                var $heading = $(this), $a = $heading.find('> h4 > a')
                
                if (!$a.hasClass('collapsed')) $heading.addClass('active')
            })
        })
    
    $(document).on('click', 'ul.menu-items > li > a', function(e) {
        var $a = $(this), $li = $a.parent(), options = $a.data('options').toObj()
        var onClose = function() {
            $li.removeClass('active')
        }
        var onSwitch = function() {
            $('#bjui-accordionmenu').find('ul.menu-items > li').removeClass('switch')
            $li.addClass('switch')
        }
        
        $li.addClass('active')
        if (options) {
            options.url      = $a.attr('href')
            options.onClose  = onClose
            options.onSwitch = onSwitch
            if (!options.title) options.title = $a.text()
            
            if (!options.target)
                $a.navtab(options)
            else
                $a.dialog(options)
        }
        
        e.preventDefault()
    })
    
    //时钟
    var today = new Date(), time = today.getTime()
    $('#bjui-date').html(today.formatDate('yyyy/MM/dd'))
    setInterval(function() {
        today = new Date(today.setSeconds(today.getSeconds() + 1))
        $('#bjui-clock').html(today.formatDate('HH:mm:ss'))
    }, 1000)
})

//菜单-事件
function MainMenuClick(event, treeId, treeNode) {
    event.preventDefault()
    
    if (treeNode.isParent) {
        var zTree = $.fn.zTree.getZTreeObj(treeId)
        
        zTree.expandNode(treeNode, !treeNode.open, false, true, true)
        return
    }
    
    if (treeNode.target && treeNode.target == 'dialog')
        $(event.target).dialog({id:treeNode.tabid, url:treeNode.url, title:treeNode.name})
    else
        $(event.target).navtab({id:treeNode.tabid, url:treeNode.url, title:treeNode.name, fresh:treeNode.fresh, external:treeNode.external})
}
</script>
<!-- for doc begin -->
<link type="text/css" rel="stylesheet" href="/vendor/bjui/js/syntaxhighlighter-2.1.382/styles/shCore.css"/>
<link type="text/css" rel="stylesheet" href="/vendor/bjui/js/syntaxhighlighter-2.1.382/styles/shThemeEclipse.css"/>
<script type="text/javascript" src="/vendor/bjui/js/syntaxhighlighter-2.1.382/scripts/brush.js"></script>
<link href="/vendor/bjui/doc/doc.css" rel="stylesheet">
<script type="text/javascript">
$(function(){
    SyntaxHighlighter.config.clipboardSwf = '/vendor/bjui/js/syntaxhighlighter-2.1.382/scripts/clipboard.swf'
    $(document).on(BJUI.eventType.initUI, function(e) {
        SyntaxHighlighter.highlight();
    })
})
</script>
<!-- for doc end -->
<!--Laravel中CSRF验证-->
<script type="text/javascript">
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
</script>

@endverbatim
</head>
<body>
    <!--[if lte IE 7]>
        <div id="errorie"><div>您还在使用老掉牙的IE，正常使用系统前请升级您的浏览器到 IE8以上版本 <a target="_blank" href="http://windows.microsoft.com/zh-cn/internet-explorer/ie-8-worldwide-languages">点击升级</a>&nbsp;&nbsp;强烈建议您更改换浏览器：<a href="http://se.360.cn/" target="_blank">360浏览器</a></div></div>
    <![endif]-->
    <div id="bjui-window">
