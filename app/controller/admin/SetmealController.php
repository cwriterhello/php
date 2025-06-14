<?php

namespace app\controller\admin;

use app\model\Dish;
use app\model\Setmeal;
use app\Request;

class SetmealController
{
    public function page(Request $request)
    {
        $page = $request->param('page', 1);
        $pageSize = $request->param('pageSize', 10);
        $status = $request->param('status', '');
        $name = $request->param('name', '');
        $category = $request->param('categoryId', '');
        $setmeal  = new Setmeal();
        return $setmeal->selectByPage($page, $pageSize,$status,$name,$category);
    }


    public function add(Request $request)
    {
        //请求出错了：Cannot set properties of undefined (setting 'type')
//        $data = $request->only([
//            'name', 'categoryId', 'price', 'code', 'image', 'description', 'dishList', 'status', 'idType', 'setmealDishes'
//        ]);
//        $setmeal = new Setmeal();
//        return $setmeal->addSetmeal($data);
    }
    public function update(Request $request)
    {
        $data = $request->only([
            'name', 'categoryId', 'price', 'image', 'description','status', 'setmealDishes'
        ]);
        $setmeal = new Setmeal();
        return $setmeal->updateSetmeal($data);
    }
}
