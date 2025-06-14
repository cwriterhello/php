<?php
namespace app\controller;

use app\BaseController;
use think\facade\Request;

class User extends BaseController
{
    protected $middleware = [
        'app\middleware\Check' => ['except' => ['login']],
    ];

    public function login()
    {

        $username = request()->param('username');
        $password = request()->param('password');
        if($username == 'admin' && $password == '123456'){
            session('username',$username);
            session('password',$password);
            session('isLogin',true);
            return json(['code'=>200,'message'=>'登录成功']);
        }else{
            return json(['code'=>400,'message'=>'登录失败']);
        }
    }




     public function llogin(Request $request)
     {
         // 获取输入数据
         $username = $request->param('username');
         $password = $request->param('password');

         // 验证输入
         if (empty($username) || empty($password)) {
             return json([
                 'code' => 400,
                 'message' => '用户名和密码不能为空'
             ], 400);
         }

         // 查询用户 (示例，请根据实际数据库结构调整)
         $user = User::where('username', $username)->find();

         // 验证用户是否存在和密码是否正确
         if (!$user || !password_verify($password, $user->password)) {
             return json([
                 'code' => 401,
                 'message' => '用户名或密码错误'
             ], 401);
         }

         // 设置session
         Session::set('user_id', $user->id);
         Session::set('username', $user->username);

         // 设置cookie (可选，用于延长session有效期)
         cookie('login_token', md5($user->id . $user->username . time()), 3600 * 24 * 7);

         return json([
             'code' => 200,
             'message' => '登录成功',
             'data' => [
                 'user_id' => $user->id,
                 'username' => $user->username
             ]
         ]);
     }

     /**
      * 登出接口
      * @return Response
      */
     public function logout()
     {
         // 清除session
         Session::delete('user_id');
         Session::delete('username');

         // 清除cookie
         cookie('login_token', null);

         return json([
             'code' => 200,
             'message' => '登出成功'
         ]);
     }

     /**
      * 检查登录状态
      * @return Response
      */
     public function checkLogin()
     {
         if (Session::has('user_id')) {
             return json([
                 'code' => 200,
                 'is_login' => true,
                 'user_info' => [
                     'user_id' => Session::get('user_id'),
                     'username' => Session::get('username')
                 ]
             ]);
         }

         return json([
             'code' => 200,
             'is_login' => false
         ]);
     }

}
