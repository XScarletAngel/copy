<?php
namespace Common\Service;

use Common\Model\ClassServiceModel;

class ClassServiceService
{
	private $mod_cs = null;

	public function __construct()
	{
		$this->mod_cs = new ClassServiceModel();
	}

	/**
	 * 通过关键词获取列表
	 * @param $kw
	 * @return array
	 */
	public function getAll($kw)
	{
		if (!empty($kw)) {
			$where = "srv_name like '%$kw%' or srv_info like '%$kw%' ";
		} else {
			$where = "1 =1 ";
		}
		$class  = new ClassServiceModel();
		$result = $class->getAll($where);
		return $result;
	}

	/**
	 * 添加一行
	 * @param $row
	 * @return array|mixed
	 */
	public function addRow($row)
	{
		if (empty($row)) {
			return [];
		}
		return $this->mod_cs->add($row);
	}

	/**
	 * 更新数据,row 包含ID
	 * @param $row
	 * @return array|bool
	 */
	public function saveRow($row)
	{
		if (empty($row)) {
			return [];
		}
		return $this->mod_cs->save($row);
	}

	/**
	 * 获取班级的单个服务
	 * @param $srv_id
	 * @param string $field
	 * @return array|mixed
	 */
	public function queryById($srv_id, $field = "*")
	{
		if (empty($srv_id)) {
			return [];
		}
		return $this->mod_cs->field($field)->where(['id'=>$srv_id])->find();
	}
}