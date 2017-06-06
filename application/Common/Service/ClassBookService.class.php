<?php
namespace Common\Service;

use Common\Model\ClassBookModel;

class ClassBookService {



    /**
     * @return array
     */
    public static function findClassBook($where = [])
    {
        $classBook  = new ClassBookModel();
        $result  = $classBook->findClassBook($where);
        return $result;
    }

    /**
     * @param $data 需要添加的数据（数组）
     * @return array
     */
    public static function addData($data)
    {
        $course  = new ClassBookModel();
        $result = $course->addData($data);
        return $result;
    }

    public static function find($id)
    {
        $class   = M('class_book');
        return $class->find($id);
    }

    public static function update($where,$data)
    {
        if(empty($where)) return false;
        $class = M('class_book');
        $class->where($where)->save($data); // 根据条件更新记录
    }

}