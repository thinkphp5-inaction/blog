<?php
/**
 * @author xialeistudio <xialeistudio@gmail.clom>
 * @date 2019-04-05
 */

namespace app\user\controller;


use app\user\service\CategoryService;
use think\Exception;
use think\Request;

class Category extends BaseController
{
    /**
     * @var CategoryService
     */
    private $categoryService;

    protected function _initialize()
    {
        parent::_initialize();
        $this->categoryService = new CategoryService();
    }

    public function index()
    {
        $list = $this->categoryService->all($this->userId());
        $this->assign('title', '分类列表');
        $this->assign('list', $list);

        return $this->fetch();
    }

    public function add()
    {
        $this->assign('title', '添加分类');
        return $this->fetch();
    }

    public function do_add(Request $request)
    {
        try {
            $data = $request->post();
            if (empty($data['name'])) {
                $this->error('请输入分类名称');
            }
            $this->categoryService->add($this->userId(), $data);
            $this->success('添加成功', 'index');
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

    public function update(Request $request)
    {
        $id = $request->param('id');
        if (empty($id)) {
            $this->error('缺少参数ID');
        }
        $category = $this->categoryService->findByUser($id, $this->userId());
        $this->assign('title', '编辑分类');
        $this->assign('category', $category);

        return $this->fetch();
    }

    public function do_update(Request $request)
    {
        $id = $request->post('id');
        $data = $request->post();
        if (empty($data['name'])) {
            $this->error('请输入分类名称');
        }
        $result = $this->categoryService->update($id, $this->userId(), $data);
        if (!$result) {
            $this->error('编辑失败');
        }
        $this->success('编辑成功', 'index');
    }

    public function delete(Request $request)
    {
        $id = $request->param('id');
        if (empty($id)) {
            $this->error('缺少参数ID');
        }
        $this->categoryService->delete($id, $this->userId());
        $this->success('删除成功');
    }
}