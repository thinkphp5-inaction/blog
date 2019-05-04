<?php
/**
 * @author xialeistudio <xialeistudio@gmail.clom>
 * @date 2019-04-05
 */

namespace app\user\controller;

use app\user\service\CategoryService;
use app\user\service\PostService;
use app\user\service\UserService;
use think\Request;
use think\Validate;

class Index extends BaseController
{
    /**
     * @var CategoryService
     */
    private $categoryService;

    /**
     * @var PostService
     */
    private $postService;

    protected function _initialize()
    {
        parent::_initialize();
        $this->categoryService = new CategoryService();
        $this->postService = new PostService();
    }

    public function index()
    {
        $categoryCount = $this->categoryService->count($this->userId());
        $postCount = $this->postService->count($this->userId());
        $this->assign('title', '个人中心');
        $this->assign('category_count', $categoryCount);
        $this->assign('post_count', $postCount);
        return $this->fetch();
    }

    public function logout()
    {
        $this->userService->logout();
        $this->redirect('index/user/signin');
    }

    public function changepwd()
    {
        return $this->fetch();
    }

    public function do_changepwd(Request $request)
    {
        $validator = new Validate([
            'old_pwd' => 'require',
            'new_pwd' => 'require',
            'new_pwd_confirm' => 'require|confirm:new_pwd',
        ]);
        if (!$validator->check($request->post())) {
            $this->error($validator->getError());
        }
        $oldPwd = $request->post('old_pwd');
        $newPwd = $request->post('new_pwd');
        $service = new UserService();
        $service->changePassword($this->userId(), $oldPwd, $newPwd);
        $this->success('修改成功');
    }
}