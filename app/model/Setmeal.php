<?php

namespace app\model;

use app\common\ArrayUtil;
use app\common\BaseModel;
use app\common\Result;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\facade\Db;
use think\helper\Arr;
use think\Model;

class Setmeal extends Model
{
    protected $table = 'setmeal';
    // 设置主键
    protected $pk = 'id';
    // 开启自动写入时间戳
    protected $autoWriteTimestamp = true;

    // 可选：自定义创建和更新字段名
    protected $createTime = 'create_time';   // 创建时间字段
    protected $updateTime = 'update_time';   // 更新时间字段


    public function selectByPage($page, $pageSize, $status, $name, $categoryId)
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
        } catch (DataNotFoundException|ModelNotFoundException $e) {
            // 特定异常处理：数据或模型未找到
            return Result::error('数据未找到: ' . $e->getMessage());
        } catch (\Throwable $e) {
            // 通用异常捕获，防止暴露敏感信息
            return Result::error('查询失败，请稍后重试');
        }
    }

    public function addSetmeal($data)
    {
        // 初始化 SetmealDish 模型实例
        $setmealDish = new SetmealDish();

        // 获取套餐关联的菜品列表
        $mealDishList = $data['setmealDishes'];

        // 从数据中移除 setmealDishes 键，避免干扰后续处理
        if (isset($data['setmealDishes'])) {
            unset($data['setmealDishes']);
        }

        // 将数据键名从驼峰命名转换为下划线命名
        $data = ArrayUtil::camelToSnake($data);

        // 更新套餐基本信息
        try {
            $this->save($data);
        } catch (\Exception $e) {
            return Result::error('套餐添加失败: ' . $e->getMessage());
        }

        // 使用数据中的 ID 作为套餐 ID
        $setmealId = $this->id;

        // 准备新的套餐菜品关联数据
        $list = [];
        foreach ($mealDishList as &$dish) {
            // 将菜品数据键名从驼峰命名转换为下划线命名
            $dish = ArrayUtil::camelToSnake($dish);

            // 设置菜品与套餐的关联 ID
            $dish['setmeal_id'] = $setmealId; // 使用明确的 setmealId

            // 将处理后的菜品数据添加到列表中
            $list[] = $dish;
        }

        try {// 批量保存新的套餐菜品关联数据
            $setmealDish->saveAll($list);
        } catch (\Exception $e) {
            return Result::error('套餐菜品关联添加失败: ' . $e->getMessage());
        }

        // 返回成功响应
        return Result::success('套餐更新成功');
    }
    /**
     * 更新套餐信息及关联的菜品数据
     *
     * @param array $data 包含套餐信息和关联菜品的数据
     * @return array 返回操作结果
     */
    public
    function updateSetmeal($data)
    {
        // 初始化 SetmealDish 模型实例
        $setmealDish = new SetmealDish();

        // 获取套餐关联的菜品列表
        $mealDishList = $data['setmealDishes'];

        // 从数据中移除 setmealDishes 键，避免干扰后续处理
        if (isset($data['setmealDishes'])) {
            unset($data['setmealDishes']);
        }

        // 将数据键名从驼峰命名转换为下划线命名
        $data = ArrayUtil::camelToSnake($data);

        // 更新套餐基本信息
        try {
            $this->where('id', $data['id'])->update($data);
        } catch (\Exception $e) {
            return Result::error('套餐更新失败: ' . $e->getMessage());
        }

        // 使用数据中的 ID 作为套餐 ID
        $setmealId = $data['id'];

        // 准备新的套餐菜品关联数据
        $list = [];
        foreach ($mealDishList as &$dish) {
            // 将菜品数据键名从驼峰命名转换为下划线命名
            $dish = ArrayUtil::camelToSnake($dish);

            // 设置菜品与套餐的关联 ID
            $dish['setmeal_id'] = $setmealId; // 使用明确的 setmealId

            // 将处理后的菜品数据添加到列表中
            $list[] = $dish;
        }

        // 删除原有套餐菜品关联数据
        $setmealDish->where('setmeal_id', $setmealId)->delete(); // 清除原有套餐菜品关联

        // 批量保存新的套餐菜品关联数据
        $setmealDish->saveAll($list);

        // 返回成功响应
        return Result::success('套餐更新成功');
    }

    public function updateStatus($status, $id)
{
    if (empty($id)) {
        return Result::error('无效的套餐ID');
    }

    try {
        // 检查是否存在该套餐
        $existingSetmeal = $this->find($id);
        if (!$existingSetmeal) {
            return Result::error('套餐不存在');
        }

        // 更新状态
        $this->where('id', $id)->update(['status' => $status]);

        return Result::success('套餐状态更新成功');
    } catch (\Exception $e) {
        return Result::error('套餐状态更新失败: ' . $e->getMessage());
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

    public function deleteSetmeals($idArray)
{
    try {
        // 删除与套餐相关的菜品
        $setmealDish = new SetmealDish();
        $setmealDish->where('setmeal_id', 'in', $idArray)->delete();

        // 删除套餐本身
        $this->where('id', 'in', $idArray)->delete();

        return Result::success('套餐删除成功');
    } catch (\Exception $e) {
        return Result::error('套餐删除失败: ' . $e->getMessage());
    }
}

}
