<?php
namespace Common\Service;

use Common\Model\ClassBookReceiveModel;

class ReceiveService 
{
	/**
	 * @param $ 条件(数组)
	 * @return array
	 */
	public function getall($zone_id, $sub_zone_id, $class_type, $class_con, $book_type, $book_con, $user_type, $user_con, $start_time, $end_time, $receive_status)
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
		if($book_type != '' && $book_con != ''){
			$sql .= " and ".$book_type." = '".$book_con."'";
		}
		if($user_type != '' && $user_con != ''){
			$sql .= " and '".$user_type."' = '".trim($user_con, ' ')."'";
		}
		if ($start_time != ''){
			$sql .= " and receive_time >= '".$start_time."'";
		}
		if ($end_time != ''){
			$sql .= " and receive_time <= '".$end_time."'";
		}
		if($receive_status != ''){
			$sql .= " and receive_status = ".$receive_status;
		}
		$where = array('zone_id' => $zone_id, 'sub_zone_id' => $sub_zone_id, 'class_type' => $class_type, 'class_con' => $class_con, 'book_type' => $book_type, 'book_con' => $book_con, 'user_type' => $user_type, 'user_con' => $user_con, 'start_time' => $start_time, 'end_time' => $end_time, 'receive_status' => $receive_status);
		$receive  = new ClassBookReceiveModel();
    	$data     = $receive->getall($sql, $where);
    	return $data;
	}

	/**
	 * @param $ids 条件
	 * @return ture false
	 */
	public function receive($ids)
	{
		$receive = new ClassBookReceiveModel();
		$result  = $receive->receive($ids);
		return $result;
	}

	/**
	 * @param $ids 条件
	 * @return ture false
	 */
	public function noreceive($ids)
	{
		$receive = new ClassBookReceiveModel();
		$result  = $receive->noreceive($ids);
		return $result;
	}

	/**
	 * @param $id 条件
	 * @param $status 条件s
	 * @return ture false
	 */
	public function singlereceive($id, $status)
	{
		$receive = new ClassBookReceiveModel();
		$result  = $receive->singlereceive($id, $status);
		return $result;
	}
}