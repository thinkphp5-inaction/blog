<?php
/**
 * @author xialeistudio <xialeistudio@gmail.clom>
 * @date 2019-04-05
 */

namespace app\user\controller;

use app\user\service\CategoryService;
use app\user\service\PostService;
use think\Request;
use think\Validate;

/**
 * 文章控制器
 * Class Post
 * @package app\user\controller
 */
class Post extends BaseController
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

    public function index(Request $request)
    {
        $list = $this->postService->list($this->userId(), $request->get('size', 30));
        $this->assign('list', $list);
        return $this->fetch();
    }

    public function publish()
    {
        $categories = $this->categoryService->all($this->userId(), \app\common\model\Category::STATUS_VISIBLE);
        $this->assign('categories', $categories);
        return $this->fetch();
    }

    public function do_publish(Request $request)
    {
        $validator = new Validate([
            'title' => 'require|max:100',
            'content' => 'require',
            'category_id' => 'require'
        ]);
        if (!$validator->check($request->post())) {
            $this->error($validator->getError());
        }
        $this->postService->publish($this->userId(), $request->post());
        $this->success('保存成功', 'index');
    }

    public function update(Request $request)
    {
        $id = $request->param('id');
        $data = $this->postService->show($id, $this->userId());
        $categories = $this->categoryService->all($this->userId(), \app\common\model\Category::STATUS_VISIBLE);

        $this->assign('categories', $categories);
        $this->assign('post', $data);
        return $this->fetch();
    }

    public function do_update(Request $request)
    {
        $validator = new Validate([
            'title' => 'require|max:100',
            'content' => 'require',
            'category_id' => 'require'
        ]);
        if (!$validator->check($request->post())) {
            $this->error($validator->getError());
        }
        $id = $request->post('post_id');
        $data = $request->post();
        $data['top'] = $data['top'] ?? 0;
        $this->postService->update($id, $this->userId(), $data);
        $this->success('编辑成功', 'index');
    }

    public function delete(Request $request)
    {
        $id = $request->get('id');
        $this->postService->delete($id, $this->userId());
        $this->success('删除成功', 'index');
    }
}