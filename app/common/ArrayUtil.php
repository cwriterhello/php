<?php

namespace app\common;

class ArrayUtil
{

    /**
     * 将数组中的所有 key 从驼峰命名转为下划线命名
     *
     * @param array $array 原始数组
     * @return array 转换后的数组
     */
    public static function camelToSnake(array $array): array
    {
        $converted = [];

        foreach ($array as $key => $value) {
            // 将 key 从驼峰转为下划线格式
            $newKey = self::convertKey($key);

            // 如果 value 是数组，递归处理
            if (is_array($value)) {
                $value = self::camelToSnake($value);
            }

            $converted[$newKey] = $value;
        }

        return $converted;
    }

    /**
     * 将单个 key 从驼峰命名转为下划线命名
     *
     * @param string $key
     * @return string
     */
    private static function convertKey(string $key): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $key));
    }
}
