<?php
/**
 * @author xialeistudio <xialeistudio@gmail.com>
 * @date 2019-04-11
 */

namespace app\index\service;


use app\common\model\Category;
use app\common\service\Service;
use think\exception\DbException;
use think\Paginator;

class CategoryService extends Service
{
    /**
     * 分类列表
     * @param int $size
     * @return Paginator
     * @throws DbException
     */
    public function list($size = 10)
    {
        $model = new Category();
        $model->where('status', Category::STATUS_VISIBLE);
        $model->order(['posts' => 'desc', 'category_id' => 'desc']);
        return $model->paginate($size);
    }
}