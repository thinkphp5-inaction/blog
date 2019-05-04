<?php


namespace app\index\controller;


use app\common\service\CommentService;
use app\index\service\PostService;
use think\Request;
use think\Validate;

/**
 * 文章控制器
 * Class Post
 *
 * @package app\index\controller
 */
class Post extends BaseController
{
    /**
     * @var PostService
     */
    private $postService;
    /**
     * @var CommentService
     */
    private $commentService;

    protected function _initialize()
    {
        parent::_initialize();
        $this->postService = new PostService();
        $this->commentService = new CommentService();
    }

    public function show(Request $request)
    {
        $id = $request->param('id');
        if (empty($id)) {
            $this->error('您的请求有误');
        }
        $post = $this->postService->show($id, $this->userId());
        $comment_list = $this->commentService->all($id);

        $this->assign('login_url', url('user/signin'));
        $this->assign('post', $post);
        $this->assign('comment_list', $comment_list);
        return $this->fetch();
    }

    public function praise(Request $request)
    {
        $id = $request->param('id');
        if (empty($id)) {
            $this->error('您的请求有误');
        }
        if ($this->isGuest()) {
            $this->error('请登录', url('index/user/signin'));
        }
        $this->postService->praise($this->userId(), $id);
        $this->success('点赞成功!');
    }

    public function comment(Request $request)
    {
        if ($this->isGuest()) {
            $this->error('请登录', url('index/user/signin'));
        }
        $validator = new Validate([
            'content' => 'require'
        ]);
        if (!$validator->check($request->post())) {
            $this->error($validator->getError());
        }
        \app\index\service\CommentService::Factory()->publish($this->userId(), $request->param('id'), $request->post('content'));
        $this->success('评论成功');
    }
}