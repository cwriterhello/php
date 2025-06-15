<?php

namespace app\middleware;

use app\common\Result;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use think\facade\Request;
use think\Response;

class JwtAuth
{
    public function handle($request, \Closure $next)
    {
        // 排除登录接口
        if ($request->pathinfo() === 'admin/employee/login') {
            return $next($request);
        }

        // 获取 Token
        $token = $request->header('token');

        if (!$token){
            return Response::create([
                'code' => 401,
                'message' => '缺少 Token'
            ], 'json', 401);
        }
        $key = "yoursecretkey";

        try {
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            // 将用户信息写入请求对象，供后续控制器使用
            $request->user = (array)$decoded;
        } catch (\Exception $e) {
            return Response::create([
                'code' => 401,
                'message' => '无效的 Token'
            ], 'json', 401);
        }

        return $next($request);
    }
}
