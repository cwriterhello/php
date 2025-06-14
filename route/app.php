<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;
use app\model\DishFlavor;
Route::get('think', function () {
    return json(['code'=>'1'],[],[]);
});

//Route::get('hello/:name', 'index/hello');


Route::get('admin/shop/status',function(){
    return json("dasd");
});
//Route::get('admin/employee/page',function (){
//   return json("dasd");
//});

Route::group('admin/employee/', function() {
    // 员工登录
    Route::post('login', 'admin.EmployeeController/login');

    // 员工退出
    Route::post('logout', 'admin.EmployeeController/logout');

    // 添加新员工
    Route::post('', 'admin.EmployeeController/add');

    // 分页查询员工
    Route::get('page', 'admin.EmployeeController/page');

    // 启用/禁用员工
    Route::post('status/:status', 'admin.EmployeeController/updateStatus')
        ->pattern(['status' => '\d+']);

    // 修改员工信息
    Route::put('', 'admin.EmployeeController/updateInfo');

    // 根据ID获取员工
    Route::get(':id', 'admin.EmployeeController/getById')
        ->pattern(['id' => '\d+']);
})->prefix('admin/');



Route::group('admin/category', function() {

        // 新增分类
        Route::post('', 'admin.CategoryController/save');
        // 分页查询
        Route::get('page', 'admin.CategoryController/page');
        // 删除分类
        Route::delete('', 'admin.CategoryController/delete');
        // 修改分类
        Route::put('', 'admin.CategoryController/update');
        // 启用/禁用分类
        Route::post('status/:status', 'admin.CategoryController/status');
        // 根据类型查询分类列表
        Route::get('list', 'admin.CategoryController/list');

});


Route::group('admin/dish/', function() {

    // 新增分类
    Route::post('', 'admin.DishController/add');
    // 分页查询
    Route::get('page', 'admin.DishController/page');
    // 删除分类
    Route::delete('', 'admin.DishController/delete');
    // 修改分类
    Route::put('', 'admin.DishController/update');
    // 启用/禁用分类
    Route::post('status/:status', 'admin.DishController/status');
    // 根据类型查询分类列表
    Route::get('list', 'admin.DishController/list');
    Route::get(':id', 'admin.DishController/getById');
});

Route::group('admin/common/', function (){
    Route::post('upload', 'admin.Upload/upload');
});

Route::group('admin/setmeal', function (){
    Route::get('page', 'admin.SetmealController/page');
    Route::post('',  'admin.SetmealController/add');
    Route::put('',  'admin.SetmealController/update');
});

