<?php
/**
 * @author xialeistudio <xialeistudio@gmail.com>
 * @date 2019-04-11
 */

namespace app\common\service;

/**
 * 业务基类
 * Class Service
 * @package app\common\service
 */
class Service
{
    private static $_instances = [];

    /**
     * @return static|mixed
     */
    public static function Factory()
    {
        if (isset(self::$_instances[__CLASS__])) {
            return self::$_instances[__CLASS__];
        }
        self::$_instances[__CLASS__] = new static();
        return self::$_instances[__CLASS__];
    }
}