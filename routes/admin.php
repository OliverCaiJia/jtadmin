<?php

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | This file is where you may define all of the routes that are handled
  | by your application. Just tell Laravel the URIs it should respond
  | to using a Closure or controller method. Build something great!
  |
 */
Route::get('/', function () {
    return redirect('/admin/home');
})->name('admin');

Route::get('index', function () {
    return redirect('/admin/home');
})->name('admin.index');

Route::get('home', ['middleware' => ['auth:admin', 'menu', 'authAdmin'], function () {
    return view('admin');
}])->name('admin.home');

// 登陆登出
Route::get('login', 'Rbac\LoginController@showLoginForm')->name('admin.login');
Route::post('login', 'Rbac\LoginController@login')->name('admin.login');
Route::get('logout', 'Rbac\LoginController@logout')->name('admin.logout');
Route::post('logout', 'Rbac\LoginController@logout')->name('admin.logout');

Route::group(['middleware' => ['auth:admin', 'menu', 'authAdmin'], 'namespace' => 'Rbac'], function () {
    //权限管理路由
    Route::get('permission/{cid}/create', ['as' => 'admin.permission.create', 'uses' => 'PermissionController@create']);
    Route::get('permission/manage', ['as' => 'admin.permission.manage', 'uses' => 'PermissionController@index']);
    Route::get('permission/{cid?}', ['as' => 'admin.permission.indexs', 'uses' => 'PermissionController@index']);
    Route::post('permission/index', ['as' => 'admin.permission.indexs', 'uses' => 'PermissionController@index']); //查询
    Route::resource('permission', 'PermissionController', ['names' => ['update' => 'admin.permission.edit', 'store' => 'admin.permission.create']]);
    Route::post('department/{id}/permissions', [
        'as' => 'admin.ajax.department.permissions',
        'uses' => 'PermissionController@getPermissions'
    ]);

    //角色管理路由
    Route::get('role/index', ['as' => 'admin.role.indexs', 'uses' => 'RoleController@index']);
    Route::post('role/index', ['as' => 'admin.role.indexs', 'uses' => 'RoleController@index']);
    Route::resource('role', 'RoleController', ['names' => ['update' => 'admin.role.edit', 'store' => 'admin.role.create']]);
    Route::post('department/{id}/roles', [
        'as' => 'admin.ajax.department.roles',
        'uses' => 'RoleController@getRoles'
    ]);

    //用户管理路由
    Route::get('user/index', ['as' => 'admin.user.indexs', 'uses' => 'UserController@index']);  //用户管理
    Route::post('user/index', ['as' => 'admin.user.indexs', 'uses' => 'UserController@index']);
    Route::post('user/change-pwd', ['as' => 'admin.user.change-pwd', 'uses' => 'UserController@changePwd']); // 修改密码
    Route::resource('user', 'UserController', ['names' => ['update' => 'admin.role.edit', 'store' => 'admin.role.create']]);

    //部门管理路由
    Route::get('department/index', ['as' => 'admin.department.indexs', 'uses' => 'DepartmentController@index']);
    Route::post('department/index', ['as' => 'admin.department.indexs', 'uses' => 'DepartmentController@index']);
    Route::resource('department', 'DepartmentController', ['names' => ['update' => 'admin.department.edit', 'store' => 'admin.department.create']]);
});

/**
 * Load all routes
 */
foreach (File::allFiles(__DIR__ . '/admin') as $partial) {
    require_once $partial->getPathname();
}
