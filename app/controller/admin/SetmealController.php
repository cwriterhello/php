<?php

namespace app\controller\admin;

use app\common\Result;
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
        $setmeal = new Setmeal();
        return $setmeal->selectByPage($page, $pageSize, $status, $name, $category);
    }


    public function add(Request $request)
    {
        $data = $request->only([
            'name', 'categoryId', 'price', 'image', 'description', 'status', 'setmealDishes'
        ]);
        $setmeal = new Setmeal();
        return $setmeal->addSetmeal($data);
    }

    public function update(Request $request)
    {
        $data = $request->only(['id',
            'name', 'categoryId', 'price', 'image', 'description', 'status', 'setmealDishes'
        ]);
        $setmeal = new Setmeal();
        return $setmeal->updateSetmeal($data);
    }

    public function getById($id)
    {
        $setmeal = Setmeal::find($id);
        if ($setmeal && isset($setmeal['price'])) {
            $setmeal['price'] = (float)$setmeal['price'];
        }
        return json(['code' => 1, 'msg' => '', 'data' => $setmeal]);
    }

    public function delete(Request $request)
    {
        $ids = $request->param('ids'); // 获取逗号分隔的ID字符串
        $idArray = explode(',', $ids); // 转换为数组

        $setmeal = new Setmeal();
        $result = $setmeal->deleteSetmeals($idArray);

        return $result;
    }

    public function updateStatus($status, $id)
{
    $setmeal = new Setmeal();
    $result = $setmeal->updateStatus($status, $id);

    return $result;
}

}
