<?php
namespace app\controller;
use app\BaseController;

class Blog extends BaseController
{
    public function inex()
    {
        return json(['name'=>'blog']);
    }
}