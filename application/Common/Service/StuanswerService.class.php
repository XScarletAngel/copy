<?php
namespace Common\Service;

use Common\Model\StuAnswerModel;

class StuAnswerService 
{
	/**
	 * @param $ 条件
	 * @return array
	 */
	public function getall($zone_id, $sub_zone_id, $class_type, $class_con, $user_type, $user_con, $course_type, $course_con, $exercise_type, $exercise_con, $start_time, $end_time)
	{
		$sql = "1=1";
		if($zone_id != ''){
			$sql .= " and zone_id = ".$zone_id;
		}
		if($sub_zone_id != ''){
			$sql .= " and sub_zone_id = ".$sub_zone_id;
		}
		if($class_type != '' && $class_con != ''){
			$sql .= " and ".$class_type." = '".$class_con."'";
		}
		if($user_type != '' && $user_con != ''){
			$sql .= " and ".$user_type." = '".$user_con."'";
		}
		if($course_type != '' && $course_con != ''){
			$sql .= " and ".$course_type." = '".$course_con."'";
		}
		if($exercise_type != '' && $exercise_con != ''){
			$sql .= " and ".$exercise_type." = '".$exercise_con."'";
		}
		if ($start_time != ''){
			$sql .= " and answer_time >= '".$start_time."'";
		}
		if ($end_time != ''){
			$sql .= " and answer_time <= '".$end_time."'";
		}
		$where = array('zone_id' => $zone_id, 'sub_zone_id' => $sub_zone_id, 'class_type' => $class_type, 'class_con' => $class_con, 'user_type' => $user_type, 'user_con' => $user_con, 'course_type' => $course_type, 'course_con' => $course_con, 'exercise_type' => $exercise_type, 'exercise_con' => $exercise_con, 'start_time' => $start_time, 'end_time' => $end_time);
		$answer   = new StuAnswerModel();
    	$data     = $answer->getall($sql, $where);
    	return $data;
	}
}