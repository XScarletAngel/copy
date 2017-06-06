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
class AdminLoginBehavior extends Behavior {

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
//        var_dump($content);die;
        //如果不是登录操作就return
        if($this->actionUrl != '/index.php/Admin/Public/dologin') return ;

        //未登录状态 免记录
        $name = session('name');
        $admin_id = session('ADMIN_ID');
        if(empty($name) || empty($admin_id)) return ;

        //整理查询条件，从menu表 获取操作名称
        $where = [
            'app'       =>  $this->app,
            'model'     =>  $this->model,
            'action'    =>  $this->action,
        ];
        //搜索对象路由名字
        $pathInfo = M('menu')->where($where)->find();
        $object = (empty($pathInfo))?'':$pathInfo['name'];
        //插入数据库（admin_log）
        $InData = [
          'type'            =>  4,                           // '操作类型：1增加，2删除，3修改  4登录  5登出',
          'admin_id'        =>  $admin_id,                   // '操作人 id',
          'admin_name'      =>  $name,                       // '操作人姓名',
          'module_name'     =>  $where['app'],               // '访问的应用名称',
          'controller_name' =>  $where['model'],             // '访问的控制器名称',
          'action_name'     =>  $where['action'],            // '访问的方法名称',
          'object'          =>  $object,                     // '操作对象',
          'status'          =>  1                            // '状态 1正常  2异常',
        ];



         M('admin_log')->add($InData);

    }

}
