<?php
namespace Common\Model;

use Common\Model\CommonModel;

class StuAnswerModel extends CommonModel
{

	/**
	 * @param $where 条件(数组) 
	 * @param $field 字段
	 * @return array
	 */
	public function getall($sql, $where)
	{
		$answer  = M("stu_answer");
		$count   = $answer->where($sql)->count();
		$Page    = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		// //分页跳转的时候保证查询条件
		if (!empty($where)){
			foreach($where as $key=>$val) {
			    $Page->parameter[$key]   =   urlencode($val);
			}
		}
		$show    = $Page->show();// 分页显示输出
		$data    = $answer->where($sql)->limit($Page->firstRow.','.$Page->listRows)->select();
		// echo "<pre>";print_r($data);die;
		$result  = array('data' => $data, 'page' => $show);
		return $result;
	}
}