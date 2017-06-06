<?php
namespace Common\Model;

use Common\Model\CommonModel;

class ClassUserModel extends CommonModel
{

	/**
	 * @param $where 条件(数组) 
	 * @param $field 字段
	 * @return array
	 */
	public function index($sql, $where) 
	{
		$classuser = M("class_user");
		$count     = $classuser->field(C('DB_PREFIX')."class_user.*, ".C('DB_PREFIX')."users.user_login, ".C('DB_PREFIX')."users.user_no, ".C('DB_PREFIX')."users.user_name")->join("left join ".C('DB_PREFIX')."class on ".C('DB_PREFIX')."class_user.class_id = ".C('DB_PREFIX')."class.id")->join("left join ".C('DB_PREFIX')."users on ".C('DB_PREFIX')."class_user.user_id = ".C('DB_PREFIX')."users.id")->where($sql)->count();
		$Page      = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		// //分页跳转的时候保证查询条件
		if (!empty($where)){
			foreach($where as $key=>$val) {
			    $Page->parameter[$key]   =   urlencode($val);
			}
		}
		$show      = $Page->show();// 分页显示输出
		$data      = $classuser->field(C('DB_PREFIX')."class_user.*, ".C('DB_PREFIX')."users.user_login, ".C('DB_PREFIX')."users.user_no, ".C('DB_PREFIX')."users.user_name")->join("left join ".C('DB_PREFIX')."class on ".C('DB_PREFIX')."class_user.class_id = ".C('DB_PREFIX')."class.id")->join("left join ".C('DB_PREFIX')."users on ".C('DB_PREFIX')."class_user.user_id = ".C('DB_PREFIX')."users.id")->where($sql)->select();
		$result	   = array('data' => $data, 'page' => $show);
		return $result;
	}

	/**
     * 问答操作
     */
    public function qa($id, $no) 
    {   
    	$classuser  = M("class_user");
    	$data       = array('no_qa' => $no);
        $res        = $classuser->where("id = ".$id)->save($data);
        if($res){
        	$result = array('code' => 1, 'msg' => "修改成功");
        	return $result;
        } else {
        	$result = array('code' => 0, 'msg' => "修改失败");
        	return $result;
        }
    }

    /**
     * 话题操作
     */
    public function talk($id, $no) 
    {   
    	$classuser  = M("class_user");
    	$data       = array('no_talk' => $no);
        $res        = $classuser->where("id = ".$id)->save($data);
        if($res){
        	$result = array('code' => 1, 'msg' => "修改成功");
        	return $result;
        } else {
        	$result = array('code' => 0, 'msg' => "修改失败");
        	return $result;
        }
    }
}