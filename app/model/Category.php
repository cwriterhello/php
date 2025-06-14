<?php

namespace app\model;

use app\common\Result;
use app\common\BaseModel;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;

class Category extends BaseModel
{

    protected $table = 'category';
    // 设置主键
    protected $pk = 'id';
    // 开启自动写入时间戳
    protected $autoWriteTimestamp = true;

    // 可选：自定义创建和更新字段名
    protected $createTime = 'create_time';   // 创建时间字段
    protected $updateTime = 'update_time';   // 更新时间字段


    public function deleteById($id)
    {
        if  (!$id) {
            return  Result::error('删除失败', '参数错误');
        }
        try {
            $data = $this->destroy($id);
            return  Result::success('删除成功');
        } catch (DataNotFoundException $e) {
            return  Result::error('删除失败', $e->getMessage());
        }
    }
    public function selectByPage($page, $pageSize,$name,$type)
    {
        $where = [];

        if ($name) {
            $where[] = ['name', 'like', "%{$name}%"];
        }

        if ($type !== null) {  // 注意用 !== null 判断，因为 type 可能是 0
            $where[] = ['type', '=', $type];
        }

        // 查询并分页
        $list = Category::where($where)
            ->paginate([
                'page' => $page,
                'list_rows' => $pageSize,
            ]);

        // 返回 JSON 格式数据
        return [
            'total' => $list->total(),
            'records' => $list->items()
        ];
    }

    public function listByType($type)
    {
        try {
            $data = Category::where('type', $type)
                ->select();
        }  catch (DbException $e) {
            return Result::error('查询失败: ' . $e->getMessage());
        }
        return Result::success('查询成功', $data);
    }
    public function add($data)
    {
        if (empty($data['name'])) {
            return Result::error('姓名不能为空');
        }
        if (empty($data['sort'])) {
            return Result::error('排序不能为空');
        }
        if (empty($data['type'])) {
            return Result::error('类型不能为空');
        }

        try {
            // Create and save the new employee

            $this->save($data);
            return Result::success('菜品分类添加成功');
        } catch (\Exception $e) {
            return Result::error('菜品分类添加失败: ' . $e->getMessage());
        }
    }

    public function updateById($data)
    {

        $requiredFields = [
            'name' => '姓名',
            'id' => '员工id',
            'sort' => '排序',
        ];

        foreach ($requiredFields as $field => $fieldName) {
            if (empty($data[$field])) {
                return Result::error("{$fieldName}不能为空");
            }
        }

        try {
            // Create and save the new employee
            $this->where('id', $data['id'])->update($data);
            return Result::success('套餐更新成功');
        } catch (\Exception $e) {
            return Result::error('套餐更新失败: ' . $e->getMessage());
        }
    }

    public function updateStatus($status, $id)
    {
        try {
            $this->where('id', $id)->update(['status' => $status]);
            return Result::success('分类状态更新成功');
        } catch (\Exception $e) {
            return Result::error('分类状态更新失败: ' . $e->getMessage());
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
