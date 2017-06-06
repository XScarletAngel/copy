<?php
namespace Common\Service;

use Common\Model\SubjectsModel;

class SubjectsService 
{
	/**
	 * @param $where 条件(数组) 
	 * @param $field 字段
	 * @return array
	 */
	public function getall($where, $field="id, sub_code, sub_name, sub_status") 
	{
		$sub       = new SubjectsModel;
    	$categorys = $sub->getall($where, $field);
    	return $categorys;
	}

}