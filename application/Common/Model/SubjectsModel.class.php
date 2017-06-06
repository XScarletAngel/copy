<?php
namespace Common\Model;

use Common\Model\CommonModel;

class SubjectsModel extends CommonModel
{

	/**
	 * @param $where 条件(数组) 
	 * @param $field 字段
	 * @return array
	 */
	public function getall($where, $field) 
	{
		$sql      = "1=1";
		if ($where['sub'] != '' && $where['count'] != ''){
			$sql .= " and ".$where['sub']." = ".$where['count'];
		}
		if ($where['sub_status'] != ''){
			$sql .= " and sub_status = ".$where['sub_status'];
		}
		$sub      = M('subjects');
		$count    = $sub->where($sql)->count();// 查询满足要求的总记录数
		$Page     = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		//分页跳转的时候保证查询条件
		if (!empty($where)){
			foreach($where as $key=>$val) {
			    $Page->parameter[$key]   =   urlencode($val);
			}
		}
		$show     = $Page->show();// 分页显示输出$page->show('Admin')
		$result   = $sub->field($field)->where($sql)->limit($Page->firstRow.','.$Page->listRows)->select();
		$data	  = array('page' => $show, 'result' => $result, 'where' => $where);
		return $data;
	}
}