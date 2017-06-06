<?php
namespace Common\Service;

use Common\Model\StuScoreModel;

class StuscoreService 
{
	/**
	 * @param $ 条件
	 * @return array
	 */
	public function getall($zone_id, $sub_zone_id, $class_type, $class_con, $user_type, $user_con, $subject_name, $course_type, $course_con, $paper, $paper_con, $paper_type, $paper_way, $start_time, $end_time)
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
		if($subject_name != ''){
			$sql .= " and subject_name = '".$subject_name."'";
		}
		if($course_type != '' && $course_con != ''){
			$sql .= " and ".$course_type." = '".$course_con."'";
		}
		if($paper != '' && $paper_con != ''){
			$sql .= " and ".$paper." = '".$paper_con."'";
		}
		if($paper_type != ''){
			$sql .= " and paper_type = '".$paper_type."'";
		}
		if($paper_way != ''){
			$sql .= " and paper_way = '".$paper_way."'";
		}
		if ($start_time != ''){
			$sql .= " and answer_time >= '".$start_time."'";
		}
		if ($end_time != ''){
			$sql .= " and answer_time <= '".$end_time."'";
		}
		$where = array('zone_id' => $zone_id, 'sub_zone_id' => $sub_zone_id, 'class_type' => $class_type, 'class_con' => $class_con, 'user_type' => $user_type, 'user_con' => $user_con, 'subject_name' => $subject_name, 'course_type' => $course_type, 'course_con' => $course_con, 'paper' => $paper, 'paper_con' => $paper_con, 'paper_type' => $paper_type, 'paper_way' => $paper_way, 'start_time' => $start_time, 'end_time' => $end_time);
		$score    = new StuScoreModel();
    	$data     = $score->getall($sql, $where);
    	return $data;
	}

	/**
	 * @param $id 条件
	 * @param $value 修改的值
	 * @return array
	 */
	public function score($id, $value)
	{
		$score    = new StuScoreModel();
		$result   = $score->score($id, $value);
		return $result;
	}

	/**
	 * @param $ 条件
	 * @return ture false
	 */
	public function add_post($zone_id, $sub_zone_id, $classid, $paperid, $user, $sco)
	{
		$score    = new StuScoreModel();
		$result   = $score->add_post($zone_id, $sub_zone_id, $classid, $paperid, $user, $sco);
		return $result;
	}

	/**
	 * @param $id 条件
	 * @return array
     * 获取试卷信息（一张试卷只属于一个课程，一个课程有多张试卷，一个课程可以组成多个班级，一个班级只能有一个课程）
     */
    public function getpaper($class_id)
    {
    	$score    = new StuScoreModel();
		$result   = $score->getpaper($class_id);
		return $result;
    }

    /**
	 * @param $id 条件
	 * @return array
     * 这个班级的所有用户信息
     */
    public function getuser($class_id)
    {
    	$score    = new StuScoreModel();
		$result   = $score->getuser($class_id);
		return $result;
    }

    /**
	 * @param $ 条件
	 * @return ture false
	 */
	public function batchadd_post($zone_id, $sub_zone_id, $classid, $paperid, $answer_time, $sco, $account, $user_no, $user_name)
	{
		$score    = new StuScoreModel();
		$result   = $score->batchadd_post($zone_id, $sub_zone_id, $classid, $paperid, $answer_time, $sco, $account, $user_no, $user_name);
		return $result;
	}
}