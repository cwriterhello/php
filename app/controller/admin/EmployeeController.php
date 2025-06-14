<?php

namespace app\controller\admin;

use app\BaseController;
use app\common\Result;
use app\model\Employee;
use app\Request;

class EmployeeController extends BaseController
{

    public function login(Request $request)
    {
        $data = $request->only(['username', 'password']);
        $employee = new Employee();

        try {
            $user = $employee->where('username', $data['username'])->find();
            if ($user && $user->password == $data['password']) {
                return Result::success('登录成功', $user);
            } else {
                return Result::error('用户名或密码错误');
            }
        } catch (\Exception $e) {
            return Result::error('登录失败: ' . $e->getMessage());
        }
    }

    /**
     * 分页查询员工列表
     * @param int $page 当前页码
     * @param int $pageSize 每页显示数量
     * @param string|null $name 员工姓名(可选)
     * @return array|\think\response\Json
     */

    public function page(Request $request)
    {
        $page = $request->param('page', 1);
        $pageSize = $request->param('pageSize', 10);
        $name = $request->param('name', null);


        $employee = new Employee();
        return $employee->selectByPage($page, $pageSize, $name);
    }


    /**
     * 添加员工
     * @param string $username 员工账号
     * @param string $name 员工姓名
     * @param string $phone 员工电话
     * @param string $gender 员工性别
     * @param string $idNumber 员工地址
     * @return array|\think\response\Json
     */
    public function add(Request $request)
    {
        $data = $request->only(['username', 'name', 'phone', 'sex', 'idNumber']);
        $emp = new Employee();
        return $emp->addUser($data);
    }

    public function updateInfo(Request $request)
    {
        $data = $request->only([
            'username',
                'name',
                'phone',
                'sex',
                'idNumber',
                'id',
                'createTime',
                'updateTime',
                'createUser',
                'updateUser']);
        $emp = new Employee();
        return $emp->updateUSer($data);
    }

    public function updateStatus($status, Request $request)
    {
        $userId = $request->param('id');
        $emp = new Employee();
        return $emp->updateStatus($status, $userId);
    }

    public function getById($id)
    {
        $emp = new Employee();
        $result = $emp->getById($id);
        return $result;
    }
}
