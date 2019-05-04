<?php
/**
 * @author xialeistudio <xialeistudio@gmail.com>
 * @date 2019-04-11
 */

namespace app\user\service;


use app\common\model\User;
use app\common\service\Service;
use think\Exception;
use think\exception\DbException;

/**
 * Class UserService
 * @package app\user\service
 */
class UserService extends Service
{
    /**
     * 修改密码
     * @param int $userId
     * @param int $oldPwd
     * @param int $newPwd
     * @throws Exception
     * @throws DbException
     */
    public function changePassword($userId, $oldPwd, $newPwd)
    {
        $user = User::get(['user_id' => $userId]);
        if (empty($user)) {
            throw new Exception('用户不存在');
        }
        if (!password_verify($oldPwd, $user->password)) {
            throw new Exception('当前密码错误');
        }
        $user->password = password_hash($newPwd, PASSWORD_DEFAULT);
        if (!$user->save()) {
            throw new Exception('修改失败');
        }
    }
}