<?php
namespace Common\Service;

use Common\Model\ComplainModel;

class ComplainService 
{
	/**
	 * @param $search 条件(数组)
	 * @return array
	 */
	public function getAll($zone_id, $zone_sub_id, $class_id, $user, $user_con, $touser, $touser_con, $com_type, $deal_status, $com, $com_con, $start_time, $end_time)
	{
	    $where    = array('zone_id' => $zone_id, 'zone_sub_id' => $zone_sub_id, 'class_id' => $class_id, 'user' => $user, 'user_con' => $user_con, 'touser' => $touser, 'touser_con' => $touser_con, 'com_type' => $com_type, 'deal_status' => $deal_status, 'com' => $com, 'com_con' => $com_con, 'start_time' => $start_time, 'end_time' => $end_time);
		$sql      = "1=1";
		if ($zone_id != ''){
			$sql .= " and school.id = ".$zone_id;
		}
		if ($zone_sub_id != ''){
			$sql .= " and xschool.id = ".$zone_sub_id;
		}
		if ($class_id != ''){
			$sql .= " and class_id = ".$class_id;
		}
		if ($user != '' && $user_con != ''){
			$sql .= " and a.".$user." like '".$user_con."%'";
		}
		if ($touser != '' && $touser_con != ''){
			$sql .= " and ".$touser." like '".$touser_con."'";
		}
		if ($com_type != ''){
			$sql .= " and com_type = ".$com_type;
		}
		if ($deal_status != '' && $deal_status == 0){
			$sql .= " and deal_status = 0";
		}
		if ($deal_status != '' && $deal_status != 0){
			$sql .= " and deal_status > 0";
		}
		if ($com != '' && $com_con != ''){
			$sql .= " and ".$com." like '".$com_con."%'";
		}
		if ($start_time != ''){
			$sql .= " and ".C('DB_PREFIX')."complain.add_time >= '".$start_time."'";
		}
		if ($end_time != ''){
			$sql .= " and ".C('DB_PREFIX')."complain.add_time <= '".$end_time."'";
		}
		$complain = new ComplainModel();
		$result   = $complain->getAll($sql, $where);
		return $result;
	}

	/**
	 * @param $id 条件
	 * @return array
	 */
	public function details($id)
	{
		$complain = new ComplainModel();
		$result   = $complain->details($id);
		return $result;
	}

	/**
	 * @param $id 主键
	 * @param $deal_status 状态
	 * @param $deal_reason 理由
	 * @param $remark 备注
	 * @param $deal_time 处理时间
	 * @return array
	 */
	public function deal($id, $deal_status, $deal_reason, $remark, $deal_time)
	{
		$data     = array('id' => $id, 'deal_status' => $deal_status, 'deal_reason' => $deal_reason, 'remark' => $remark, 'deal_time' => $deal_time);
		$complain = new ComplainModel();
		$result   = $complain->deal($data);
		return $result;
	}





}