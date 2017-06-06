<?php
/**
 * 客户档案
 */
namespace Market\Controller;

use Common\Controller\AdminbaseController;
use Common\Service\UserService;
use Common\Service\SchoolZoneService;
use Common\Service\ResearchService;
use Common\Service\ClassNoticeService;

class UserController extends AdminbaseController
{
	public $market_srv = null;
    public $zone_srv   = null;
    public $mod_activiy   = null;

    public function __construct()
    {
        parent::__construct();
        $this->zone_srv   = new SchoolZoneService();
        $zone = $this->zone_srv->getSubZone(0);

        $this->assign('zone', $zone);
    }

    /**
     * 客户档案列表
     */
    public function index() 
    {
    	$zone_id   = I("request.zone_id");
    	$sub_id    = I("request.sub_zone_id");
    	$user_type = I("request.user_type");
    	$user_con  = I("request.user_con");

    	$user      = new UserService();
    	$data      = $user->index($zone_id, $sub_id, $user_type, $user_con);
    	$this->assign("data", $data['data']);
    	$this->assign("page", $data['page']);
    	$this->display();
    }

    /**
     * 添加客户档案页面
     */
    public function add() 
    {
    	$class  = new ClassNoticeService();
    	$school = $class->zone();    // 校区，分校
    	$res    = new ResearchService;
        $sch = $res->getschool();    // 目标院校
        $user = new UserService();
    	$data = $user->add_layer();  // 活动列表

		$this->assign("data", $data);        
    	$this->assign("school", $school);
    	$this->assign("sch", $sch);
    	$this->display();
    }

    /**
     * 添加客户档案数据处理
     */
    public function add_post() 
    {
    	$data = $_POST;
		$user_phone = $data['user_phone'];

		//已经是学员不能再添加为潜在客户
		$user_info = M("users")->where(['user_login'=>$user_phone])->find();
		if (!empty($user_info)) {
			$stu_info  = M("stu")->where(['user_id'=>$user_info['id']])->find();
			if (!empty($stu_info)) {
				$this->error("已经是学员不能再添加为潜在客户");
			}
		}

    	$user = new UserService();
    	$res  = $user->add_post($data);
    	if($res == 1){
    		$this->success("添加成功", U('User/index'));
    	} elseif($res == 0) {
    		$this->error("客户档案添加失败，请重新添加");
    	} elseif($res == 2) {
    		$this->error("活动添加失败，请重新添加");
    	} elseif($res == 3) {
    		$this->error("该客户已存在，请重新添加");
    	}
    }

    /**
     * 添加活动页面弹窗
     */
    // public function add_layer() 
    // {
    // 	$user = new UserService();
    // 	$data = $user->add_layer();
    // 	$this->assign("data", $data);
    // 	$this->display();
    // }

    /**
     * 客户档案编辑
     */
    public function edit() 
    {
    	$id   = I("get.id");
    	$class  = new ClassNoticeService();
    	$school = $class->zone();    // 校区，分校
    	$res    = new ResearchService;
        $sch = $res->getschool();    // 目标院校
        $user = new UserService();
    	$data = $user->add_layer();  // 活动列表
    	$user = new UserService();
    	$res  = $user->edit($id);
    	foreach($data as $k=>$v){
    		foreach($res['arr'] as $kk=>$vv){
    			if($data[$k]['id'] == $res['arr'][$kk]['act_id']){
    				$data[$k]['check'] = 1;
    			}
    		}
    	}
    	$this->assign("data", $data);
    	$this->assign("school", $school);
    	$this->assign("sch", $sch);
    	$this->assign("res", $res['res']);
    	$this->assign("id", $id);
    	$this->display();
    }

    /**
     * 客户档案编辑数据提交
     */
    public function edit_post() 
    {
    	$data = $_POST;
    	$user = new UserService();
    	$res  = $user->edit_post($data);
    	if($res == 1){
    		$this->success("编辑成功", U('User/index'));
    	} elseif($res == 0) {
    		$this->error("编辑失败，请重新编辑");
    	}
    }

    /**
     * 客户档案查看页面
     */
    public function info() 
    {
    	$id   = I("get.id");
    	$user = new UserService();
    	$res  = $user->info($id);
    	
    	$this->assign("res", $res['res']);
    	$this->assign("acts", $res['acts']);
    	$this->display();
    }
}