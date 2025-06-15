<?php

namespace app\model;

use app\common\ArrayUtil;
use app\common\BaseModel;
use app\common\Result;
use BcMath\Number;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\facade\Db;
use think\Model;

class Dish extends Model
{
    protected $type = [
        'price' => 'decimal:10,2',    // 定义为小数类型，最大10位，保留2位小数
        'created_at' => 'datetime:Y-m-d H:i:s', // 日期格式化
    ];
    protected $table = 'dish';
    // 设置主键list

    // 开启自动写入时间戳
    protected $autoWriteTimestamp = true;

    // 可选：自定义创建和更新字段名
    protected $createTime = 'create_time';   // 创建时间字段
    protected $updateTime = 'update_time';   // 更新时间字段


    public function listByCategory($categoryId)
    {
        $data = Db::name('dish')->where('category_id', $categoryId)->select()->map(function ($item) {
            $item['price'] = (float)$item['price'];
            return $item;
        });
        return Result::success("成功", $data);
    }

    public function selectByPage($page, $pageSize, $status, $name, $categoryId)
    {

        $query = Db::name('dish');

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


    public function addDish($data)
    {

        $dishFlavors = new DishFlavor();
        $flavorsValue = str_replace('\\', '', $data['flavors'][0]['value']);
        $flavorsName = $data['flavors'][0]['name'];
        //移除flavors
        if (isset($data['flavors'])) {
            unset($data['flavors']);
        }
        // 手动确保 categoryId 被转换为 category_id
        if (isset($data['categoryId'])) {
            $data['category_id'] = $data['categoryId'];
            unset($data['categoryId']);
        }

        $data['create_user'] = 1;
        $data['update_user'] = 1;

        try {
            $this->save($data);
            $flavors = [
                'name' => $flavorsName,
                'value' => $flavorsValue,
                'dish_id' => $this->id,
            ];
            $dishFlavors->addFlavor($flavors);
            return json(['code' => 200, 'message' => '添加成功']);
        } catch (\Exception $e) {
            return Result::error('添加失败: ' . $e->getMessage());
        }
    }


    public function addUser($data)
    {
        $data['id_number'] = $data['idNumber'];
        unset($data['idNumber']);

        // Validate required fields
        if (empty($data['username'])) {
            return Result::error('用户名不能为空');
        }
        if (empty($data['name'])) {
            return Result::error('姓名不能为空');
        }
        if (empty($data['phone'])) {
            return Result::error('手机号不能为空');
        }
        if (empty($data['id_number'])) {
            return Result::error('身份证号不能为空');
        }

        $data['create_user'] = 1;
        $data['update_user'] = 1;
        $data['password'] = 123456;
        try {
            // Create and save the new employee

            $this->save($data);
            return Result::success('员工添加成功');
        } catch (\Exception $e) {
            return Result::error('员工添加失败: ' . $e->getMessage());
        }
    }

    public function updateUser($data)
    {
        $data['id_number'] = $data['idNumber'];
        unset($data['idNumber']);

        // Validate required fields
        if (empty($data['username'])) {
            return Result::error('用户名不能为空');
        }
        if (empty($data['name'])) {
            return Result::error('姓名不能为空');
        }
        if (empty($data['phone'])) {
            return Result::error('手机号不能为空');
        }
        if (empty($data['id_number'])) {
            return Result::error('身份证号不能为空');
        }


        try {
            // Create and save the new employee
            $this->updateUser($data);
            return Result::success('员工更新成功');
        } catch (\Exception $e) {
            return Result::error('员工更新失败: ' . $e->getMessage());
        }
    }

    public function updateStatus($status, $id)
    {
        try {
            $this->where('id', $id)->update(['status' => $status]);
            return Result::success('菜品状态更新成功');
        } catch (\Exception $e) {
            return Result::error('菜品状态更新失败: ' . $e->getMessage());
        }
    }

    public function getById($id)
    {
        try {
            $result = $this->find($id);
            if (!$result) {
                return Result::error('未找到对应菜品');
            }
            // 格式化价格字段
            if (isset($result['price'])) {
                $result['price'] = (float)$result['price'];
            }
            return json(['code' => 1, 'msg' => '', 'data' => $result]);
        } catch (\Exception $e) {
            return Result::error('菜品查询失败: ' . $e->getMessage());
        }
    }

    /**
     * 批量删除菜品及其关联数据
     */
    public function deleteByIds($idArray)
    {
        try {
            foreach ($idArray as $id) {
                // 删除与菜品关联的口味
                $dishFlavor = new DishFlavor();
                $dishFlavor->where('dish_id', $id)->delete();

                // 删除菜品本身
                $this->where('id', $id)->delete();
            }

            return Result::success('菜品批量删除成功');
        } catch (\Exception $e) {
            return Result::error('菜品批量删除失败: ' . $e->getMessage());
        }
    }


}
