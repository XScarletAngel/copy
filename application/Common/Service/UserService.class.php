<?php
namespace Common\Service;

use Common\Model\MarketUserModel;

class UserService 
{
	/**
	 * @param $ 条件(数组)
	 * @return array
	 */
	public function index($zone_id, $sub_id, $user_type, $user_con)
	{
		$sql = "1=1";
		if($zone_id != ''){
			$sql .= " and zone_id = ".$zone_id;
		}
		if($sub_id != ''){
			$sql .= " and sub_zone_id = ".$sub_id;
		}
		if($user_type != '' && $user_con != ''){
			$sql .= " and ".$user_type." = ".$user_con;
		}
		$where  = array('zone_id' => $zone_id, 'sub_id' => $sub_id, 'user_type' => $user_type, 'user_con' => $user_con);
		$user   = new MarketUserModel();
		$result = $user->index($sql, $where);
		return $result;
	}

	/**
	 * @param $ 条件(数组)
	 * @return array
	 */
	public function add_layer()
	{
		$user   = new MarketUserModel();
		$result = $user->add_layer();
		return $result;
	}

	/**
     * 添加客户档案数据处理
     */
    public function add_post($data) 
    {
    	$user   = new MarketUserModel();
		$result = $user->add_post($data);
		return $result;
    }

    /**
     * 客户档案编辑
     */
    public function edit($id) 
    {
    	$user = new MarketUserModel();
    	$res  = $user->edit($id);
    	return $res;
    }

    /**
     * 客户档案编辑数据提交
     */
    public function edit_post($data) 
    {
    	$user = new MarketUserModel();
    	$res  = $user->edit_post($data);
    	return $res;
    }

    /**
     * 客户档案查看页面
     */
    public function info($id) 
    {
    	$user = new MarketUserModel();
    	$res  = $user->info($id);
    	return $res;
    }
}