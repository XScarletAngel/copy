<?php
namespace Common\Service;

use Common\Model\PaperModel;

class PaperService {



    /**
     * @return array
     * 分页获得搜索的数据
     */
    public static function findPaper($where = [])
    {
        $paper  = new PaperModel();
        $result  = $paper->findPaper($where);
        return $result;
    }

    /**
     * @param $data 需要添加的数据（数组）
     * @return array
     */
    public static function addData($data)
    {
        $course  = new PaperModel();
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
        $paper   = M('paper');
        return $paper->find($id);
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
        $paper  = new PaperModel();
        $res = $paper->update($where,$data);
        return $res;
    }

    /**
     * User: maChuang
     * @param array $where
     * @return mixed
     * 根据条件获取所有班级
     */
    public function getPapers($where = [],$field = '')
    {
        $paper  = new PaperModel();
        $res = $paper->getPaper($where,$field);
        return $res;
    }


}