<?php
namespace Common\Service;

use Common\Model\PaperExerciseModel;

class PaperExerciseService {



    /**
     * @return array
     * 分页获得搜索的数据
     */
    public static function findPaperExercise($where = [])
    {
        $PaperExercise  = new PaperExerciseModel();
        $result  = $PaperExercise->findPaperExercise($where);
        return $result;
    }

    /**
     * @param $data 需要添加的数据（数组）
     * @return array
     */
    public static function addData($data)
    {
        $course  = new PaperExerciseModel();
        $result = $course->addData($data);
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
        $PaperExercise   = M('paper_exercise');
        return $PaperExercise->find($id);
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
        $PaperExercise  = new PaperExerciseModel();
        $res = $PaperExercise->update($where,$data);
        return $res;
    }

    /**
     * User: maChuang
     * @param array $where
     * @return mixed
     * 根据条件获取所有班级
     */
    public function getPaperExercises($where = [],$field = '')
    {
        $PaperExercise  = new PaperExerciseModel();
        $res = $PaperExercise->getPaperExercise($where,$field);
        return $res;
    }


}