<?php

namespace app\controller\admin;

use app\common\Result;
use app\model\Category;
use app\model\Dish;
use app\Request;
use think\db\exception\DbException;

class DishController
{

    public function add(Request $request)
    {
        // 接收请求参数
        $data = $request->only(['categoryId', 'description', 'flavors', 'image', 'name', 'price', 'status']);
        $dish = new Dish();
        return $dish->addDish($data);
    }

    /**
     * 分类分页查询
     */
    public function page(Request $request)
    {
        $page = $request->param('page', 1);
        $pageSize = $request->param('pageSize', 10);
        $status = $request->param('status', '');
        $name = $request->param('name', '');
        $category = $request->param('categoryId', '');
        $dish = new Dish();
        return $dish->selectByPage($page, $pageSize, $status, $name, $category);
        //return json($dish->selectByPage($page, $pageSize,$status,$name,$category));
    }

    public function list(Request $request)
    {
        $categoryId = $request->param('categoryId');
        $dish = new Dish();
        return $dish->listByCategory($categoryId);
    }

    public function getById($id)
    {
        $dish = new Dish();
        return $dish->getById($id);
    }

    /**
     * 删除分类
     */
    public function delete(Request $request)
    {
        $id = $request->param('id');
        $category = new Category();
        return $category->deleteById($id);
    }

    /**
     * 修改分类
     */
    public function update(Request $request)
    {
        //return Result::success("ccc",1);
        // Extract specific parameters from the request
        $data = $request->only(['name', 'id', 'price', 'code', 'image', 'description', 'status', 'categoryId', 'flavors']);
        $dish = new Dish();
        return $dish->updateDish($data, $data['id']);
    }


    /**
     * 启用/禁用分类
     */
    public function status($status, Request $request)
    {
        $id = $request->param('id');
        $emp = new Dish();
        return $emp->updateStatus($status, $id);
    }
}
