<?php


namespace app\common\helper;


class ArrayHelper
{
    public static function filter(array $data, array $keys)
    {
        $result = [];
        foreach ($keys as $key) {
            if (isset($data[$key])) {
                $result[$key] = $data[$key];
            }
        }

        return $result;
    }
}