<?php

namespace app\model;
use app\common\BaseModel;
class SetmealDish extends BaseModel
{
    protected $table = 'setmeal_dish';
    protected $pk = 'id';
    public function add($data)
    {
         return $this->saveAll($data);
    }
}
