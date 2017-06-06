<?php
/**
 * 班级公告
 */
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Common\Service\ClassNoticeService;

class ClassnoticeController extends AdminbaseController 
{
	/**
     * 班级公告查询列表
     */
    public function index() 
    {   
        $search      = array();
        $zone_id     = I('request.zone_id');
        $zone_sub_id = I('request.zone_sub_id');
        $class_id    = I('request.class_id');
        $notice_type = I('request.notice_type');
        $notice      = I('request.notice');
        $start_time  = I('request.start_time');
        $end_time    = I('request.end_time');
        $status      = I('request.status');
        
    	$class       = new ClassNoticeService();
    	$school      = $class->zone();
    	$data        = $class->getAll($zone_id, $zone_sub_id, $class_id, $notice_type, $notice, $start_time, $end_time, $status);
    	// $this->assign('page', $categorys['page']);  // 分页数据显示
        $this->assign('notice', $data['notice']);  // 数据显示
        $this->assign('school', $school);  // 学校
        $this->assign('page', $data['page']);  // 分页数据显示
       	$this->display();
    }

    /**
     * 发布公告
     */
    public function add() 
    {   
    	$class  = new ClassNoticeService();
    	$school = $class->zone();
    	$this->assign('school', $school);
     	$this->display();
    }

    /**
     * 发布公告信息处理
     */
    public function add_post() 
    {   
    	$user_id       = get_current_admin_id();
    	$zone_id       = I('post.zone_id');
    	$zone_name     = I('post.zone_name');
    	$zone_sub_id   = I('post.zone_sub_id');
    	$zone_sub_name = I('post.zone_sub_name');
    	$class_id      = I('post.class_id');
    	$class_name    = I('post.class_name');
    	$notice_type   = I('post.notice_type');
    	$notice        = I('post.notice');
    	$status        = I('post.status');
    	$data          = array('user_id' => $user_id,
                            'zone_id' => $zone_id,
                            'zone_name' => $zone_name,
                            'zone_sub_id' => $zone_sub_id,
                            'zone_sub_name' => $zone_sub_name,
                            'class_id' => $class_id,
                            'class_name' => $class_name,
                            'notice_type' => $notice_type,
                            'notice' => $notice,
                            'status' => $status
                        );
    	if (IS_POST){
    		if($zone_name != '' && $zone_sub_name != '' && $class_name != '' && $notice != ''){
    			$notice        = new ClassNoticeService();
    			$result        = $notice->add_post($data);
    			if ($result != false){
		    		$this->success("发布成功", U('Classnotice/index'));
		    	} else {
		    		$this->error("发布失败");
		    	}
    		} else {
    			$this->error("请填写完整信息");
    		}
    	} else {
    		$this->error("数据提交错误");
    	}
    	
    }

    /**
     * 分校查询
     */
    public function getZone() 
    {   
     	$id   = I('request.zone_id');     // 校区id
        $zone = new ClassNoticeService();
        $zone = $zone->getZone($id);
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($zone));
        $this->ajaxReturn(array_values($zone));
    }

    /**
     * 班级查询
     */
    public function getClass() 
    {   
     	$id   = I('request.fzone_id');     // 校区id
        $zone = new ClassNoticeService();
        $zone = $zone->getClass($id);
        header('Content-Type:application/json; charset=utf-8');
        exit(json_encode($zone));
        $this->ajaxReturn(array_values($zone));
    }

    /**
     * 班级公告禁用启用
     */
    public function status() 
    {   
        $id     = I('request.id');     // 公告id
        $status = I('request.status');     // 公告要更改的状态
        $notice = new ClassNoticeService();
        $result = $notice->status($id, $status);
        if ($result) {
            $this->ajaxReturn(['msg'=>'修改成功～','code'=>1]);
        } else {
            $this->ajaxReturn(['msg'=>'修改失败～','code'=>2]);
        }
    }

}