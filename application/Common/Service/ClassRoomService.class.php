<?php
namespace Common\Service;

use Common\Model\ClassRoomModel;

class ClassRoomService {


    /**
     * @return array`
     */
    public static function getClassRoom($where = [])
    {
        $classRoom  = new ClassRoomModel();
        $result  = $classRoom->getClassRoom($where);
        return $result;
    }

    public static function getAllRoom($where = [])
    {
        $classRoom  = new ClassRoomModel();
        $result  = $classRoom->getAllClass($where);
        return $result;
    }

    /**
     * @param $data 需要添加的数据（数组）
     * @return array
     */
    public function addData($data)
    {
        $courseRoom  = new ClassRoomModel();
        $result = $courseRoom->addData($data);
        return $result;
    }

    public function addRoom($data)
    {
        if(empty($data)) return false;
        $classRoom = new ClassRoomModel();
        $res = $classRoom->add_post($data);
        return $res;
    }

    public function update($data)
    {
        if(empty($data)) return false;
        $classRoom = new ClassRoomModel();
        $res = $classRoom->update($data);
        return $res;
    }

    public function delete($id)
    {
        if(empty($id)) return false;
        $classRoom = new ClassRoomModel();
        $res = $classRoom->delete(['id'=>$id]);
        return $res;
    }

    public static function find($id)
    {
        if(empty($id)) return false;
        $classRoom = M('class_room');// 实例化对象
        $list = $classRoom->find($id);
        //$classRoom-> getLastSql();
        return $list;
    }

}