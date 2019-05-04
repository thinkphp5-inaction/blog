<?php


namespace app\index\controller;


use app\common\service\UserService;
use think\Controller;
use think\Exception;
use think\Request;
use think\Validate;

class User extends BaseController
{
    const SESSION_AUTH_CALLBACK = 'auth.callback';
    /**
     * @var UserService
     */
    private $service;

    protected function _initialize()
    {
        $this->service = new UserService();
    }

    public function signup()
    {
        $this->assign('title', '用户注册');
        return $this->fetch();
    }

    public function do_signup(Request $request)
    {
        $validator = new Validate([
            'captcha' => 'require|captcha',
            'username' => 'require|alphaNum|max:40',
            'password' => 'require',
        ]);
        if (!$validator->check($request->post())) {
            $this->error($validator->getError());
        }
        try {
            $username = $request->post('username');
            $password = $request->post('password');
            if (!$this->service->signup($username, $password)) {
                $this->error('注册失败');
            }
            $this->success('注册成功!', 'signin');
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

    public function signin()
    {
        $this->assign('title', '用户登录');
        return $this->fetch();
    }

    public function do_signin(Request $request)
    {
        $validator = new Validate([
            'username' => 'require|alphaNum|max:40',
            'password' => 'require',
        ]);
        if (!$validator->check($request->post())) {
            $this->error($validator->getError());
        }
        try {
            $username = $request->post('username');
            $password = $request->post('password');
            $this->service->signin($username, $password);
            $this->success('登录成功!', '/user');
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }
}