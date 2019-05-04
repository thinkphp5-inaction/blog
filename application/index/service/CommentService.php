<?php
/**
 * @author xialeistudio <xialeistudio@gmail.com>
 * @date 2019-04-11
 */

namespace app\index\service;


use app\common\model\Comment;
use app\common\model\Post;
use app\common\service\Service;
use think\Db;
use think\Exception;

class CommentService extends Service
{
    /**
     * 发表评论
     * @param int $userId
     * @param int $postId
     * @param string $content
     * @return mixed
     */
    public function publish($userId, $postId, $content)
    {
        return Db::transaction(function () use ($userId, $postId, $content) {
            $post = Post::get(['post_id' => $postId, 'status' => Post::STATUS_VISIBLE]);
            if (empty($post)) {
                throw new Exception('文章不存在');
            }
            $post->comment_count++;
            if (!$post->save()) {
                throw new Exception('评论失败');
            }
            $comment = new Comment();
            $comment->user_id = $userId;
            $comment->post_id = $postId;
            $comment->content = $content;
            if (!$comment->save()) {
                throw new Exception('评论不存在');
            }
        });
    }
}