<?php
namespace Common\Service;

use Common\Model\StuSignModel;

class StusignService 
{
	/**
	 * @param $ 条件(数组)
	 * @return array
	 */
	public function getall($zone_id, $sub_zone_id, $class_type, $class_con, $user_type, $user_con, $course_times, $notice_status, $reply_status, $sign_status)
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
		if($course_times != ''){
			$sql .= " and course_times = '".$course_times."'";
		}
		if($notice_status != ''){
			$sql .= " and notice_status = '".$notice_status."'";
		}
		if($reply_status != ''){
			$sql .= " and reply_status = '".$reply_status."'";
		}
		if($sign_status != ''){
			$sql .= " and sign_status = '".$sign_status."'";
		}
		$where = array('zone_id' => $zone_id, 'sub_zone_id' => $sub_zone_id, 'class_type' => $class_type, 'class_con' => $class_con, 'user_type' => $user_type, 'user_con' => $user_con, 'course_times' => $course_times, 'notice_status' => $notice_status, 'reply_status' => $reply_status, 'sign_status' => $sign_status);
		$stusign  = new StuSignModel();
    	$data     = $stusign->getall($sql, $where);
    	return $data;
	}

	/**
     * @param $id 条件
	 * @return ture  false
     */
    public function sign($id)
    {
        $sign   = new StuSignModel();
        $result = $sign->sign($id);
        return $result;
    }

    /**
     * @param $id 条件
     * @param $value 要添加的值(字符串)
	 * @return ture  false
     */
    public function remark($id, $value)
    {
        $sign   = new StuSignModel();
        $result = $sign->remark($id, $value);
        return $result;
    }

    /**
     * User: maChuang
     * @param $id
     * @return mixed
     * id查询
     */
    public static function find($where)
    {
        $st = M("stu_sign");
        return $st->where($where)->select();
    }
}