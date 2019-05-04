<?php
/**
 * @author xialeistudio <xialeistudio@gmail.clom>
 * @date 2019-04-05
 */

namespace app\user\controller;


use app\common\service\UserService;
use think\Controller;

class BaseController extends Controller
{
    /**
     * @var UserService
     */
    protected $userService;

    protected function _initialize()
    {
        $this->userService = new UserService();
        if ($this->userService->isGuest()) {
            $this->redirect('user/signin');
        }
    }

    protected function userId()
    {
        return $this->userService->userId();
    }
}