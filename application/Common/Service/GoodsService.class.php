<?php
namespace Common\Service;

use Common\Model\GoodsModel;

class GoodsService {



    /**
     * @return array
     * 分页获得搜索的数据
     */
    public static function findGoods($where = [])
    {
        $goods  = new GoodsModel();
        $result  = $goods->findGoods($where);
        return $result;
    }

    /**
     * @param $data 需要添加的数据（数组）
     * @return array
     */
    public function addData($data)
    {
        $course  = new GoodsModel();
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
        $class   = M('goods');
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
        $class  = new GoodsModel();
        return  $class->update($where,$data);
    }

    /**
     * User: maChuang
     * @param array $where
     * @return mixed
     * 根据条件获取所有班级
     */
    public static function getGoods($where = [],$field = '')
    {
        $g  = new GoodsModel();
        return $g->getGoods($where,$field);
    }


}