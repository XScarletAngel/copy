<?php
// +---------------------------------------------------------------------
// | ThinkCMF [ WE CAN DO IT MORE SIMPLE ]
// +---------------------------------------------------------------------
// | Copyright (c) 2013-2014 http://www.thinkcmf.com All rights reserved.
// +---------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +---------------------------------------------------------------------
// | Author: Dean <zxxjjforever@163.com>
// +---------------------------------------------------------------------
namespace Common\Behavior;

use Think\Behavior;
use Think\Hook;

// 初始化钩子信息
class AdminLogBehavior extends Behavior {

    public $actionUrl;
    public $app;
    public $model;
    public $action;

    /**
     * Encapsulate an event with $subject and $args.
     *
     * @param mixed $subject   The subject of the event, usually an object.
     * @param array $arguments Arguments to store in the event.
     */
    public function __construct()
    {
        $this->actionUrl = __ACTION__;
        $this->app       = MODULE_NAME;
        $this->model     = CONTROLLER_NAME;
        $this->action    = ACTION_NAME;
    }

    // 行为扩展的执行入口必须是run
    public function run(&$content){
//        echo "<pre>"; var_dump($this->actionUrl); die;
//        echo "<pre>"; var_dump(session()); die;
        //查看日志路由是否设为 免记录
        if($this->DoNotLogArray()) return ;
//        echo "<pre>"; var_dump(111); die;
        //未登录状态 免记录
        $name = session('name');
        $admin_user = session('admin_user');
        if(empty($name) || empty($admin_user)) return ;
//        echo "<pre>"; var_dump(222); die;

        //整理查询条件，从menu表 获取操作名称
        $where = [
            'app'       =>  $this->app,
            'model'     =>  $this->model,
            'action'    =>  $this->action,
        ];
        //搜索对象路由名字
        $pathInfo = M('menu')->where($where)->find();
//        echo "<pre>"; var_dump($pathInfo); die;
        $object = (empty($pathInfo))?'':$pathInfo['name'];
        //插入数据库（admin_user）
        $InData = [
          'admin_id'        =>  session('admin_user')['id'], // '操作人 id',
          'admin_name'      =>  session('name'),             // '操作人姓名',
          'module_name'     =>  $where['app'],               // '访问的应用名称',
          'controller_name' =>  $where['model'],             // '访问的控制器名称',
          'action_name'     =>  $where['action'],            // '访问的方法名称',
          'object'          =>  $object,                     // '操作对象',
          'status'          =>  1                            // '状态 1正常  2异常',
        ];
        // type=4登录，5退出 操作
//        $InData = $this->logSpecial($InData);
        // '操作类型：1增加，2删除，3修改  4登录  5登出',
        switch ($this->actionUrl)
        {
            case '/index.php/Admin/Public/dologin':
                $InData['type'] = 4;
                break;
            case '/index.php/Admin/Public/logout':
                $InData['type'] = 5;
                break;
            default:
                $InData['type'] = 0;
                break;
        }
//        $data = M('admin_log')->select(['action'=>'logout'])->find(); echo "<pre>"; print_r($data); die;
         M('admin_log')->add($InData);

    }

    /**
     * User: maChuang
     * @return bool
     * 判断当前路由是否在免log范围内
     */
    public function DoNotLogArray(){
        //可加
        $arr = [
            '/index.php/Admin/Public/login',  //登录view
            '/index.php/Admin/Log/index',     //操作日志管理、
            '/index.php/Admin/Index/index',     //首页
        ];
        if(in_array_case($this->actionUrl,$arr)){
            return true;
        }
        return false;
    }

    /**
     * User: maChuang
     * @param $InData
     * @return mixed
     * 判断type操作
     */
    public function logSpecial($InData)
    {
        //特殊操作数组
       $arrSpecial = [
           '/index.php/Admin/Public/dologin',//登录
           '/index.php/Admin/Public/logout', //退出登录
       ] ;

        if(in_array_case($this->actionUrl,$arrSpecial)){

            switch ($this->actionUrl)
            {
                case '/index.php/Admin/Public/dologin':
                    $InData['type'] = 4;
                    break;
                case '/index.php/Admin/Public/logout':
                    $InData['type'] = 5;
                    break;
                default:
                    $InData['type'] = 0;
                    break;
            }

        }
        return $InData;

    }
}
