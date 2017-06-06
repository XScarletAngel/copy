<?php
namespace Common\Model;

class OrdersModel extends CommonModel
{
    protected $_validate = [];

    //获得全部
    public function getAll($where = [])
    {
        $orders   = M('orders');
        $result = $orders->where($where)->select();
        return $result;
    }

    //分页获取
    public function ordersByPage($where = [])
    {
        $orders = M('orders');// 实例化对象
        $count  = $orders->where($where)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        //分页跳转的时候保证查询条件
        foreach($where as $key=>$val) {
            $Page->parameter[$key]   =   urlencode($val);
        }
        $show       = $Page->show();// 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $orders->where($where)->order(array("add_time"=>"DESC"))->limit($Page->firstRow.','.$Page->listRows)->select();
        $orders->getLastSql();
        $result=[
            'list'=>$list,
            'page'=>$show,
        ];

        return $result;
    }

    /**
     * @param $data 需要添加的数据（数组）
     * @return ture false
     */
    public function addData($data)
    {
        $orders  = M('orders');
        $result = $orders->data($data)->add();
        return $result;
    }

}