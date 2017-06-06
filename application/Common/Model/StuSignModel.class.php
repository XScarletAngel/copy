<?php
namespace Common\Model;

use Common\Model\CommonModel;

class StuSignModel extends CommonModel
{

	/**
	 * @param $where 条件(数组) 
	 * @param $field 字段
	 * @return array
	 */
	public function getall($sql, $where)
	{
		$stusign = M("stu_sign");
		$count   = $stusign->where($sql)->count();
		$Page    = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		// //分页跳转的时候保证查询条件
		if (!empty($where)){
			foreach($where as $key=>$val) {
			    $Page->parameter[$key]   =   urlencode($val);
			}
		}
		$show    = $Page->show();// 分页显示输出
		$data    = $stusign->where($sql)->limit($Page->firstRow.','.$Page->listRows)->select();
		// echo "<pre>";print_r($data);die;
		$result  = array('data' => $data, 'page' => $show);
		return $result;
	}

	/**
     * @param $id 条件
	 * @return ture  false
     */
    public function sign($id)
    {
        $stusign = M("stu_sign");
        $time    = date('Y-m-d H:i:s', time());
        $data    = array('sign_status' => '2', 'sign_time' => $time);
        $result  = $stusign->where("id = ".$id)->data($data)->save();
        return $result;
    }

    /**
     * @param $id 条件
     * @param $value 要添加的值(字符串)
	 * @return ture  false
     */
    public function remark($id, $value)
    {
        $stusign = M("stu_sign");
        $data    = array('remark' => $value);
        $result  = $stusign->where("id = ".$id)->data($data)->save();
        return $result;
    }
}