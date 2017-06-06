<?php
namespace Common\Model;

use Common\Model\CommonModel;

class StuScoreModel extends CommonModel
{

	/**
	 * @param $where 条件(数组) 
	 * @param $field 字段
	 * @return array
	 */
	public function getall($sql, $where)
	{
		$score   = M("stu_score");
		$count   = $score->where($sql)->count();
		$Page    = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		// //分页跳转的时候保证查询条件
		if (!empty($where)){
			foreach($where as $key=>$val) {
			    $Page->parameter[$key]   =   urlencode($val);
			}
		}
		$show    = $Page->show();// 分页显示输出
		$data    = $score->where($sql)->limit($Page->firstRow.','.$Page->listRows)->select();
		// echo "<pre>";print_r($data);die;
		$result  = array('data' => $data, 'page' => $show);
		return $result;
	}

	/**
	 * @param $id 条件
	 * @param $value 修改的值
	 * @return array
	 */
	public function score($id, $value)
	{
		$score  = M("stu_score");
		$result = $score->where("id = ".$id)->data(array('score' => $value))->save();
		return $result;
	}

	/**
	 * @param $ 条件
	 * @return ture false
	 */
	public function add_post($zone_id, $sub_zone_id, $classid, $paperid, $user, $sco)
	{
		$score  = M("stu_score");
		$zone   = M("school_zone");  // 校区表
		$class  = M("class");        // 班级表
		$course = M("course");      // 课程表
		$paper  = M("paper");        // 试卷表
		$users  = M("users");        // 用户信息表

		$zone_arr = $zone->where("id = %d", $zone_id)->find();                     // 查询分校信息
		$sub_zone_arr = $zone->where("id = %d", $sub_zone_id)->find();              // 查询分区信息
		$class_arr = $class->where("id = %d", $classid)->find();  // 查询班级信息 (一个班级只有一个课程)
		$course_arr = $course->where("id = %d", $class_arr['course_id'])->find();  // 查询课程信息
		// print_r($course_arr);die;
		$paper_arr = $paper->where("id = %d", $paperid)->find();                     // 查询试卷的信息
		if(preg_match("/^1[34578]{1}\d{9}$/",$user)){  
		    $user_arr = $users->where(array('mobile' => $user))->find();  
		} else {  
		    $user_arr = $users->where(array('user_no' => $user))->find();
		}
		if(empty($user_arr)){
			return 2;
		} else {
			$data = array('zone_id' => $zone_arr['id'], 'zone_name' => $zone_arr['sz_name'], 'sub_zone_id' => $sub_zone_arr['id'], 'sub_zone_name' => $sub_zone_arr['sz_name'], 'class_id' => $class_arr['id'], 'class_no' => $class_arr['class_no'], 'class_name' => $class_arr['class_name'], 'user_account' => $user_arr['user_login'], 'user_no' => $user_arr['user_no'], 'user_name' => $user_arr['user_nicename'], 'course_id' => $course_arr['id'], 'course_no' => $course_arr['course_no'], 'course_name' => $course_arr['course_name'], 'paper_id' => $paper_arr['id'], 'paper_no' => $paper_arr['paper_no'], 'paper_name' => $paper_arr['paper_title'], 'exam_last' => $paper_arr['total_time'], 'paper_type' => $paper_arr['paper_type'], 'paper_way' => $paper_arr['paper_way'], 'subject_type' => $course_arr['subject_type'], 'score' => number_format($sco, 2, '.', ''));
			$result = $score->data($data)->add();
			return $result;
		}
		
	}

	/**
	 * @param $id 条件
	 * @return array
     * 获取试卷信息（一张试卷只属于一个课程，一个课程有多张试卷，一个课程可以组成多个班级，一个班级只能有一个课程）
     */
    public function getpaper($class_id)
    {
    	$class     = M("class");
    	$class_arr = $class->where("id = %d", $class_id)->find();
    	$paper     = M("paper");
    	$paper_arr = $paper->where("course_id = %d", $class_arr['course_id'])->select();
    	return $paper_arr;die;
    }

    /**
	 * @param $id 条件
	 * @return array
     * 获取这个班级的所有用户信息
     */
    public function getuser($class_id)
    {
    	$class     = M("class_user");
    	$user      = M("users");
    	$class_arr = $class->where("class_id = %d", $class_id)->select();
    	for($i=0;$i<count($class_arr);$i++){
    		$user_id .= $class_arr[$i]['user_id'].",";
    	}
    	if(empty($user_id)){
    		return 2;die;
    	} else {
    		$result = $user->field('id, user_login, user_no, user_nicename, mobile')->where("id in(%s)", rtrim($user_id, ','))->select();
	    	return $result;die;
    	}
    	
    }

    /**
	 * @param $ 条件
	 * @return ture false
	 */
	public function batchadd_post($zone_id, $sub_zone_id, $classid, $paperid, $answer_time, $sco, $account, $user_no, $user_name)
	{
		$score  = M("stu_score");
		$zone   = M("school_zone");  // 校区表
		$class  = M("class");        // 班级表
		$course = M("course");      // 课程表
		$paper  = M("paper");        // 试卷表
		// $users  = M("users");        // 用户信息表

		$zone_arr = $zone->where("id = %d", $zone_id)->find();                     // 查询分校信息
		$sub_zone_arr = $zone->where("id = %d", $sub_zone_id)->find();              // 查询分区信息
		$class_arr = $class->where("id = %d", $classid)->find();  // 查询班级信息 (一个班级只有一个课程)
		$course_arr = $course->where("id = %d", $class_arr['course_id'])->find();  // 查询课程信息
		// print_r($course_arr);die;
		$paper_arr = $paper->where("id = %d", $paperid)->find();                     // 查询试卷的信息
		// if(preg_match("/^1[34578]{1}\d{9}$/",$user)){  
		//     $user_arr = $users->where(array('mobile' => $user))->find();  
		// } else {  
		//     $user_arr = $users->where(array('user_no' => $user))->find();
		// }
		foreach($sco as $k=>$v){
			if($sco[$k] == ''){
				$num = 0;
			} else {
				$num  = number_format($sco[$k], 2, '.', '');
			}
			$data = array('zone_id' => $zone_arr['id'], 'zone_name' => $zone_arr['sz_name'], 'sub_zone_id' => $sub_zone_arr['id'], 'sub_zone_name' => $sub_zone_arr['sz_name'], 'class_id' => $class_arr['id'], 'class_no' => $class_arr['class_no'], 'class_name' => $class_arr['class_name'], 'user_account' => $account[$k], 'user_no' => $user_no[$k], 'user_name' => $user_name[$k], 'course_id' => $course_arr['id'], 'course_no' => $course_arr['course_no'], 'course_name' => $course_arr['course_name'], 'paper_id' => $paper_arr['id'], 'paper_no' => $paper_arr['paper_no'], 'paper_name' => $paper_arr['paper_title'], 'exam_last' => $paper_arr['total_time'], 'paper_type' => $paper_arr['paper_type'], 'paper_way' => $paper_arr['paper_way'], 'answer_time' => $answer_time, 'subject_type' => $course_arr['subject_type'], 'score' => $num);
			$result = $score->data($data)->add();
		}
		return $result;
		
	}
}