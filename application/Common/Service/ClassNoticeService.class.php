<?php
namespace Common\Service;

use Common\Model\ClassNoticeModel;

class ClassNoticeService 
{
	/**
	 * @param $search 条件(数组)
	 * @return array
	 */
	public function getAll($zone_id, $zone_sub_id, $class_id, $notice_type, $notice, $start_time, $end_time, $status)
	{
		$where    = array('zone_id' => $zone_id, 'zone_sub_id' => $zone_sub_id, 'class_id' => $class_id, 'notice_type' => $notice_type, 'notice' => $notice, 'start_time' => $start_time, 'end_time' => $end_time, 'status' => $status);
		$sql      = "1=1";
		if ($zone_id != ''){
			$sql .= " and zone_id = ".$zone_id;
		}
		if ($zone_sub_id != ''){
			$sql .= " and zone_sub_id = ".$zone_sub_id;
		}
		if ($class_id != ''){
			$sql .= " and class_id = ".$class_id;
		}
		if ($notice_type != ''){
			$sql .= " and notice_type = ".$notice_type;
		}
		if ($notice != ''){
			$sql .= " and notice like '%".$notice."%'";
		}
		if ($start_time != ''){
			$sql .= " and add_time >= '".$start_time."'";
		}
		if ($end_time != ''){
			$sql .= " and add_time <= '".$end_time."'";
		}
		if ($status != ''){
			$sql .= " and status = '".$status."'";
		}
		$class  = new ClassNoticeModel();
		$result = $class->getAll($sql, $where);
		return $result;
	}

	/**
     * 校区查询
     */
    public function zone() 
    {   
        $zone = new ClassNoticeModel();
        $zone = $zone->zone();
        return $zone;
    }

    /**
     * 发布公告数据添加
     */
    public function add_post($data) 
    {   
        $notice = new ClassNoticeModel();
        $result = $notice->add_post($data);
        return $result;
    }

	/**
     * 分校查询
     */
    public function getZone($id) 
    {   
        $zone = new ClassNoticeModel();
        $zone = $zone->getZone($id);
        return $zone;
    }

    /**
     * 班级查询
     */
    public function getClass($id) 
    {   
        $zone = new ClassNoticeModel();
        $zone = $zone->getClass($id);
        return $zone;
    }

    /**
     * @param id 公告id
     * @param status 要修改成的状态
     * @return 返回的数组
     */
    public function status($id, $status)
    {
        $notice = new ClassNoticeModel();
        $result = $notice->status($id, $status);
        return $result;
    }
}