<?php
namespace Common\Model;

use Common\Model\CommonModel;

class ComplainModel extends CommonModel
{

	/**
	 * @param $where 条件(数组) 
	 * @param $field 字段
	 * @return array
	 */
	public function getAll($sql, $where)
	{
		$complain = M('Complain');
        $count = $complain->field(C('DB_PREFIX').'complain.*, a.user_login, a.user_nicename, b.user_login as to_login, b.user_nicename as to_nicename, c.class_name, c.zone_name, c.sub_zone_name')
        ->join('left join '.C('DB_PREFIX').'users as a on '.C('DB_PREFIX').'complain.user_id = a.id')
        ->join('left join '.C('DB_PREFIX').'users as b on '.C('DB_PREFIX').'complain.to_user_id = b.id')
        ->join('left join '.C('DB_PREFIX').'class as c on '.C('DB_PREFIX').'complain.class_id = c.id')
        ->where($sql)
        ->count();  // 查找所有的问题
		 $Page = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		 //分页跳转的时候保证查询条件
		 if (!empty($where)){
		 	foreach($where as $key=>$val) {
		 	    $Page->parameter[$key]   =   urlencode($val);
		 	}
		 }
        $show  = $Page->show();// 分页显示输出
		$complain = $complain->field(C('DB_PREFIX').'complain.*, a.user_login, a.user_nicename, b.user_login as to_login, b.user_nicename as to_nicename, c.class_name, c.zone_name, c.sub_zone_name')
        ->join('left join '.C('DB_PREFIX').'users as a on '.C('DB_PREFIX').'complain.user_id = a.id')
        ->join('left join '.C('DB_PREFIX').'users as b on '.C('DB_PREFIX').'complain.to_user_id = b.id')
        ->join('left join '.C('DB_PREFIX').'class as c on '.C('DB_PREFIX').'complain.class_id = c.id')
        ->where($sql)
        ->limit($Page->firstRow.','.$Page->listRows)
        ->order("add_time desc")
        ->select();  // 查找所有的问题
		$data	  = array('complain' => $complain, 'page' => $show);
		return $data;
	}

	/**
	 * @param $id 条件
	 * @return array
	 */
	public function details($id)
	{
		$complain  = M('Complain as c');
		// 查询投诉人的内容
		$com       = $complain->field("c.*, u.user_nicename, u.avatar, u.mobile")->join(C('DB_PREFIX')."users as u on c.user_id = u.id")->where("c.id = %d", $id)->find();

		if ($com['com_type'] == 1 || $com['com_type'] == 2 || $com['com_type'] == 3) {

			$sql   = C('DB_PREFIX')."topic on c.com_id = ".C('DB_PREFIX')."topic.id";
			$field = "c.*, user_nicename, avatar, mobile, topic_name as title, ".C('DB_PREFIX')."topic.content as con";

		} else {

			$sql   = C('DB_PREFIX')."question on c.com_id = ".C('DB_PREFIX')."question.id";
			$field = "c.*, user_nicename, avatar, mobile, que_name as title, ".C('DB_PREFIX')."question.content as con";

		}
		// 查询被投诉人内容
		$tocom     = $complain->field($field)->join($sql)->join(C('DB_PREFIX')."users on c.to_user_id = ".C('DB_PREFIX')."users.id")->where("c.id = ".$id)->find();
		
		$data      = array('com' => $com, 'tocom' => $tocom);
		return $data;
	}


	/**
	 * @param $data 数组
	 * @return array
	 */
	public function deal($data)
	{
		$complain  = M('Complain');
		$result    = $complain->where("id = ".$data['id'])->data($data)->save();
		return result;
	}



}