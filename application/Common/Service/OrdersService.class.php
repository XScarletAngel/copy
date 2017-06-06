<?php
namespace Common\Service;

use Common\Model\OrdersModel;

class OrdersService {



    /**
     * @return array
     * 分页获得搜索的数据
     */
    public function ordersByPage($where = [])
    {
        $orders  = new OrdersModel();
        $result  = $orders->ordersByPage($where);
        foreach($result['list'] as $k=>$v){
            //获得商品名称
            $goodInfo = GoodsService::find($v['goods_id']);
            $result['list'][$k]['goods_name'] = $goodInfo['goods_name'];
            //获得创建人的姓名
            if(!empty($v['create_uid'])){
                $cUserInfo = UsersService::find($v['create_uid']);
                $result['list'][$k]['create_username'] = $cUserInfo['user_name'];
            }
            //获取校区，分校信息
            $zone = StudentService::find(['user_id'=>$v['user_id']]);
            $result['list'][$k]['zone_name']     =$zone[0]['zone_name'];
            $result['list'][$k]['sub_zone_name'] =$zone[0]['sub_zone_name'];
        }
        return $result;
    }

    /**
     * @param $data 需要添加的数据（数组）
     * @return array
     */
    public function addData($data)
    {
        $course  = new OrdersModel();
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
        $orders   = M('orders');
        return $orders->find($id);
    }
    
    /**
     * User: maChuang
     * @param array $where
     * @return mixed
     * 根据条件获取所有班级
     */
    public function getAll($where = [])
    {
        $orders  = new OrdersModel();
        $res = $orders->getAll($where);
        return $res;
    }

    public static function count($where)
    {
        $orders   = M('orders');
        return $orders->where($where)->count();
    }


}