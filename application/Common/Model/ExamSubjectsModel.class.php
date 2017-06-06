<?php
namespace Common\Model;

use Common\Model\CommonModel;

class ExamSubjectsModel extends CommonModel
{

	/**
	 * @param $where 条件(数组) 
	 * @param $field 字段
	 * @return array
	 */
	public function getall($where) 
	{
		$sql      = "1=1";
		if ($where['school'] != '' && $where['school_con'] != ''){
			$sql .= " and ".$where['school']." like '%".$where['school_con']."%'";
		}
		if ($where['dept'] != '' && $where['dept_con'] != ''){
			$sql .= " and ".$where['dept']." like '%".$where['dept_con']."%'";
		}
		if ($where['major'] != '' && $where['major_con'] != ''){
			$sql .= " and ".$where['major']." like '%".$where['major_con']."%'";
		}
		if ($where['rch_name'] != ''){
			$sql .= " and rch_name = '".$where['rch_name']."'";
		}
		if ($where['lang'] != '' && $where['lang_con'] != ''){
			$sql .= " and ".$where['lang']." like '%".$where['lang_con']."%'";
		}
		if ($where['one'] != '' && $where['one_con'] != ''){
			$sql .= " and ".$where['one']." like '%".$where['one_con']."%'";
		}
		if ($where['two'] != '' && $where['two_con'] != ''){
			$sql .= " and ".$where['two']." like '%".$where['two_con']."%'";
		}
		if ($where['es_status'] != ''){
			$sql .= " and es_status = ".$where['es_status'];
		}
		$sql     .= " and ".C('DB_PREFIX')."exam_subjects.is_del = 1";
		$exams    = M('exam_subjects');
		$count    = $exams->join(' '.C('DB_PREFIX').'research on '.C('DB_PREFIX').'exam_subjects.sch_id = '.C('DB_PREFIX').'research.id')->where($sql)->count();// 查询满足要求的总记录数
		$Page     = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		//分页跳转的时候保证查询条件
		if (!empty($where)){
			foreach($where as $key=>$val) {
			    $Page->parameter[$key]   =   urlencode($val);
			}
		}
		$show     = $Page->show();// 分页显示输出$page->show('Admin')
		$result   = $exams->field(C('DB_PREFIX')."research.school_name, ".C('DB_PREFIX')."research.dept_name, ".C('DB_PREFIX')."research.major_name, ".C('DB_PREFIX')."research.rch_name, ".C('DB_PREFIX')."research.major_code, ".C('DB_PREFIX')."exam_subjects.politics, ".C('DB_PREFIX')."exam_subjects.lang, ".C('DB_PREFIX')."exam_subjects.bus_one, ".C('DB_PREFIX')."exam_subjects.bus_two, ".C('DB_PREFIX')."exam_subjects.pol_code, ".C('DB_PREFIX')."exam_subjects.lang_code, ".C('DB_PREFIX')."exam_subjects.one_code, ".C('DB_PREFIX')."exam_subjects.two_code, ".C('DB_PREFIX')."exam_subjects.es_status")->join(' '.C('DB_PREFIX').'research on '.C('DB_PREFIX').'exam_subjects.sch_id = '.C('DB_PREFIX').'research.id')->where($sql)->limit($Page->firstRow.','.$Page->listRows)->select();

		$data	  = array('page' => $show, 'result' => $result, 'where' => $where);
		return $data;
	}
}