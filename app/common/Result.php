<?php

namespace app\common;

use think\response\Json;

class Result
{
    /**
     * 返回成功响应
     * @param string $message 响应消息
     * @param mixed $data 响应数据（可选）
     * @return array 标准化成功响应格式
     */
    public static function success(string $message = null, $data = null): array
    {
        return  [
            'code' => 1,
            'msg' => $message,
            'data' => $data,
        ];
    }

    /**
     * 返回错误响应
     * @param string $message 错误消息
     * @param int $code 错误码
     * @param mixed $data 错误数据（可选）
     * @return array 标准化错误响应格式
     */
    public static function error(string $message, $data = null): array
    {
        return [
            'code' => 0,
            'msg' => $message,
            'data' => $data,
        ];
    }
}
