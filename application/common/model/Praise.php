<?php


namespace app\common\model;


use think\Model;

/**
 * Class Praise
 * @package app\common\model
 * @property integer $post_id
 * @property integer $user_id
 * @property integer $created_at
 */
class Praise extends Model
{
    protected $autoWriteTimestamp = true;
    protected $createTime = 'created_at';
    protected $updateTime = false;

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'post_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }
}