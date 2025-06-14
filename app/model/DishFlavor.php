<?php

namespace app\model;
use app\common\BaseModel;
use app\common\Result;
use think\Request;

class DishFlavor extends BaseModel
{

    protected $table = 'dish_flavor';
    protected $pk = 'id';


    public function addFlavor($data)
    {

//        //return json('dsa');
//        try {
//            $data = $request->only(["name", "value"]);
//            $data['dish_id'] = '33';
//            $id = $this->save($data);
//            return json($id);
//        } catch (\Exception $e) {
//            return json(['code' => 400, 'msg' => '添加失败: ' . $e->getMessage()]);
//        }
            return $this->save($data);
    }
}
