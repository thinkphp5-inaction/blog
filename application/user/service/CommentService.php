<?php
/**
 * @author xialeistudio <xialeistudio@gmail.com>
 * @date 2019-04-13
 */

namespace app\user\service;

use app\common\model\Comment;
use app\common\model\Post;
use app\common\service\Service;
use think\Db;
use think\db\Query;
use think\Exception;
use think\exception\DbException;
use think\Paginator;

/**
 * 评论业务
 * Class CommentService
 * @package app\user\service
 */
class CommentService extends Service
{
    /**
     * 评论列表
     * @param int $userId
     * @param int $size
     * @return Paginator
     * @throws DbException
     */
    public function listByUser($userId, $size)
    {
        $model = new Comment();
        $model->with([
            'user',
            'post' => function (Query $query) use ($userId) {
                $query->field('post_id,title');
                $query->where('user_id', $userId);
            }
        ]);
        return $model->paginate($size);
    }

    /**
     * 删除评论
     * @param int $commentId
     * @param int $userId
     */
    public function delete($commentId, $userId)
    {
        Db::transaction(function () use ($userId, $commentId) {
            $comment = Comment::get(['comment_id' => $commentId]);
            if (empty($comment)) {
                throw new Exception('您无权操作');
            }
            if (!$comment->delete()) {
                throw new Exception('删除失败');
            }
            $post = Post::get(['post_id' => $comment->post_id, 'user_id' => $userId]);
            if (empty($post)) {
                throw new Exception('您无权操作');
            }
            $post->comment_count--;
            if (!$post->save()) {
                throw new Exception('删除失败');
            }
        });
    }
}