<?php
namespace Common\Model;

use Common\Model\CommonModel;

class MajorModel extends CommonModel
{

	/**
	 * @param $field 字段名称
	 * @return array
	 */
	public function getAll($field="id, parentid, major_name, major_type, major_code, level") 
	{
		$result = $this->field($field)->where("is_del = 1")->select();
		return $result;
	}

	/**
	 * @param $field 字段名称
	 * @return array
	 */
	public function add($post) 
	{	
		$major = M('major');
		$result = $major->data($post)->add();
		return $result;
	}

	/**
	 * @param $field 字段名称
	 * @return array
	 */
	public function edit($id) 
	{	
		$major = M('major');
		$result = $major->where('id = '.$id)->find();
		return $result;
	}

	/**
     * @param $id id值
     * @param $post array
     * @return 返回ture false
     */
    public function edit_post($id, $post)
    {
        $major = new MajorModel();
        $result = $major->where('id = '.$id)->save($post);
        return $result;
    }

    /**
	 * @param $dbname  数据库名称
	 * @param $id  要删除的id
	 * @return ture false
	 */
	public function delete($id)
	{
		$db     = M('major');
		$arr    = $db->where("parentid = ".$id)->find();
		if (empty($arr)){
			$data   = array('is_del' => 2);
			$result = $db->where("id = ".$id)->save($data);
		} else {
			$result = 3;
		}
		return $result;
	}

}