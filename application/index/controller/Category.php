<?php
/**
 * @author xialeistudio <xialeistudio@gmail.com>
 * @date 2019-04-12
 */

namespace app\index\controller;

use app\index\service\CategoryService;

/**
 * 分类
 * Class Category
 * @package app\index\controller
 */
class Category extends BaseController
{
    public function index()
    {
        $list = CategoryService::Factory()->list(10);
        $this->assign('list', $list);
        $this->assign('page', $list->render());
        return $this->fetch();
    }
}