<?php
namespace Common\Service;

use Common\Model\PushMsgModel;

class PushMsgService {



    /**
     * @return array
     * 分页获得搜索的数据
     */
    public static function findPushMsg($where = [])
    {
        $PushMsg  = new PushMsgModel();
        $result  = $PushMsg->findPushMsg($where);
        foreach ($result['list'] as $k=>$v){
            $userInfo = UsersService::find($v['user_id']);
            $result['list'][$k]['user_name'] =  $userInfo['user_name'];
        }
        return $result;
    }

    /**
     * @param $data 需要添加的数据（数组）
     * @return array
     */
    public function addData($data)
    {
        $course  = new PushMsgModel();
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
        $PushMsg   = M('push_msg');
        return $PushMsg->find($id);
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
        $PushMsg  = new PushMsgModel();
        $res = $PushMsg->update($where,$data);
        return $res;
    }

    /**
     * User: maChuang
     * @param array $where
     * @return mixed
     * 根据条件获取所有班级
     */
    public function getPushMsges($where = [],$field = '')
    {
        $PushMsg  = new PushMsgModel();
        $res = $PushMsg->getPushMsg($where,$field);
        return $res;
    }


}