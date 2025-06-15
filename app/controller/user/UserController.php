<?php

namespace app\controller\user;

use app\controller\User;
use app\Request;

class UserController
{
    public function login(Request $request)
    {
         $code = $request->param('code');
         $user = new User($code);

    }
}
