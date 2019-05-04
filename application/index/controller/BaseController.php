<?php
/**
 * @author xialeistudio <xialeistudio@gmail.com>
 * @date 2019-04-11
 */

namespace app\index\controller;


use app\common\service\UserService;
use think\Controller;

class BaseController extends Controller
{
    /**
     * @var UserService
     */
    private $userService;

    protected function _initialize()
    {
        parent::_initialize();
        $this->userService = new UserService();
    }

    protected function userId()
    {
        return $this->userService->userId();
    }

    protected function isGuest()
    {
        return $this->userService->isGuest();
    }
}