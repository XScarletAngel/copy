<?php
namespace Common\Service;

use Common\Model\InnerMsgModel;

class InnerMsgService {



    /**
     * @return array
     * 分页获得搜索的数据
     */
    public static function findInnerMsg($where = [])
    {
        $InnerMsg  = new InnerMsgModel();
        $result  = $InnerMsg->findInnerMsg($where);
        return $result;
    }

    /**
     * @param $data 需要添加的数据（数组）
     * @return array
     */
    public function addData($data)
    {
        $InnerMsg  = new InnerMsgModel();
        $result = $InnerMsg->addData($data);
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
        $InnerMsg   = M('inner_msg');
        return $InnerMsg->find($id);
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
        $InnerMsg  = new InnerMsgModel();
        $res = $InnerMsg->update($where,$data);
        return $res;
    }

    /**
     * User: maChuang
     * @param array $where
     * @return mixed
     * 根据条件获取所有班级
     */
    public function getInnerMsges($where = [],$field = '')
    {
        $InnerMsg  = new InnerMsgModel();
        $res = $InnerMsg->getInnerMsg($where,$field);
        return $res;
    }


}