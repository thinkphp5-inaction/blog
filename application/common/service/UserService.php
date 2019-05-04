<?php


namespace app\common\service;

use app\common\helper\ArrayHelper;
use app\common\model\User;
use think\Exception;
use think\exception\DbException;

/**
 * 用户业务类
 * Class UserService
 *
 * @package app\common\service
 */
class UserService extends Service
{
    const SESSION_KEY = 'user';

    /**
     * 修改资料
     * @param       $userId
     * @param array $data
     *
     * @return false|int
     * @throws Exception
     * @throws DbException
     */
    public function post($userId, array $data)
    {
        $data = ArrayHelper::filter($data, ['password', 'realname', 'email']);
        if (!empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }
        $user = User::get($userId);
        if (empty($user)) {
            throw new Exception('用户不存在');
        }
        $user->data($data);

        return $user->save();
    }

    /**
     * 创建账号
     *
     * @param string $username
     * @param string $password
     *
     * @return int|string
     * @throws Exception
     * @throws DbException
     */
    public function signup($username, $password)
    {
        // 检查账号是否存在
        $user = User::get(['username' => $username]);
        if (!empty($user)) {
            throw new Exception('用户名已存在');
        }
        $password = password_hash($password, PASSWORD_DEFAULT);
        $user = new User();
        $user->data(['username' => $username, 'password' => $password]);

        return $user->save();
    }

    /**
     * 登录
     * @param string $username
     * @param string $password
     * @return User|null
     * @throws DbException
     * @throws Exception
     */
    public function signin($username, $password)
    {
        $user = User::get(['username' => $username]);
        if (empty($user) || !password_verify($password, $user->password)) {
            throw new Exception('用户名或密码错误');
        }
        session(self::SESSION_KEY, $user);
        return $user;
    }

    /**
     * 退出登录
     */
    public function logout()
    {
        session(self::SESSION_KEY, null);
    }

    /**
     * 检测是否游客
     * @return bool
     */
    public function isGuest()
    {
        return !session(self::SESSION_KEY);
    }

    /**
     * 用户ID
     * @return mixed|null
     */
    public function userId()
    {
        $user = session(self::SESSION_KEY);
        return $user ? $user->user_id : null;
    }
}