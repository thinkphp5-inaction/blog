<?php


namespace app\common\model;


use think\Model;
use think\model\relation\BelongsTo;

/**
 * Class Post
 * @package app\common\model
 * @property integer $post_id
 * @property string $title
 * @property string $content
 * @property integer $status
 * @property integer $top
 * @property integer $sort
 * @property integer $praise_count
 * @property integer $comment_count
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $category_id
 * @property integer $user_id
 */
class Post extends Model
{
    const STATUS_DRAFT = 1; // 草稿
    const STATUS_VISIBLE = 2; // 显示
    const STATUS_INVISIBLE = 3; // 隐藏
    protected $autoWriteTimestamp = true;
    protected $createTime = 'created_at';
    protected $updateTime = 'updated_at';

    /**
     * 分类
     *
     * @return BelongsTo
     */
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'category_id');
    }

    /**
     * 作者
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class, 'post_id', 'post_id');
    }
}