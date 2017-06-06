<?php
namespace Common\Service;

use Common\Model\ExamSubjectsModel;

class ExamSubjectsService 
{
	/**
	 * @param $where 条件(数组) 
	 * @param $field 字段
	 * @return array
	 */
	public function getall($where) 
	{
		$exams    = new ExamSubjectsModel;
    	$contents = $exams->getall($where);
    	return $contents;
	}

}