<?php
namespace Common\Model;

use Common\Model\CommonModel;

class BadwordsModel extends CommonModel
{

	/**
	 * @return array
	 */
	public function getall($where) 
	{
		$badwords   = M('badwords');
		$sql      = "1=1";
		if (!empty($where['word'])){
			$sql .= " and word like '%".$where['word']."%'";
		}
		$count    = $badwords->where($sql)->count();// 查询满足要求的总记录数
		$Page     = new \Think\Page($count,25);// 实例化分页类 传入总记录数和每页显示的记录数(25)
		//分页跳转的时候保证查询条件
		if (!empty($where)){
			foreach($where as $key=>$val) {
			    $Page->parameter[$key]   =   urlencode($val);
			}
		}
		$show     = $Page->show();// 分页显示输出$page->show('Admin')
		$result   = $badwords->where($sql)->limit($Page->firstRow.','.$Page->listRows)->select();
		$res      = array('list' => $result, 'page' => $show, 'where' => $where);
		return $res;
	}

	/**
	 * @param $arr 条件数组
	 * @return array
	 */
	public function getone($arr)
	{
		$badwords   = M('badwords');
		$result     = $badwords->where("word = '".$arr['word']."'")->find();
		return $result;
	}

	/**
	 * @param $arr 条件数组
	 * @return ture false
	 */
	public function add_post($arr)
	{
		$badwords   = M('badwords');
		$result     = $badwords->data($arr)->add();
		return $result;
	}

	/**
	 * @param $id 主键
	 * @return ture false
	 */
	public function del($id)
	{
		$badwords   = M('badwords');
		$result     = $badwords->where("id = ".$id)->delete();
		return $result;
	}
}