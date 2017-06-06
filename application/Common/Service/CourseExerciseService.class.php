<?php
namespace Common\Service;

use Common\Model\CourseExerciseModel;

class CourseExerciseService {



    /**
     * @return array
     * 分页获得搜索的数据
     */
    public static function findCourseExercise($where = [])
    {
        $courseExercise  = new CourseExerciseModel();
        $result  = $courseExercise->findCourseExercise($where);
        return $result;
    }

    /**
     * @param $data 需要添加的数据（数组）
     * @return array
     */
    public static function addData($data)
    {
        $courseExercise  = new CourseExerciseModel();
        $result = $courseExercise->addData($data);
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
        $courseExercise   = M('CourseExercise');
        return $courseExercise->find($id);
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
        $courseExercise  = new CourseExerciseModel();
        $res = $courseExercise->update($where,$data);
        return $res;
    }

    /**
     * User: maChuang
     * @param array $where
     * @return mixed
     * 根据条件获取所有班级
     */
    public function getCourseExercises($where = [],$field = '')
    {
        $courseExercise  = new CourseExerciseModel();
        $res = $courseExercise->getCourseExercise($where,$field);
        return $res;
    }


}