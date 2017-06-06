<?php
/**
 * 考试答题管理
 */
namespace Admin\Controller;

use Common\Controller\AdminbaseController;
use Common\Service\ClassNoticeService;
use Common\Service\StuanswerService;

class StuanswerController extends AdminbaseController 
{
	/**
	 * 考试答题列表
	 */
	public function index()
	{
		$zone_id        = I('request.zone_id');
		$sub_zone_id    = I('request.sub_zone_id');
		$class_type     = I('request.class_type');
		$class_con      = I('request.class_con');
		$user_type      = I('request.user_type');
		$user_con       = I('request.user_con');
		$course_type    = I('request.course_type');
		$course_con     = I('request.course_con');
		$exercise_type  = I('request.exercise_type');
		$exercise_con   = I('request.exercise_con');
		$start_time     = I('request.start_time');
		$end_time       = I('request.end_time');

		$class          = new ClassNoticeService();
    	$school         = $class->zone();
    	$answer         = new StuanswerService();
    	$result         = $answer->getall($zone_id, $sub_zone_id, $class_type, $class_con, $user_type, $user_con, $course_type, $course_con, $exercise_type, $exercise_con, $start_time, $end_time);
    	$this->assign('school', $school);
    	$this->assign('data', $result['data']);
    	$this->assign('page', $result['page']);
		$this->display();
	}
}