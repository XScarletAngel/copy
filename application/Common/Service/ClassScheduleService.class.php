<?php
namespace Common\Service;

use Common\Model\ClassScheduleModel;

class ClassScheduleService {



    /**
     * @return array
     * 分页获得搜索的数据
     */
    public static function findClassScheduleByPage($where = [])
    {
        $classSchedule  = new ClassScheduleModel();
        $result  = $classSchedule->findClassScheduleByPage($where);
        return $result;
    }

    /**
     * @param $data 需要添加的数据（数组）
     * @return array
     */
    public function addData($data)
    {
        $classSchedule  = new ClassScheduleModel();
        $result = $classSchedule->addData($data);
        return $result;
    }

    /**
     * User: maChuang
     * @param $id
     * @return mixed
     * id查询
     */
    public static function find($id)
    {
        $class   =  M('class_schedule');
        return $class->find($id);
    }

    /**
     * User: maChuang
     * @param $where
     * @param array $data
     * @return bool
     * 更新
     */
    public function update($where,$data=[])
    {
        if(empty($where)) return false;
        $class  = new ClassScheduleModel();
        $res = $class->update($where,$data);
        return $res;
    }

    /**
     * User: maChuang
     * @param array $where
     * @return mixed
     * 根据条件获取所有班级
     */
    public function getClassSchedule($where = [])
    {
        $class  = new ClassScheduleModel();
        $res = $class->getClassSchedule($where);
        return $res;
    }

    /**
     * User: maChuang
     * @param $where
     * @param array $data
     * @return bool
     * 删除
     */
    public function delete($id)
    {
        if(empty($id)) return false;
        $class  = new ClassScheduleModel();
        $res = $class->delete($id);
        return $res;
    }


}