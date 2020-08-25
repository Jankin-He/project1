<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

route::group(['prefix'=>'admin','namespace'=>'Admin'],function(){
    //后台登录路由
    route::get('login','LoginController@login');
    //处理后台登录路由
    route::post('dologin','LoginController@dologin');

    //加密算法
    route::get('jiami','LoginController@jiami');
});

route::group(['prefix'=>'admin','namespace'=>'Admin','middleware'=>'isLogin'],function(){
    //后台首页路由
    route::get('index','LoginController@index');
    //后台欢迎页
    route::get('welcome','LoginController@welcome');
    //后台退出登录路由
    route::get('logout','LoginController@logout');

    //后台用户模块相关路由
    route::get('/user/del','UserController@delAll');
    route::resource('user','UserController');

    //后台统计页面
    route::get('welcome1','LoginController@welcome1');

    //后台成员列表（静态列表）
    route::get('memberlist','UserController@index');

    //后台会员列表（动态列表）
    route::get('memberlist1','LoginController@memberlist1');

    //后台会员添加
    route::get('memberadd','LoginController@memberadd');

    //后台会员修改
    route::get('memberedit','LoginController@memberedit');

    //图标对应字体
    route::get('unicode','LoginController@unicode');
});