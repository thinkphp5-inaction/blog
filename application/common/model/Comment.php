<?php


namespace app\common\model;


use think\Model;
use think\model\relation\BelongsTo;

/**
 * Class Comment
 * @package app\common\model
 * @property integer $comment_id
 * @property string $content
 * @property integer $created_at
 * @property integer $post_id
 * @property integer $user_id
 */
class Comment extends Model
{
    protected $autoWriteTimestamp = true;
    protected $createTime = 'created_at';
    protected $updateTime = false;

    /**
     * 评论人
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'post_id');
    }
}