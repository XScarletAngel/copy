<?php
namespace Common\Service;

use Common\Model\TeacherModel;

class TeacherService {




    /**
     * @return array
     */
    public static function getDataByPage($where = [])
    {
        $tc  = new TeacherModel();
        $result  = $tc->getDataByPage($where);
        return $result;
    }

    /**
     * @param $data 需要添加的数据（数组）
     * @return array
     */
    public function addData($data)
    {
        $tc  = new TeacherModel();
        $result = $tc->addData($data);
        return $result;
    }

    /**
     * @param array $where
     * @return mixed
     * 搜索
     */
    public function select($where = [],$field='')
    {
        $tc   = M('teacher');
        return $tc->where($where)->field($field)->select();
    }

    /**
     * @param $id
     * @return mixed
     * id获取信息
     */
    public static function find($id)
    {
        $tc   = M('teacher');
        return $tc->find($id);
    }

    /**
     * @param $where
     * @param array $data
     * @return bool
     * 更新
     */
    public function update($where,$data=[])
    {
        if(empty($where)) return false;
        $tc = new TeacherModel();
        $res = $tc->update($where,$data);
        return $res;
    }

    public function getSignedTeacher()
    {
        return $this->select(['sign_status'=>1]);
    }
}