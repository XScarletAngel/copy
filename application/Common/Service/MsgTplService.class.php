<?php
namespace Common\Service;

use Common\Model\InnerMsgTplModel;

class MsgTplService
{
	private $mod = null;

	public function __construct()
	{
		$this->mod = new InnerMsgTplModel();
	}

	/**
	 * 获取列表
	 * @param $req
	 * @return array
	 */
	public function getAll($req)
	{
		$where = "1 =1 ";
		if (!empty($req['kw'])) {
			$where .= " and ( tpl_name like '%".$req['kw']."%' or tpl_content like '%".$req['kw']."%') ";
		}
		if (!empty($req['msg_type'])) $where .= " and msg_type = ".$req['msg_type'];
		if (!empty($req['is_push'])) $where .= " and is_push = ".$req['is_push'];
		if (!empty($req['tpl_status'])) $where .= " and tpl_status =".$req['tpl_status'];

		$result = $this->mod->getAll($where);
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
		return $this->mod->add($row);
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
		return $this->mod->save($row);
	}

	/**
	 * 获取班级的单个服务
	 * @param $id
	 * @param string $field
	 * @return array|mixed
	 */
	public function queryById($id, $field = "*")
	{
		if (empty($id)) {
			return [];
		}
		return $this->mod->field($field)->where(['id'=>$id])->find();
	}
}