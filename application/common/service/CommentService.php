<?php
/**
 * @author xialeistudio <xialeistudio@gmail.com>
 * @date 2019-04-11
 */

namespace app\common\service;


use app\common\model\Comment;
use PDOStatement;
use think\Collection;
use think\db\exception\DataNotFoundException;
use think\db\exception\ModelNotFoundException;
use think\exception\DbException;

/**
 * 评论
 * Class CommentService
 * @package app\common\service
 */
class CommentService extends Service
{
    /**
     * 获取文章评论列表
     * @param int $postId
     * @return false|PDOStatement|string|Collection
     * @throws DataNotFoundException
     * @throws ModelNotFoundException
     * @throws DbException
     */
    public function all($postId)
    {
        $model = new Comment();
        return $model->where('post_id', $postId)->with(['user'])->select();
    }
}