<?php
// app/middleware/AuthCheck.php
namespace app\middleware;

use think\facade\Session;

class Check
{
    public function handle($request, \Closure $next)
    {
        // 排除登录接口
        if ($request->pathinfo() == 'login') {
            return $next($request);
        }
        
        // 检查session
        if (!Session::has('user_id')) {
            return json([
                'code' => 401,
                'message' => '未登录，请先登录'
            ], 401);
        }
        
        return $next($request);
    }
}
?>