<?php
namespace Common\Service;

use Common\Model\InnerMsgTplModel;

class InnerMsgTplService {



    /**
     * @return array
     * 分页获得搜索的数据
     */
    public static function findInnerMsgTpl($where = [])
    {
        $InnerMsgTpl  = new InnerMsgTplModel();
        $result  = $InnerMsgTpl->getAll($where);
        return $result;
    }

    /**
     * @param $data 需要添加的数据（数组）
     * @return array
     */
    public function addData($data)
    {
        $InnerMsgTpl  = new InnerMsgTplModel();
        $result = $InnerMsgTpl->addData($data);
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
        $InnerMsgTpl   = M('inner_msg_tpl');
        return $InnerMsgTpl->find($id);
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
        $InnerMsgTpl  = new InnerMsgTplModel();
        $res = $InnerMsgTpl->update($where,$data);
        return $res;
    }

    /**
     * User: maChuang
     * @param array $where
     * @return mixed
     * 根据条件获取所有班级
     */
    public function getInnerMsgTples($where = [],$field = '')
    {
        $InnerMsgTpl  = new InnerMsgTplModel();
        $res = $InnerMsgTpl->getInnerMsgTpl($where,$field);
        return $res;
    }


}