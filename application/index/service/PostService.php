<?php


namespace app\index\service;

use app\common\model\Post;
use app\common\model\Praise;
use app\common\service\Service;
use think\Db;
use think\db\Query;
use think\Exception;
use think\exception\DbException;
use think\Paginator;

/**
 * 文章业务
 * Class PostService
 *
 * @package app\index\service
 */
class PostService extends Service
{
    /**
     * 文章列表
     *
     * @param int $size
     *
     * @param int $categoryId
     * @param int $userId
     * @return Paginator
     * @throws DbException
     */
    public function list($size = 10, $categoryId = 0, $userId = 0)
    {
        $model = new Post();
        $query = $model->where('status', Post::STATUS_VISIBLE)
            ->with(['user', 'category'])
            ->order(['top' => 'desc', 'sort' => 'desc', 'post_id' => 'desc']);
        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }
        if ($userId) {
            $query->where('user_id', $userId);
        }
        return $query->paginate($size);
    }

    /**
     * 文章数量
     *
     * @return int|string
     */
    public function count()
    {
        $model = new Post();

        return $model->where('status', Post::STATUS_VISIBLE)->count();
    }

    /**
     * 文章详情
     * @param int $id
     * @param int $userId
     * @return Post|null
     * @throws DbException
     * @throws Exception
     */
    public function show($id, $userId = 0)
    {
        $model = new Post();
        /** @var Post $data */
        $data = $model->where('post_id', $id)->where('status', Post::STATUS_VISIBLE)->with(['user', 'category'])->find();
        if (empty($data)) {
            throw new Exception('文章不存在');
        }
        if (empty($userId) && $data->status != Post::STATUS_VISIBLE) {
            throw new Exception('文章不存在');
        }
        return $data;
    }

    /**
     * 点赞
     * @param $userId
     * @param $postId
     * @return mixed
     * @throws DbException
     * @throws Exception
     */
    public function praise($userId, $postId)
    {
        $praise = Praise::get(['user_id' => $userId, 'post_id' => $postId]);
        if (!empty($praise)) {
            throw new Exception('您已经赞过啦!');
        }
        return Db::transaction(function () use ($userId, $postId) {
            $praise = new Praise();
            $praise->user_id = $userId;
            $praise->post_id = $postId;
            if (!$praise->save()) {
                throw new Exception('点赞失败');
            }
            $post = Post::get(['post_id' => $postId, 'status' => Post::STATUS_VISIBLE]);
            $post->praise_count++;
            if (!$post->save()) {
                throw new Exception('点赞失败');
            }
        });
    }
}