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
});

/**
 *  Auth
 */
Auth::routes();

Route::get('/home', 'WebController@index');


/**
 * Load all routes
 */
foreach (File::allFiles(__DIR__ . '/web') as $partial) {
    require_once $partial->getPathname();
}
