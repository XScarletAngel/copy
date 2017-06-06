<?php
namespace Common\Service;

use Common\Model\ClassScheduleModel;
use Common\Model\SchoolModel;

class SchoolService {

	/**
     * @param $where 条件(数组)
	 * @param $field 字段
     * @return array
     */
    public function getAll($where)
    {
        $school  = new SchoolModel();
        $result  = $school->getAll($where);
        return $result;
    }

    /**
     * @return array
     */
    public function getArea()
    {
        $school  = new SchoolModel();
        $result  = $school->getArea();
        return $result;
    }

    /**
     * @param $data 数组
     * @return 返回的id
     */
    public function add_post($data)
    {
        $school  = new SchoolModel();
        $result  = $school->add_post($data);
        return $result;
    }

    /**
     * @param id
     * @return 返回的数组
     */
    public function edit($id)
    {
        $school  = new SchoolModel();
        $result  = $school->edit($id);
        return $result;
    }

    /**
     * @param $data 数组
     * @return 返回ture false
     */
    public function edit_post($data)
    {
        $school  = new SchoolModel();
        $result  = $school->edit_post($data);
        return $result;
    }

    /**
     * @param $id 主键
     * @return ture false
     */
    public function delete($id)
    {
        $del    = new SchoolModel;
        $result = $del->delete($id);
        return $result;
    }

    /**
     * @param $id 主键
     * @return ture false
     */
    public function delLesson($id)
    {
        $del    = new ClassScheduleModel();
        $result = $del->delete($id);
        return $result;
    }

    /**
     * @return array
     * 根据条件获取全部学校
     */
    public function getSchools($where=[])
    {
        $school  = new SchoolModel();
        $result  = $school->getSchools($where);
        return $result;
    }

    /**
     * User: maChuang
     * @param array $where
     * @return mixed
     * 根据条件获取全部所属院系
     */
    public static function getDepts($where = [])
    {
        $dept   = M('school_dept');
        $result   = $dept->where($where)->select();
        return $result;
    }

    /**
     * User: maChuang
     * @param array $where
     * @return mixed
     * 根据条件获取全部考研专业
     */
    public static function getmajors($where = [])
    {
        $major   = M('school_major');
        $result   = $major->where($where)->select();
        return $result;
    }

    /**
     * User: maChuang
     * @param array $where
     * @return mixed
     * 根据条件获取全部考研方向
     */
    public static function getResearchs($where = [])
    {
        $research   = M('research');
        $result   = $research->where($where)->select();
        return $result;
    }




}