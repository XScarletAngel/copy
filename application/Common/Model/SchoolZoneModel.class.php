<?php
namespace Common\Model;

use Common\Model\CommonModel;

class SchoolZoneModel extends CommonModel
{

	/**
	 * @param $where 条件(数组) 
	 * @param $field 字段
	 * @return array
	 */
	public function getall() 
	{
		$schoolzone = M('school_zone');
		$result     = $schoolzone->where("is_del = 1")->select();
		return $result;
	}

	/**
	 * @param $data 需要添加的数据(数组)
	 * @return ture false
	 */
	public function add_post($data) 
	{
		$schoolzone = M('school_zone');
		$result     = $schoolzone->data($data)->add();
		return $result;
	}

	/**
	 * @param $id 需要修改的数据id
	 * @return array
	 */
	public function edit($id) 
	{
		$schoolzone = M('school_zone');
		$result     = $schoolzone->where('id = '.$id)->find();
		return $result;
	}

	/**
	 * @param $id 需要修改的数据id
	 * @return array
	 */
	public function edit_post($data) 
	{
		$schoolzone = M('school_zone');
		$result     = $schoolzone->where('id = '.$data['id'])->save($data);
		return $result;
	}

	/**
	 * @param $id  要删除的id
	 * @return ture false
	 */
	public function delete($id)
	{
		$db     = M('school_zone');
		$arr    = $db->where("parentid = ".$id)->find();
		if (empty($arr)){
			$data   = array('is_del' => 2);
			$result = $db->where("id = ".$id)->save($data);
		} else {
			$result = 3;
		}
		return $result;
	}

	/**
	 * 通过父ID获取子元素
	 * @param int $pid
	 * @param string $field
	 * @return array
	 */
	public function getSubZone($pid = 0, $field = "id, sz_name")
	{
		return $this->field($field)->where(['is_del'=>1,'parentid'=>$pid])->select();
	}

    public function getInfo($where, $field = "sz_name")
    {
        return $this->field($field)->where($where)->select();
    }
}