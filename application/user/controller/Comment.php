<?php
/**
 * @author xialeistudio <xialeistudio@gmail.com>
 * @date 2019-04-13
 */

namespace app\user\controller;

use app\user\service\CommentService;
use think\exception\DbException;
use think\Request;

/**
 * 评论
 * Class Comment
 * @package app\user\controller
 */
class Comment extends BaseController
{
    /**
     * 评论列表
     * @return mixed
     * @throws DbException
     */
    public function index()
    {
        $list = CommentService::Factory()->listByUser($this->userId(), 10);
        $this->assign('list', $list);
        $this->assign('page', $list->render());
        return $this->fetch();
    }

    /**
     * 删除评论
     * @param Request $request
     */
    public function delete(Request $request)
    {
        $commentId = $request->param('comment_id');
        CommentService::Factory()->delete($commentId, $this->userId());
        $this->success('删除成功');
    }
}