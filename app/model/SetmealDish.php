<?php

namespace app\model;
use app\common\BaseModel;
use think\Model;

class SetmealDish extends Model
{
    protected $table = 'setmeal_dish';
    protected $pk = 'id';
    public function add($data)
    {
         return $this->saveAll($data);
    }
}
