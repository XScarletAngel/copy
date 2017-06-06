<?php
namespace Common\Service;

use Common\Model\BadwordsModel;

class BadwordsService 
{
	/**
	 * @param $where 条件(数组)
	 * @return array
	 */
	public function getall($where)
	{
		$badwords   = new BadwordsModel;
		$result     = $badwords->getall($where);
		return $result;
	}

	/**
	 * @param $arr 数组
	 * @return array
	 */
	public function getone($arr)
	{
		$badwords   = new BadwordsModel;
		$result     = $badwords->getone($arr);
		return $result;
	}

	/**
	 * @param $arr 数组
	 * @return ture false
	 */
	public function add_post($arr)
	{
		$badwords   = new BadwordsModel;
		$result     = $badwords->add_post($arr);
		return $result;
	}

	/**
	 * @param $id 主键
	 * @return ture false
	 */
	public function del($id)
	{
		$badwords   = new BadwordsModel;
		$result     = $badwords->del($id);
		return $result;
	}
}