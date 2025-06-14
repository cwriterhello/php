<?php

namespace app\model;

use app\common\ArrayUtil;
use app\common\BaseModel;
use app\common\Result;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\facade\Db;
use think\helper\Arr;

class Setmeal extends BaseModel
{
    protected $table = 'setmeal';
    // 设置主键
    protected $pk = 'id';
    // 开启自动写入时间戳
    protected $autoWriteTimestamp = true;

    // 可选：自定义创建和更新字段名
    protected $createTime = 'create_time';   // 创建时间字段
    protected $updateTime = 'update_time';   // 更新时间字段


    public function selectByPage($page, $pageSize,$status,$name,$categoryId)
    {
        $query = Db::name('setmeal');

        // 精确条件查询
        if ($status !== '') {
            $query->where('status', $status);
        }

        if ($categoryId !== '') {
            $query->where('category_id', $categoryId);
        }

        // 模糊查询
        if ($name !== '') {
            $query->where('name', 'like', '%' . $name . '%');
        }

        // 执行分页查询
        try {
            $list = $query->paginate([
                'list_rows' => $pageSize,
                'page' => $page,
            ]);
            $result = [
                'total' => $list->total(),
                'records' => $list->items()
            ];
            return Result::success('查询成功', $result);
        } catch (DataNotFoundException | ModelNotFoundException $e) {
            // 特定异常处理：数据或模型未找到
            return Result::error('数据未找到: ' . $e->getMessage());
        } catch (\Throwable $e) {
            // 通用异常捕获，防止暴露敏感信息
            return Result::error('查询失败，请稍后重试');
        }
    }

    public function addSetmeal($data)
    {
        return json($data);
//        $dishFlavors = new DishFlavor();
//        $flavorsValue = str_replace('\\', '', $data['flavors'][0]['value']);
//        $flavorsName = $data['flavors'][0]['name'];
//
//        //移除flavors
//        if (isset($data['flavors'])) {
//            unset($data['flavors']);
//        }
//        // 手动确保 categoryId 被转换为 category_id
//        if (isset($data['categoryId'])) {
//            $data['category_id'] = $data['categoryId'];
//            unset($data['categoryId']);
//        }
//
//        $data['create_user'] = 1;
//        $data['update_user'] = 1;
//        return json('dsadsad');
//        try {
//            $this->save($data);
//            $flavors = [
//                'name' => $flavorsName,
//                'value' => $flavorsValue,
//                'dish_id' => $this->id,
//            ];
//            $dishFlavors->addFlavor($flavors);
//            return json(['code'=>200, 'message'=>'添加成功']);
//        } catch (\Exception $e) {
//            return   Result::error('添加失败: ' . $e->getMessage());
//        }
    }

    public function updateSetmeal($data)
    {
        $setmealDish = new SetmealDish();
        $mealDishList = $data['setmealDishes'];
        if (isset($data['setmealDishes'])) {
            unset($data['setmealDishes']);
        }
        $data = ArrayUtil::camelToSnake($data);
        $this->save($data);

        $list = [];
        foreach ($mealDishList as &$dish) {
            $dish = ArrayUtil::camelToSnake($dish);
            // Assuming Dish model exists and has a save method
        }
        return Result::success('套餐更新成功');
    }

    public function updateStatus($status, $userId)
    {
        try {
            $this->where('id', $userId)->update(['status' => $status]);
            return Result::success('员工状态更新成功');
        } catch (\Exception $e) {
            return Result::error('员工状态更新失败: ' . $e->getMessage());
        }
    }

    public function getById($id)
    {
        try {
            $result = $this->find($id);
            return Result::success('员工查询成功', $result);
        } catch (\Exception $e) {
            return Result::error('员工查询失败: ' . $e->getMessage());
        }
    }
}
