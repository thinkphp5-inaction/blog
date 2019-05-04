<?php


namespace app\common\model;


use think\Model;

/**
 * Class Category
 * @package app\common\model
 * @property integer $category_id
 * @property string $name
 * @property integer $status
 * @property integer $posts
 * @property integer $user_id
 */
class Category extends Model
{
    const STATUS_VISIBLE = 1; // 显示
    const STATUS_INVISIBLE = 0; // 隐藏

    public function user()
    {
        return $this->hasOne(User::class, 'user_id', 'user_id');
    }
}