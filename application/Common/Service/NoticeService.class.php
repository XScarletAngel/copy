<?php
namespace Common\Service;

use Common\Model\SystemNoticeModel;

class NoticeService
{
	private $mod_cs = null;

	public function __construct()
	{
		$this->mod = new SystemNoticeModel();
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
			$where .= "and content like '%".$req['kw']."%'  ";
		}
		if (!empty($req['is_stu'])) $where .= " and is_stu = ".$req['is_stu'];
		if (!empty($req['is_teacher'])) $where .= " and is_teacher =".$req['is_teacher'];
		if (!empty($req['is_frontend'])) $where .= " and is_frontend = ".$req['is_frontend'];
		if (!empty($req['is_oper'])) $where .= " and is_oper =".$req['is_oper'];
		if (!empty($req['notice_status'])) $where .= " and notice_status =".$req['notice_status'];

		if (!empty($req['begin_time']) && !empty($req['end_time'])) {
			$where.=" and add_time>='".$req['begin_time']."' and add_time<='".$req['end_time']."' ";
		}

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