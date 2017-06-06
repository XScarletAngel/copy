<?php
namespace Common\Service;

use Common\Model\AdminLogModel;

class AdminLogService {

    /**
     * User: maChuang
     * @return array
     * 分页获得搜索的数据
     */
    public static function getByPage($where = [])
    {
        $adminLog  = new AdminLogModel();
        $result  = $adminLog->getByPage($where);
        return $result;
    }

    /**
     * User: maChuang
     * @param array $where
     * @return mixed
     * 根据搜索条件获得全部数据
     */
    public static function getAll($where = []){
        $adminLog  = new AdminLogModel();
        $result  = $adminLog->getAllAdminLog($where);
        return $result;
    }

    /**
     * User: maChuang
     * @param $data
     * @return \Common\Model\ture
     */
    public function addData($data)
    {
        $adminLog  = new AdminLogModel();
        $result = $adminLog->addData($data);
        return $result;
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
        $adminLog  = new AdminLogModel();
        $res = $adminLog->update($where,$data);
        return $res;
    }

    /**
     * User: maChuang
     * @param $id
     * @return mixed
     * id查询
     */
    public static function find($id)
    {
        return  M('admin_log')->find($id);
    }


}