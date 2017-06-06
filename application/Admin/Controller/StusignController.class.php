<?php
/**
 * 考勤管理
 */
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Common\Service\ClassNoticeService;
use Common\Service\StusignService;

class StusignController extends AdminbaseController 
{
	/**
	 * 考勤管理列表
	 */
	public function index()
	{
		$zone_id        = I('request.zone_id');
		$sub_zone_id    = I('request.sub_zone_id');
		$class_type     = I('request.class_type');
		$class_con      = I('request.class_con');
		$user_type      = I('request.user_type');
		$user_con       = I('request.user_con');
		$course_times   = I('request.course_times');
		$notice_status  = I('request.notice_status');
		$reply_status   = I('request.reply_status');
		$sign_status    = I('request.sign_status');

		$class          = new ClassNoticeService();
    	$school         = $class->zone();
    	$stusign        = new StusignService();
    	$result         = $stusign->getall($zone_id, $sub_zone_id, $class_type, $class_con, $user_type, $user_con, $course_times, $notice_status, $reply_status, $sign_status);
    	$this->assign('school', $school);
    	$this->assign('data', $result['data']);
    	$this->assign('page', $result['page']);
		$this->display();
	}

	/**
     * 签到
     */
    public function sign()
    {
        $id     = I('request.id');     // 公告id
        $sign   = new StusignService();
        $result = $sign->sign($id);
        if ($result) {
            $this->ajaxReturn(['msg'=>'签到成功～','code'=>1]);
        } else {
            $this->ajaxReturn(['msg'=>'签到失败～','code'=>2]);
        }
    }

    /**
     * 添加备注
     */
    public function remark()
    {
        $id     = I('request.id');     // 公告id
        $value  = I('request.value');     // 公告id
        $sign   = new StusignService();
        $result = $sign->remark($id, $value);
        if ($result) {
            $this->ajaxReturn(['msg'=>'备注成功～','code'=>1]);
        } else {
            $this->ajaxReturn(['msg'=>'备注失败～','code'=>2]);
        }
    }
}