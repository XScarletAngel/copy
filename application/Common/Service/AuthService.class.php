<?php


namespace Common\Service;

/**
 * 用户登录操作
 *
 * Class Auth
 * @package Common\Service
 */
class AuthService
{

    private static $user = null;
    public static $user_id = null;


    const KEY = 'xkhtedu_user';

    /**
     * 检测登录
     */
    private static function checkLogin()
    {
        if($data = session(self::KEY)) {
            self::$user = json_decode($data, true);
            self::$user_id = isset(self::$user['uid'])?self::$user['uid']:null;
        }
    }


    /**
     * 获取用户信息
     *
     * @return null/array
     */
    public static function userInfo()
    {

        if (is_null(self::$user)) {
            self::checkLogin();
        }

        return self::$user;
    }


    public static function reloadUserInfo()
    {
        if($info  = ApiService::response2data(ApiService::call('member.info'))){
            self::setLoginSuccess($info);
//            self::$user = $info;
            return true;
        }

        return false;
    }


    /**
     * 设置登录状态，并跳转到制定页面或首页
     *
     * @param array $userData
     */
    public static function setLoginSuccess(array $userData)
    {
        session(self::KEY, json_encode($userData));
    }


    /**
     * 退出登录
     */
    public static function setLoginOut()
    {
        session(self::KEY, null);
        self::$user = null;
    }

}