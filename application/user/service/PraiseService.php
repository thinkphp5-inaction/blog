<?php
/**
 * @author xialeistudio <xialeistudio@gmail.com>
 * @date 2019-04-12
 */

namespace app\user\service;

use app\common\model\Praise;
use app\common\service\Service;
use think\db\Query;
use think\exception\DbException;
use think\Paginator;

/**
 * 点赞业务
 * Class PraiseService
 * @package app\user\service
 */
class PraiseService extends Service
{
    /**
     * 点赞列表
     * @param int $userId
     * @param int $size
     * @return Paginator
     * @throws DbException
     */
    public function listByUser($userId, $size)
    {
        $model = new Praise();
        $model->with([
            'user',
            'post' => function (Query $query) use ($userId) {
                $query->field('post_id,title');
                $query->where('user_id', $userId);
            }
        ]);
        return $model->paginate($size);
    }
}