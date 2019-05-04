<?php


namespace app\user\service;


use app\common\model\Category;
use app\common\model\Comment;
use app\common\model\Post;
use app\common\model\Praise;
use app\common\service\Service;
use think\Db;
use think\db\Query;
use think\Exception;
use think\exception\DbException;
use think\Paginator;

class PostService extends Service
{
    /**
     * 文章列表
     *
     * @param int $userId
     * @param int $size
     *
     * @return Paginator|mixed
     * @throws DbException
     */
    public function list($userId, $size = 10)
    {
        $model = new Post();
        return $model->where('user_id', $userId)
            ->with(['category'])
            ->field('content', true)
            ->order(['post_id' => 'desc'])
            ->paginate($size);
    }

    /**
     * 发表文章
     *
     * @param       $userId
     * @param array $data
     */
    public function publish($userId, array $data)
    {
        Db::transaction(function () use ($userId, $data) {
            $category = Category::get([
                'user_id' => $userId,
                'category_id' => $data['category_id'],
            ]);
            if (empty($category)) {
                throw new Exception('分类不存在');
            }
            $category->posts++;
            if (!$category->save()) {
                throw new Exception('发表失败');
            }

            $post = new Post();
            $data['user_id'] = $userId;
            $post->data($data);
            if (!$post->save()) {
                throw new Exception('发表失败');
            }
        });
    }

    /**
     * 编辑文章
     *
     * @param       $postId
     * @param       $userId
     * @param array $data
     */
    public function update($postId, $userId, array $data)
    {
        Db::transaction(function () use ($postId, $userId, $data) {
            $post = $this->show($postId, $userId);
            if (empty($post)) {
                throw new Exception('文章不存在');
            }
            if (!empty($data['category_id']) && $data['category_id'] != $post->category_id) {
                // 文章分类修改，需要修正分类文章数
                $oldCategory = Category::get($post->category_id); // 能够发布到该分类，证明是有效且有权限的分类
                $oldCategory->posts--;
                if (!$oldCategory->save()) {
                    throw new Exception('编辑失败');
                }
                $newCategory = Category::get([
                    'user_id' => $userId,
                    'category_id' => $data['category_id'],
                ]); // category_id为外部提交，需要校验
                if (empty($newCategory)) {
                    throw new Exception('分类不存在');
                }
                $newCategory->posts++;
                if (!$newCategory->save()) {
                    throw new Exception('编辑失败');
                }
            }
            $post->data($data);
            if (!$post->save()) {
                throw new Exception('编辑失败');
            }
        });
    }

    /**
     * 查看文章
     *
     * @param $postId
     * @param $userId
     *
     * @return Post
     * @throws Exception
     * @throws DbException
     */
    public function show($postId, $userId)
    {
        $data = Post::get(['post_id' => $postId, 'user_id' => $userId]);
        if (empty($data)) {
            throw new Exception('文章不存在');
        }

        return $data;
    }

    /**
     * 删除文章
     *
     * @param $postId
     * @param $userId
     */
    public function delete($postId, $userId)
    {
        Db::transaction(function () use ($postId, $userId) {
            $post = $this->show($postId, $userId);
            if (!$post->delete()) {
                throw new Exception('删除失败');
            }
            $category = Category::get($post->category_id);
            $category->posts--;
            if (!$category->save()) {
                throw new Exception('删除失败');
            }
            Comment::destroy(['post_id' => $postId]);
            Praise::destroy(['post_id' => $postId]);
        });
    }

    /**
     * 文章数量
     * @param int $userId
     * @return int|string
     */
    public function count($userId)
    {
        $model = new Post();
        return $model->where('user_id', $userId)->count();
    }
}