<?php

namespace app\controller\admin;

use app\common\Result;
use app\model\Order;
use app\Request;

class OrderController
{
    /**
     * 订单统计
     */
    public function statistics()
    {
        return Result::success('操作成功', []);
        $order = new Order();
        $statistics = $order->getOrderStatistics();

        return Result::success('操作成功', $statistics);
    }

    /**
     * 订单条件查询
     */
    public function page(Request $request)
    {
        $page = $request->param('page', 1);
        $pageSize = $request->param('pageSize', 10);
        $number = $request->param('number', '');
        $phone = $request->param('phone', '');
        $beginTime = $request->param('beginTime', '');
        $endTime = $request->param('endTime', '');
        $status = $request->param('status', '');
        $order = new Order();
        return $order->selectByPage($page, $pageSize, $number, $phone, $beginTime, $endTime, $status);
    }

    /**
     * 取消订单
     */
    public function cancel(Request $request)
    {
        $data = $request->post();
        $id = $data['id'] ?? null;
        $cancelReason = $data['cancelReason'] ?? '';

        if (empty($id)) {
            return Result::error('订单ID不能为空');
        }

        $order = new Order();
        $result = $order->updateOrderStatusToCancel($id, $cancelReason);

        return $result;
    }

    /**
     * 获取订单详情
     * @param int $id 订单ID
     * @return array 返回订单详细信息
     */
    public function details(int $id)
    {
        $order = new Order();
        return $order->getOrderDetails($id);

    }
}
