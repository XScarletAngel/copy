<?php
/**
 * 后台首页
 */
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Common\Service\ConsultService;

class IndexController extends AdminbaseController {
	
	public function _initialize() {
	    empty($_GET['upw'])?"":session("__SP_UPW__",$_GET['upw']);//设置后台登录加密码	    
		parent::_initialize();
		$this->initMenu();
	}
	
    /**
     * 后台框架首页
     */
    public function index() {
    	$con  = new ConsultService();
    	$data = $con->GetCount();
    	
    	$this->assign("con_num", $data['con_num']);  // 咨询单
    	$this->assign("com_num", $data['com_num']);  // 投诉单
    	$this->assign("not", $data['not_num']);      // 系统公告
    	$this->assign("log", $data['log']);          // 登陆日志
       	$this->display();
    }
    

}

