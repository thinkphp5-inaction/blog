<?php
/**
 * @author xialeistudio <xialeistudio@gmail.com>
 * @date 2019-04-12
 */

namespace app\user\controller;

use app\user\service\PraiseService;
use think\exception\DbException;

/**
 * 点赞
 * Class Praise
 * @package app\user\controller
 */
class Praise extends BaseController
{
    /**
     * 点赞列表
     * @return mixed
     * @throws DbException
     */
    public function index()
    {
        $list = PraiseService::Factory()->listByUser($this->userId(), 30);
        $this->assign('list', $list);
        $this->assign('page', $list->render());
        return $this->fetch();
    }
}