<?php
namespace Common\Service;

use Common\Model\SchoolDeptModel;

class SchoolDeptService {

	/**
     * @param $where 条件(数组)
	 * @param $field 字段
     * @return array
     */
    public function getAll($where)
    {   
        $school  = new SchoolDeptModel();
        $result  = $school->getAll($where);
        return $result;
    }

    /**
     * @return array
     */
    public function getschool()
    {
        $school  = new SchoolDeptModel();
        $result  = $school->getschool();
        return $result;
    }

    /**
     * @param $data 数组
     * @return 返回的id
     */
    public function add_post($data)
    {
        $school  = new SchoolDeptModel();
        $result  = $school->add_post($data);
        return $result;
    }

    /**
     * @param id
     * @return 返回的数组
     */
     
    public function edit($id)
    {
        $school  = new SchoolDeptModel();
        $result  = $school->edit($id);
        return $result;
    }

    /**
     * @param $data 数组
     * @return 返回ture false
     */
    public function edit_post($data)
    {
        $school  = new SchoolDeptModel();
        $result  = $school->edit_post($data);
        return $result;
    }

    /**
     * @param $id 主键
     * @return ture false
     */
    public function delete($id)
    {
        $del    = new SchoolDeptModel;
        $result = $del->delete($id);
        return $result;
    }
}