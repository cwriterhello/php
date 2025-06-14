<?php

namespace app\common;

use think\Model;

class BaseModel extends Model
{
    /**
     * 将当前模型对象转换为数组（自动转小驼峰）
     * @access public
     * @param array $append 追加的属性
     * @param bool $visible 是否限制可见属性
     * @return array
     */
    public function toArray(array $append = [], $visible = null): array
    {
        $array = parent::toArray($append, $visible);
        return $this->convertKeysToCamel($array);
    }

    /**
     * 将数组键名转换为小驼峰
     * @param array $array
     * @return array
     */
    protected function convertKeysToCamel(array $array): array
    {
        $result = [];
        foreach ($array as $key => $value) {
            $newKey = $this->toCamelCase($key);

            if (is_array($value)) {
                $value = $this->convertKeysToCamel($value);
            }

            $result[$newKey] = $value;
        }
        return $result;
    }

    /**
     * 下划线转小驼峰
     * @param string $string
     * @return string
     */
    protected function toCamelCase(string $string): string
    {
        return lcfirst(str_replace('_', '', ucwords($string, '_')));
    }
}
