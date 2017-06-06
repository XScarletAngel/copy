<?php
namespace Common\Service;

use Common\Model\ClassUserModel;

class ClassUserService 
{
	/**
	 * @param $search 条件(数组)
	 * @return array
	 */
	public function index($zone_id, $sub_zone_id, $class_type, $class_con, $user_type, $user_con)
	{
		$sql = "1=1";
		if(!empty($zone_id)){
			$sql .= " and ".C('DB_PREFIX')."class.zone_id = ".$zone_id;
		}
		if(!empty($sub_zone_id)){
			$sql .= " and ".C('DB_PREFIX')."class.sub_zone_id = ".$sub_zone_id;
		}
		if(!empty($class_type) && !empty($class_con)){
			$sql .= " and ".C('DB_PREFIX')."class_user.".$class_type." = '".$class_con."'";
		}
		if(!empty($user_type) && !empty($user_con)){
			$sql .= " and ".C('DB_PREFIX')."users.".$user_type." = '".$user_con."'";
		}
		$where = array('zone_id' => $zone_id, 'sub_zone_id' => $sub_zone_id, 'class_type' => $class_type, 'class_con' => $class_con, 'user_type' => $user_type, 'user_con' => $user_con);
		$class = new ClassUserModel();
		$result = $class->index($sql, $where);
		return $result;
	}

	/**
     * 问答操作
     */
    public function qa($id, $no) 
    {   
        $class = new ClassUserModel();
        $res   = $class->qa($id, $no);
        return $res;
    }

    /**
     * 话题操作
     */
    public function talk($id, $no) 
    {   
        $class = new ClassUserModel();
        $res   = $class->talk($id, $no);
        return $res;
    }
}