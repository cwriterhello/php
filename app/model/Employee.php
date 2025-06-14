<?php

namespace app\model;

use app\common\ArrayUtil;
use app\common\BaseModel;
use app\common\PageResult;
use app\common\Result;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;


class Employee extends BaseModel
{

    protected $table = 'employee';
    // 设置主键
    protected $pk = 'id';
    // 开启自动写入时间戳
    protected $autoWriteTimestamp = true;

    // 可选：自定义创建和更新字段名
    protected $createTime = 'create_time';   // 创建时间字段
    protected $updateTime = 'update_time';   // 更新时间字段


    public function query()
    {
        return  $this->find(2);
    }
    public function selectByPage($page, $pageSize,$name)
    {
        $where = [];
        if ($name) {
            $where[] = ['name', 'like', "%{$name}%"];
        }

        // 查询并分页
        $list = Employee::where($where)
            ->paginate([
                'page' => $page,
                'list_rows' => $pageSize,
            ]);

        // 返回 JSON 格式数据
        $da= [
            'total' => $list->total(),
            'records' => $list->items()
        ];
        return Result::success('员工查询成功', $da);
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
        $requiredFields = [
            'username' => '用户名',
            'name' => '姓名',
            'phone' => '手机号',
            'idNumber' => '身份证号',
            'id' => '员工id',
            'createTime' =>  '创建时间',
            'updateTime' => '更新时间',
            'createUser' => '创建人',
            'updateUser' => '更新人',
        ];

        foreach ($requiredFields as $field => $fieldName) {
            if (empty($data[$field])) {
                return Result::error("{$fieldName}不能为空");
            }
        }

        $data = ArrayUtil::toFields($data);

        try {
            // Create and save the new employee
            $this->where('id', $data['id'])->update($data);
            return Result::success('员工更新成功');
        } catch (\Exception $e) {
            return Result::error('员工更新失败: ' . $e->getMessage());
        }
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
