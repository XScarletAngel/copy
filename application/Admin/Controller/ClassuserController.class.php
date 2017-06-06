<?php
/**
 * 班级学员管理
 */
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Common\Service\ClassNoticeService;
use Common\Service\ClassUserService;

class ClassuserController extends AdminbaseController 
{
	/**
     * 班级学员管理列表
     */
    public function index() 
    {
    	$zone_id     = I("post.zone_id");
    	$sub_zone_id = I("post.sub_zone_id");
    	$class_type  = I("post.class_type");
    	$class_con   = I("post.class_con");
    	$user_type   = I("post.user_type");
    	$user_con    = I("post.user_con");
    	$class       = new ClassNoticeService();
	    $school      = $class->zone();          // 查询分校跟校区
    	if((!empty($sub_zone_id) && !empty($class_type) && !empty($class_con)) or (!empty($sub_zone_id) && !empty($user_type) && !empty($user_con))){
	    	$classuser = new ClassUserService();
	    	$data = $classuser->index($zone_id, $sub_zone_id, $class_type, $class_con, $user_type, $user_con);
	    	// echo "<pre>";print_r($data);die;
	    	$this->assign("data", $data['data']);
	    	$this->assign("page", $data['page']);
    	}
        $this->assign('school', $school);  // 学校
    	$this->display();
    }

    /**
     * 问答操作
     */
    public function qa() 
    {   
     	$id   = I('request.id');     // 班级分配表id
     	$sta  = I('request.sta');     // 学员在当前班级里的问答状态
     	if($sta == 1){
     		$no = 2;
     	} else {
     		$no = 1;
     	}
        $class = new ClassUserService();
        $res  = $class->qa($id, $no);
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($res));
        $this->ajaxReturn(array_values($res));
    }

    /**
     * 话题操作
     */
    public function talk() 
    {   
     	$id   = I('request.id');     // 班级分配表id
     	$sta  = I('request.sta');     // 学员在当前班级里的问答状态
     	if($sta == 1){
     		$no = 2;
     	} else {
     		$no = 1;
     	}
        $class = new ClassUserService();
        $res  = $class->talk($id, $no);
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($res));
        $this->ajaxReturn(array_values($res));
    }
}