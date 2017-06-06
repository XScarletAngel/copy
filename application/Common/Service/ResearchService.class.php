<?php
namespace Common\Service;

use Common\Model\ResearchModel;

class ResearchService {

	/**
     * @param $where 条件(数组)
	 * @param $field 字段
     * @return array
     */
    public function getAll($where, $field="id, school_name, dept_name, major_name, rch_name, rch_code, rch_status")
    {   
        $resear  = new ResearchModel();
        $result  = $resear->getAll($where, $field);
        return $result;
    }

    /**
     * @param $field 字段
     * @return array
     */
    public function getschool($field="id, area_id, school_name, code")
    {   
        $school  = new ResearchModel();
        $result  = $school->getschool($field);
        return $result;
    }

    /**
     * @param $id 学校id
     * @return array
     */
    public function getdept($id)
    {   
        $dept   = new ResearchModel();
        $result = $dept->getdept($id);
        return $result;
    }

    /**
     * @param $id 院系id
     * @return array
     */
    public function getmajor($id)
    {   
        $major  = new ResearchModel();
        $result = $major->getmajor($id);
        return $result;
    }

    /**
     * @param $id 专业id
     * @return array
     */
    public function getresearch($id)
    {   
        $research = new ResearchModel();
        $result   = $research->getresearch($id);
        return $result;
    }

    /**
     * @param $data 需要添加的数据（数组）
     * @return array
     */
    public function add_post($data)
    {   
        $rch  = new ResearchModel();
        $result = $rch->add_post($data);
        return $result;
    }

    /**
     * @param $id 方向id
     * @param $field 需要查询的字段
     * @return array
     */
    public function edit($id, $field="id, school_name, dept_name, major_name, rch_name, rch_code, rch_status")
    {   
        $research = new ResearchModel();
        $result   = $research->edit($id, $field);
        return $result;
    }

    /**
     * @param $data 需要修改的数据
     * @return ture false
     */
    public function edit_post($data)
    {   
        $research = new ResearchModel();
        $result   = $research->edit_post($data);
        return $result;
    }

    /**
     * @param $id 主键
     * @return ture false
     */
    public function delete($id)
    {
        $del    = new ResearchModel;
        $result = $del->delete($id);
        return $result;
    }
}