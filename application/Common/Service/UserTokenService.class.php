<?php
namespace Common\Service;

use Common\Model\UserTokenModel;

class UserTokenService {



    /**
     * @return array
     * 分页获得搜索的数据
     */
    public static function findUserToken($where = [])
    {
        $UserToken  = new UserTokenModel();
        $result  = $UserToken->getAll($where);
        return $result;
    }

    /**
     * @param $data 需要添加的数据（数组）
     * @return array
     */
    public function addData($data)
    {
        $UserToken  = new UserTokenModel();
        $result = $UserToken->addData($data);
        return $result;
    }

    /**
     * User: maChuang
     * @param $id
     * @return mixed
     * id查询
     */
    public static function find($where)
    {
        $UserToken   = M('user_token');
        return $UserToken->where($where)->find();
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
        $UserToken  = new UserTokenModel();
        $res = $UserToken->update($where,$data);
        return $res;
    }

    /**
     * User: maChuang
     * @param array $where
     * @return mixed
     * 根据条件获取所有班级
     */
    public function getUserToken($where = [],$field = '')
    {
        $UserToken  = new UserTokenModel();
        $res = $UserToken->getUserToken($where,$field);
        return $res;
    }


}