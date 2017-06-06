<?php
namespace Common\Service;

use Common\Model\UsersModel;

class UsersService 
{
	/**
	 * @param $ 条件(数组)
	 * @return array
	 */
	public function search($usertype, $user_con)
	{
		$sql = "1=1";
		if($usertype != '' && $user_con != ''){
			$sql .= " and ".$usertype." = ".$user_con;
		}
		$user = new UsersModel();
		$result = $user->search($sql);
		return $result;
	}

	/**
	 * @param $ 条件(数组)
	 * @return array
	 */
	public function lists($usertype, $user_con, $stu, $teacher, $start_time, $end_time, $source)
	{
		$sql = "1=1";
		if($usertype != '' && $user_con != ''){
			$sql .= " and ".$usertype." = ".$user_con;
		}
		if($stu != ''){
			if($stu == 1){
				$sql .= " and user_type = 1 or user_type = 3";
			} elseif($stu == 2) {
				$sql .= " and user_type != 1 and user_type != 3";
			}
		}
		if($teacher != ''){
			if($teacher == 1){
				$sql .= " and user_type = 2 or user_type = 3";
			} elseif($teacher == 2) {
				$sql .= " and user_type != 2 and user_type != 3";
			}
		}
		if ($start_time != ''){
			$sql .= " and create_time >= '".$start_time."'";
		}
		if ($end_time != ''){
			$sql .= " and create_time <= '".$end_time."'";
		}
		if($source != ''){
			$sql .= " and source = ".$source;
		}
		$where = array('usertype' => $usertype, 'user_con' => $user_con, 'stu' => $stu, 'teacher' => $teacher, 'start_time' => $start_time, 'end_time' => $end_time, 'source' => $source);
		$user = new UsersModel();
		$result = $user->lists($sql, $where);
		return $result;
	}

	/*
	 *用户列表入口学员详情
	 */
	public function info($id)
	{
		$user   = new UsersModel();
		$result = $user->info($id);
		return $result;
	}
    /**
     * @return array`
     */
    public function getUsers($where = [])
    {
        $user  = new UsersModel();
        $result  = $user->getUsers($where);
        return $result;
    }

    public function getInfo($where=[])
    {
        $user = M('users');
        $result  = $user->where($where)->find();
        return $result;
    }

    public function add($data=[])
    {
        if(empty($data)) return false;
        $user = M('users');
        $result  = $user->data($data)->add();
        return $result;
    }

    /**
     * @param $id
     * @return mixed
     * id获取信息
     */
    public static function find($id)
    {
        $user   = M('users');
        return $user->find($id);
    }

    /**
     * @param $data
     * @return bool
     */
    public function update($where = [],$data = [])
    {
        if(empty($where)) return false;
        $user = M('users');
        $result     = $user->where($where)->data($data)->save();
        return $result;
    }

    /**
     * 变更账号状态
     */
    public function status($id, $status)
    {
        $user = new UsersModel();
        $res = $user->status($id, $status);
        return $res;
    }

}