<?php
namespace Common\Model;

class MarketActivityModel extends CommonModel
{

	protected $_validate = array(
		array('zone_id','require','请选择校区！'),
		array('act_name','require','请输入活动名称！'),
		array('act_name','','活动名称已经存在！',0,'unique',1),
		array('act_address','require','请输入活动地址！'),
		array('act_master','require','请输入活动负责人！'),
		array('act_begin_time','require','请选择活动日期！'),
		array('act_info','require','请输入活动说明！'),
		array('act_status',array(1,2),'状态的范围不正确！',2,'in'),
	);

	public function queryByPage($where)
	{
		
	}
}