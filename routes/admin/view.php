<?php

/**
 * 后台公共页面路由
 *
 * @author zhaoqiying
 */
//////////////////////////////////////////////////////////////////////
//
// 登陆页面
Route::get('view/login', function () {
    return view('vendor.bjui.partials.login');
})->name('admin.view.login');

// 修改密码
Route::get('view/changepwd', function () {
    return view('vendor.bjui.partials.changepwd');
})->name('admin.view.changepwd');

// 超时页面
Route::get('view/timeout', function () {
    return view('vendor.bjui.partials.timeout');
})->name('admin.view.timeout');

//////////////////////////////////////////////////////////////////////
Route::group(['middleware' => ['auth:admin', 'menu', 'authAdmin'], 'namespace' => 'View', 'prefix' => 'view'], function () {
    // 系统首页面
    Route::get('dashboard', [
        'as' => 'admin.view.dashboard',
        'uses' => 'ViewController@dashboard',
    ]);

    // 系统主内容
    Route::get('home', [
        'as' => 'admin.view.home',
        'uses' => 'ViewController@home',
    ]);
    //////////////////////////////////////////////////////////////////////////

    Route::post('changepwd', [
        'as' => 'admin.view.changepwd',
        'uses' => 'Users\UserController@changepwd',
    ]);

    //---------------------------- 账户中心 ----------------------------------//
    Route::group(['namespace' => 'Account', 'prefix' => 'account'], function () {

        // 账户列表
        Route::any('', [
            'as' => 'admin.view.account.index',
            'uses' => 'AccountController@index',
        ]);

        // 充值相关
        Route::group(['prefix' => 'recharge'], function () {
            //充值页面
            Route::any('', [
                'as' => 'admin.view.account.recharge',
                'uses' => 'RechargeController@recharge',
            ]);
            Route::any('append', [
                'middleware' => ['valiAdmin:RechargeAppend'],
                'as' => 'admin.view.account.recharge.append',
                'uses' => 'RechargeController@append',
            ]);
            // 充值记录
            Route::group(['prefix' => 'records'], function () {
                //充值记录
                Route::any('', [
                    'as' => 'admin.view.account.recharge.records',
                    'uses' => 'RecordsController@records',
                ]);
                //充值撤回
                Route::any('withdraw', [
                    'as' => 'admin.view.account.recharge.records.withdraw',
                    'uses' => 'RecordsController@withdraw',
                ]);
                //通过充值
                Route::any('pass', [
                    'as' => 'admin.view.account.recharge.records.pass',
                    'uses' => 'RecordsController@pass',
                ]);
            });
        });
    });
    //---------------------------- 订单管理 ----------------------------------//
    Route::group(['namespace' => 'Order', 'prefix' => 'order'], function () {
        Route::any('', 'OrderController@index')->name('admin.view.order.index');
        Route::any('pending', 'OrderController@pending')->name('admin.view.order.pending');
        Route::get('detail/{id}', 'OrderController@detail')->name('admin.view.order.detail');
        Route::any('assign', [
            'uses' => 'OrderController@assign',
            'middleware' => ['valiAdmin:OrderAssign'],
            'as' => 'admin.view.order.assign'
        ]);
    });

    //---------------------------- 合作方管理 ----------------------------------//
    Route::group(['namespace' => 'Saas', 'prefix' => 'saas'], function () {
        // 合作方列表
        Route::any('', [
            'as' => 'admin.view.saas.index',
            'uses' => 'SaasController@index',
        ]);
        // 添加合作方
        Route::any('append', [
            'middleware' => ['valiAdmin:SaasAppend'],
            'as' => 'admin.view.saas.append',
            'uses' => 'SaasController@append',
        ]);
        // 删除合作方
        Route::post('delete', [
            'as' => 'admin.view.saas.delete',
            'uses' => 'SaasController@delete',
        ]);
        // 查看合作方详情
        Route::group(['prefix' => 'detail'], function () {
            Route::get('', [
                'as' => 'admin.view.saas.detail',
                'uses' => 'SaasController@detail',
            ]);
            // 编辑合作方
            Route::any('edit', [
                'middleware' => ['valiAdmin:SaasEdit'],
                'as' => 'admin.view.saas.detail.edit',
                'uses' => 'SaasController@edit',
            ]);
            // 重置合作方密码
            Route::post('resetpsw', [
                'as' => 'admin.view.saas.detail.resetpsw',
                'uses' => 'SaasController@resetpsw',
            ]);
        });
        // 冻结合作方帐号
        Route::post('freeze', [
            'as' => 'admin.view.saas.freeze',
            'uses' => 'SaasController@freeze',
        ]);
        // 解冻合作方帐号
        Route::post('unfreeze', [
            'as' => 'admin.view.saas.unfreeze',
            'uses' => 'SaasController@unfreeze',
        ]);
        // 查看合作方审查条件
        Route::group(['prefix' => 'filter'], function () {
            Route::any('', [
                'as' => 'admin.view.saas.filter',
                'uses' => 'FilterController@index',
            ]);
            // 编辑审查条件
            Route::any('edit', [
                'as' => 'admin.view.saas.filter.edit',
                'uses' => 'FilterController@edit',
            ]);
        });
    });

    //---------------------------- 渠道管理 ----------------------------------//
    Route::group(['namespace' => 'Channel', 'prefix' => 'channel'], function () {
        // 账户列表
        Route::any('', [
            'as' => 'admin.view.channel.index',
            'uses' => 'ChannelController@index',
        ]);
        // 添加渠道
        Route::group(['prefix' => 'append'], function () {
            Route::any('', [
                'middleware' => ['valiAdmin:ChannelAppend'],
                'as' => 'admin.view.channel.append',
                'uses' => 'ChannelController@append',
            ]);
            // 获取合作方列表
            Route::any('getsaas', [
                'as' => 'admin.view.channel.append.getsaas',
                'uses' => 'ChannelController@getsaas',
            ]);
        });
        // 删除渠道
        Route::post('delete', [
            'as' => 'admin.view.channel.delete',
            'uses' => 'ChannelController@delete',
        ]);
        // 查看渠道详情
        Route::get('detail', [
            'as' => 'admin.view.channel.detail',
            'uses' => 'ChannelController@detail',
        ]);
        // 编辑渠道
        Route::any('edit', [
            'middleware' => ['valiAdmin:ChannelEdit'],
            'as' => 'admin.view.channel.edit',
            'uses' => 'ChannelController@edit',
        ]);
    });

    //---------------------------- 数据报表 ----------------------------------//
    Route::group(['namespace' => 'Datagrid', 'prefix' => 'datagrid'], function () {
        // 账户列表
        Route::any('saas', [
            'as' => 'admin.view.datagrid.saas',
            'uses' => 'SaasController@index',
        ]);
        // 账户列表
        Route::any('channel', [
            'as' => 'admin.view.datagrid.channel',
            'uses' => 'ChannelController@index',
        ]);
    });

    //---------------------------- 管理中心 ----------------------------------//
    Route::group(['namespace' => 'System', 'prefix' => 'system'], function () {
        // 全局配置
        Route::any('config', [
            'as' => 'admin.view.system.config.index',
            'uses' => 'ConfigController@index',
        ]);
        Route::any('config/create', [
            'middleware' => ['valiAdmin:SystemConfig'],
            'as' => 'admin.view.system.config.create',
            'uses' => 'ConfigController@create',
        ]);
        Route::any('config/edit', [
            'middleware' => ['valiAdmin:SystemConfig'],
            'as' => 'admin.view.system.config.edit',
            'uses' => 'ConfigController@edit',
        ]);
        Route::any('config/delete', [
            'as' => 'admin.view.system.config.delete',
            'uses' => 'ConfigController@delete',
        ]);
    });
});
