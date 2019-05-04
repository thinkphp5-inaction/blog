<?php


namespace app\user\service;

use app\common\helper\ArrayHelper;
use app\common\model\Category;
use app\common\model\Comment;
use app\common\model\Post;
use app\common\model\Praise;
use app\common\service\Service;
use think\Db;
use think\Exception;
use think\exception\DbException;

/**
 * 文章分类
 * Class CategoryService
 *
 * @package app\user\service
 */
class CategoryService extends Service
{
    /**
     * 用户所有分类
     *
     * @param $userId
     *
     * @param null $status
     * @return false|static[]
     * @throws DbException
     */
    public function all($userId, $status = null)
    {
        $model = new Category();
        $model->where(['user_id' => $userId]);
        if (isset($status)) {
            $model->where('status', $status);
        }
        return $model->select();
    }

    /**
     * 根据分类ID和用户ID查找分类
     * @param int $id
     * @param int $userId
     * @return Category|null
     * @throws DbException
     */
    public function findByUser($id, $userId)
    {
        return Category::get(['category_id' => $id, 'user_id' => $userId]);
    }

    /**
     * 添加分类
     *
     * @param $userId
     *
     * @param array $data
     * @return false|int
     * @throws DbException
     * @throws Exception
     */
    public function add($userId, array $data)
    {
        if (Category::get(['name' => $data['name'], 'user_id' => $userId])) {
            throw new Exception('分类已存在');
        }
        $data = ArrayHelper::filter($data, ['name', 'status']);
        $category = new Category();
        $category->data($data);
        $category->user_id = $userId;

        return $category->save();
    }

    /**
     * 编辑分类
     *
     * @param       $categoryId
     * @param       $userId
     * @param array $data
     *
     * @return false|int
     * @throws Exception
     * @throws DbException
     */
    public function update($categoryId, $userId, array $data)
    {
        $category = Category::get(['category_id' => $categoryId, 'user_id' => $userId]);
        if (empty($category)) {
            throw new Exception('分类不存在');
        }
        $data = ArrayHelper::filter($data, ['name', 'status']);
        $category->data($data);

        return $category->save();
    }

    /**
     * 删除文章
     * @param $categoryId
     * @param $userId
     */
    public function delete($categoryId, $userId)
    {
        Db::transaction(function () use ($categoryId, $userId) {
            $category = Category::get(['category_id' => $categoryId, 'user_id' => $userId]);
            if (empty($category)) {
                throw new Exception('分类删除失败');
            }
            if ($category->posts > 0) {
                throw new Exception('该分类下有文章，不可以删除');
            }
            if (!$category->delete()) {
                throw new Exception('分类删除失败');
            }
            $post = new Post();
            $postIds = $post->where('category_id', $categoryId)->column('post_id');
            if (empty($postIds)) {
                return;
            }
            // 删除文章
            Post::destroy($postIds);
            // 删除评论
            Comment::destroy(['post_id' => $postIds]);
            // 删除
            Praise::destroy(['post_id' => $postIds]);
        });
    }

    /**
     * 用户分类数量
     * @param int $userId
     * @return int|string
     */
    public function count($userId)
    {
        $model = new Category();
        return $model->where('user_id', $userId)->count();
    }
}