<?php

namespace app\controller\admin;

use app\common\Result;
use app\model\Category;
use app\model\Employee;
use app\Request;

class CategoryController
{

    /**
     * 新增分类
     */


    public function save(Request $request)
    {
        $data = $request->only(['name', 'sort','type']);
        $category = new Category();
        return $category->add($data);
    }

    /**
     * 分类分页查询
     */
    public function page(Request $request)
    {
        $page = $request->param('page', 1);
        $pageSize = $request->param('pageSize', 10);
        $name = $request->param('name', null);
        $type = $request->param('type', null);

        $category = new Category();
        $result = $category->selectByPage($page, $pageSize, $name, $type);
        return Result::success('查询成功', $result);
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
        $data = $request->only(['id', 'name', 'sort']);
        $category = new Category();
        return  $category->updateById($data);
    }

    /**
     * 启用/禁用分类
     */
    public function status($status, Request $request)
    {
        $id = $request->param('id');
        $category = new Category();
        return $category->updateStatus($status, $id);
    }

    public function list(Request $request)
    {
        $typeId  = $request->param('type');
        $category = new Category();
        return $category->listByType($typeId);
        //return json($category->listByType($request));
    }
}
