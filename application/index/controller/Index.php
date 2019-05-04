<?php

namespace app\index\controller;

use app\index\service\PostService;
use think\Controller;
use think\Request;

class Index extends BaseController
{
    public function index(PostService $service, Request $request)
    {
        $list = $service->list(10, $request->get('category_id'), $request->get('uid'));
        $page = $list->render();
        $this->assign('list', $list);
        $this->assign('page', $page);

        return $this->fetch();
    }
}
